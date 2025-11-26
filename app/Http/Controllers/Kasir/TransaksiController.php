<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-kasir');
        $rentals = Rental::with('customer')->orderByDesc('start_at')->paginate(10); // tampilkan semua
        $rental = null;
        if ($request->filled('rental_kode')) {
            $kode = $request->rental_kode;
            $rental = Rental::where('kode', $kode)->with('customer','items')->first();
            
            if ($rental) {
                // Load rentable items manually to prevent issues with missing rentables
                foreach ($rental->items as $item) {
                    $modelClass = match($item->rentable_type) {
                        'App\Models\UnitPS', 'unitps' => UnitPS::class,
                        'App\Models\Game', 'game' => Game::class,
                        'App\Models\Accessory', 'accessory' => Accessory::class,
                        default => null,
                    };
                    
                    if ($modelClass && $item->rentable_id) {
                        $item->setRelation('rentable', $modelClass::find($item->rentable_id));
                    }
                }
            }
            if (!$rental) {
                return view('kasir.transaksi.index', compact('rentals'))
                    ->with('status', 'Kode transaksi tidak ditemukan.');
            }
        }
        return view('kasir.transaksi.index', compact('rental', 'rentals'));
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-kasir');
        $rental->load('items');
        
        // Load rentable items manually to prevent issues with missing rentables
        foreach ($rental->items as $item) {
            $modelClass = match($item->rentable_type) {
                'App\Models\UnitPS', 'unitps' => UnitPS::class,
                'App\Models\Game', 'game' => Game::class,
                'App\Models\Accessory', 'accessory' => Accessory::class,
                default => null,
            };
            
            if ($modelClass && $item->rentable_id) {
                $item->setRelation('rentable', $modelClass::find($item->rentable_id));
            }
        }
        return view('kasir.transaksi.show', compact('rental'));
    }

    public function aktifkan(Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        // Validasi status transition yang valid
        if (!in_array($rental->status, ['paid', 'pending'])) {
            return back()->withErrors(['error' => 'Transaksi hanya bisa diaktifkan dari status paid/pending. Status saat ini: ' . $rental->status]);
        }
        
        // Cek apakah sudah pernah aktif/returned
        if (in_array($rental->status, ['sedang_disewa', 'selesai', 'cancelled'])) {
            return back()->withErrors(['error' => 'Transaksi sudah pernah diaktifkan atau sudah selesai.']);
        }
        
        $rental->status = 'sedang_disewa';
        $rental->save();
        return back()->with('status', 'Transaksi sewa sudah diaktifkan, barang sudah diberikan ke pelanggan.');
    }

    public function pengembalian(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'kondisi' => ['required', 'array'],
            'kondisi.*' => ['required', 'string', 'max:255'],
        ]);
        
        // Validasi bahwa item yang diberikan adalah rental_item yang valid
        foreach (array_keys($validated['items']) as $itemId) {
            if (!\App\Models\RentalItem::where('id', $itemId)->exists()) {
                return back()->withErrors(['error' => "Item dengan ID {$itemId} tidak ditemukan."]);
            }
        }
        
        // Muat ulang rental dengan items untuk memastikan data terbaru
        $rental->load('items');
        
        \DB::transaction(function() use ($rental, $validated) {
            foreach ($rental->items as $item) {
                $itemId = $item->id;
                
                // Cek apakah item ini dicentang untuk dikembalikan
                if (isset($validated['items'][$itemId]) && $validated['items'][$itemId] == 1) {
                    // Update stok barang dengan pessimistic locking
                    $rentable = $item->rentable()->lockForUpdate()->first();
                    if ($rentable) {
                        // Check if it's UnitPS (uses 'stock') or other models (use 'stok')
                        $isUnitPS = $rentable instanceof \App\Models\UnitPS;
                        
                        if ($isUnitPS) {
                            $rentable->stock += $item->quantity;
                        } else {
                            $rentable->stok += $item->quantity;
                        }
                        $rentable->save();
                    }
                    
                    // Update kondisi jika perlu
                    if (isset($validated['kondisi'][$itemId])) {
                        $item->kondisi_kembali = $validated['kondisi'][$itemId];
                        $item->save();
                    }
                }
            }
            
            $rental->status = 'selesai';
            $rental->returned_at = now();
            $rental->save();
        });
        
        return redirect()->route('kasir.transaksi.index')->with('status', 'Pengembalian berhasil dikonfirmasi.');
    }
}
