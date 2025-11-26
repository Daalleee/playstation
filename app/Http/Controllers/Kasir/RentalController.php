<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        Gate::authorize('access-kasir');
        $rentals = Rental::with(['customer', 'items.rentable'])->latest()->paginate(10);
        return view('kasir.rentals.index', compact('rentals'));
    }

    public function create()
    {
        Gate::authorize('access-kasir');
        $units = UnitPS::where('stok', '>', 0)->orderBy('nama')->get();
        $games = Game::where('stok', '>', 0)->orderBy('judul')->get();
        $accessories = Accessory::where('stok', '>', 0)->orderBy('nama')->get();
        $customers = \App\Models\User::where('role', 'pelanggan')->orderBy('name')->get();
        return view('kasir.rentals.create', compact('units', 'games', 'accessories', 'customers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('access-kasir');
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'start_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'in:unit_ps,game,accessory'],
            'items.*.id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated) {
            $rental = Rental::create([
                'user_id' => $validated['user_id'],
                'handled_by' => Auth::id(),
                'start_at' => $validated['start_at'],
                'due_at' => $validated['due_at'] ?? null,
                'status' => 'sedang_disewa',
                'subtotal' => 0,
                'discount' => $validated['discount'] ?? 0,
                'total' => 0,
                'paid' => $validated['paid'] ?? 0,
            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                [$rentableType, $model] = match ($item['type']) {
                    'unit_ps' => [UnitPS::class, new UnitPS()],
                    'game' => [Game::class, new Game()],
                    'accessory' => [Accessory::class, new Accessory()],
                };

                $rentable = $model->newQuery()->lockForUpdate()->findOrFail($item['id']);

                if ($item['type'] === 'unit_ps') {
                    if ($rentable->stock < $item['quantity']) {
                        abort(422, 'Stok Unit PS tidak cukup');
                    }
                    $rentable->stock -= $item['quantity'];
                    // if ($rentable->stock === 0) {
                    //     $rentable->status = 'rented';
                    // }
                    $rentable->save();
                } else {
                    if ($rentable->stok < $item['quantity']) {
                        abort(422, 'Stok tidak cukup');
                    }
                    $rentable->decrement('stok', $item['quantity']);
                }

                $lineTotal = $item['quantity'] * $item['price'];
                $subtotal += $lineTotal;

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'rentable_type' => $rentableType,
                    'rentable_id' => $rentable->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $lineTotal,
                ]);
            }

            $rental->subtotal = $subtotal;
            $rental->total = max(0, $subtotal - ($rental->discount ?? 0));
            $rental->save();

            return redirect()->route('kasir.rentals.show', $rental)->with('status', 'Rental dibuat');
        });
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-kasir');
        $rental->load(['customer', 'items', 'payments']);
        return view('kasir.rentals.show', compact('rental'));
    }

    public function return(Rental $rental)
    {
        Gate::authorize('access-kasir');
        if (!in_array($rental->status, ['sedang_disewa'])) {
            return back()->with('status', 'Rental tidak dalam status sedang_disewa');
        }

        DB::transaction(function () use ($rental) {
            $rental->load('items');
            foreach ($rental->items as $item) {
                if ($item->rentable_type === UnitPS::class) {
                    $unit = UnitPS::lockForUpdate()->find($item->rentable_id);
                    if ($unit) {
                        $unit->stock += $item->quantity;
                        $unit->save();
                    }
                } elseif ($item->rentable_type === Game::class) {
                    Game::where('id', $item->rentable_id)->lockForUpdate()->increment('stok', $item->quantity);
                } elseif ($item->rentable_type === Accessory::class) {
                    Accessory::where('id', $item->rentable_id)->lockForUpdate()->increment('stok', $item->quantity);
                }
            }

            $rental->returned_at = now();
            $rental->status = 'selesai';
            $rental->save();
        });

        return redirect()->route('kasir.rentals.show', $rental)->with('status', 'Rental dikembalikan');
    }

    /**
     * Kasir mengkonfirmasi pengembalian dari user
     */
    public function confirmReturn(Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        // Hanya bisa konfirmasi jika status menunggu_konfirmasi
        if ($rental->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Penyewaan ini tidak dalam status menunggu konfirmasi.');
        }

        DB::transaction(function () use ($rental) {
            // Restore stock untuk semua item
            $rental->load('items');
            foreach ($rental->items as $item) {
                if ($item->rentable) {
                    // Check if it's UnitPS (uses 'stock') or other models (use 'stok')
                    $isUnitPS = $item->rentable instanceof \App\Models\UnitPS;
                    
                    if ($isUnitPS) {
                        $item->rentable->stock += $item->quantity;
                    } else {
                        $item->rentable->stok += $item->quantity;
                    }
                    
                    $item->rentable->save();
                    
                    \Log::info('Stock restored by cashier confirmation', [
                        'item_type' => get_class($item->rentable),
                        'item_id' => $item->rentable->id,
                        'quantity_restored' => $item->quantity,
                    ]);
                }
            }

            // Update rental status menjadi selesai
            $rental->update([
                'status' => 'selesai',
                'handled_by' => auth()->id(),
            ]);
        });

        return redirect()->route('kasir.rentals.show', $rental)
            ->with('status', 'Pengembalian berhasil dikonfirmasi. Stok telah dikembalikan.');
    }
}
