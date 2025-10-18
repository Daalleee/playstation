<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Services\MidtransService;

class RentalController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        
        $rentals = Rental::where('user_id', auth()->id())
            ->with(['items.rentable'])
            ->latest()
            ->paginate(10);
            
        return view('pelanggan.rentals.index', compact('rentals'));
    }

    public function create()
    {
        Gate::authorize('access-pelanggan');
        
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('pelanggan.cart.index')->with('error', 'Keranjang kosong. Silakan tambahkan item terlebih dahulu.');
        }
        
        return view('pelanggan.rentals.create', ['cartItems' => $cartItems]);
    }

    public function store(Request $request, MidtransService $midtrans)
    {
        Gate::authorize('access-pelanggan');
        
        $validated = $request->validate([
            'rental_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:rental_date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('pelanggan.cart.index')->with('error', 'Keranjang kosong.');
        }

        try {
            DB::beginTransaction();

            // Create rental
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'start_at' => $validated['rental_date'],
                'due_at' => $validated['return_date'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'kode' => Rental::generateKodeUnik(),
            ]);

            $totalAmount = 0;

            // Create rental items
            foreach ($cartItems as $item) {
                $model = match($item->type) {
                    'unitps' => UnitPS::class,
                    'game' => Game::class,
                    'accessory' => Accessory::class,
                };

                $rentable = $model::find($item->item_id);
                
                if (!$rentable || ($rentable->stok ?? 0) < $item->quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk {$item->name}");
                }

                // Calculate duration (simplified)
                $rentalDate = \Carbon\Carbon::parse($validated['rental_date']);
                $returnDate = \Carbon\Carbon::parse($validated['return_date']);
                $duration = ($item->price_type === 'per_jam')
                    ? max(1, $rentalDate->diffInHours($returnDate))
                    : max(1, $rentalDate->diffInDays($returnDate));

                $subtotal = $item->price * $item->quantity * $duration;

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'rentable_type' => $model,
                    'rentable_id' => $item->item_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $subtotal,
                ]);

                // Update stock
                $rentable->decrement('stok', $item->quantity);

                $totalAmount += $subtotal;
            }

            // Update rental total
            $rental->update(['total' => $totalAmount]);

            // Clear DB cart
            Cart::where('user_id', auth()->id())->delete();

            // Midtrans: build params - gunakan total per item agar sesuai durasi
            $items = [];
            $rental->load('items.rentable');
            foreach ($rental->items as $ri) {
                $baseName = strtolower(class_basename($ri->rentable_type));
                $displayName = $ri->rentable->nama ?? $ri->rentable->judul ?? $ri->rentable->name ?? ucfirst($baseName);
                $items[] = [
                    'id' => $baseName.'-'.$ri->rentable_id,
                    'price' => (int) $ri->total, // total item sudah termasuk qty x durasi
                    'quantity' => 1,
                    'name' => $displayName,
                ];
            }
            $orderId = 'ORD-'.date('Ymd').'-'.$rental->id.'-'.substr(uniqid(), -5);

            // Fallback: jika local/dev atau key Midtrans tidak terpasang, lewati Snap dan langsung ke halaman detail
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');
            $useSnap = app()->environment('production') && !empty($serverKey) && !empty($clientKey);

            if ($useSnap) {
                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $totalAmount,
                    ],
                    'item_details' => $items,
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'phone' => auth()->user()->phone ?? '',
                        'billing_address' => [
                            'first_name' => auth()->user()->name,
                            'phone' => auth()->user()->phone ?? '',
                        ],
                        'shipping_address' => [
                            'first_name' => auth()->user()->name,
                            'phone' => auth()->user()->phone ?? '',
                        ],
                    ],
                ];
                $snapToken = $midtrans->createSnapToken($params);

                DB::commit();
                return view('pelanggan.payment.midtrans', compact('rental', 'snapToken', 'orderId'));
            }

            DB::commit();
            return redirect()->route('pelanggan.rentals.show', $rental)->with('status', 'Penyewaan dibuat (mode lokal, pembayaran dilewati).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-pelanggan');
        
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }
        
        $rental->load(['items.rentable', 'payments']);
        
        return view('pelanggan.rentals.show', compact('rental'));
    }
}
