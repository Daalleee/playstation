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

    public function create(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        // Check if specific item is requested via query parameters
        $itemType = $request->query('type');
        $itemId = $request->query('id');
        
        if ($itemType && $itemId) {
            // Get the specific item directly
            $model = match($itemType) {
                'unitps' => UnitPS::class,
                'game' => Game::class,
                'accessory' => Accessory::class,
                default => null,
            };
            
            if ($model) {
                $item = $model::find($itemId);
                if ($item) {
                    // Create a temporary cart-like item for the view
                    $cartItems = collect([[
                        'name' => $item->nama ?? $item->judul ?? $item->name,
                        'type' => $itemType,
                        'price' => $itemType === 'unitps' ? $item->harga_per_jam : $item->harga_per_hari,
                        'price_type' => $itemType === 'unitps' ? 'per_jam' : 'per_hari',
                        'quantity' => 1,
                        'id' => $itemId,
                    ]]);
                    
                    return view('pelanggan.rentals.create', ['cartItems' => $cartItems, 'directItem' => true]);
                }
            }
        }
        
        // Fallback to cart items if no specific item requested or item not found
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            // Check session cart
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                // Convert session cart to collection format
                $cartItems = collect($sessionCart);
            } else {
                return redirect()->route('pelanggan.cart.index')->with('error', 'Keranjang kosong. Silakan tambahkan item terlebih dahulu.');
            }
        }
        
        return view('pelanggan.rentals.create', ['cartItems' => $cartItems, 'directItem' => false]);
    }

    public function store(Request $request, MidtransService $midtrans)
    {
        Gate::authorize('access-pelanggan');
        
        $validated = $request->validate([
            'rental_date' => ['required', 'date', 'after_or_equal:today', 'before:+1 year'],
            'return_date' => ['required', 'date', 'after:rental_date', 'before:+1 year'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        // Additional business logic validation
        $rentalDate = \Carbon\Carbon::parse($validated['rental_date']);
        $returnDate = \Carbon\Carbon::parse($validated['return_date']);
        $daysDiff = $rentalDate->diffInDays($returnDate);
        
        if ($daysDiff > 30) {
            return back()->withErrors(['return_date' => 'Maksimal durasi sewa adalah 30 hari.'])->withInput();
        }
        
        if ($daysDiff < 1) {
            return back()->withErrors(['return_date' => 'Durasi sewa minimal 1 hari.'])->withInput();
        }

        // Check if we need to create a temporary cart item (from direct item selection)
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            // Check if this is a direct item rental from query parameters
            $itemType = $request->query('type');
            $itemId = $request->query('id');
            
            if ($itemType && $itemId) {
                // Validate that this is a legitimate direct request
                $model = match($itemType) {
                    'unitps' => UnitPS::class,
                    'game' => Game::class,
                    'accessory' => Accessory::class,
                };
                
                if ($model) {
                    $item = $model::find($itemId);
                    if ($item && $item->stok > 0) {
                        // Get quantity from request or default to 1
                        $quantity = $request->input('quantity', 1);
                        
                        // Create temporary cart entry for this specific item
                        Cart::create([
                            'user_id' => auth()->id(),
                            'type' => $itemType,
                            'item_id' => $itemId,
                            'quantity' => $quantity,
                            'price' => $itemType === 'unitps' ? $item->harga_per_jam : $item->harga_per_hari,
                            'name' => $item->nama ?? $item->judul ?? $item->name,
                            'price_type' => $itemType === 'unitps' ? 'per_jam' : 'per_hari',
                        ]);
                        
                        // Get updated cart items
                        $cartItems = Cart::where('user_id', auth()->id())->get();
                    }
                }
            }
        }
        
        // Get cart items for this rental
        $cartItems = Cart::where('user_id', auth()->id())->get();
        
        // If DB cart is empty, check session cart
        if ($cartItems->isEmpty()) {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                // Convert session cart to collection format
                $cartItems = collect($sessionCart);
            } else {
                // If DB cart is empty, check session cart
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                // Convert session cart to collection format
                $cartItems = collect($sessionCart);
            } else {
                return redirect()->route('pelanggan.cart.index')->with('error', 'Keranjang kosong.');
            }
            }
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
                // Handle both object and array formats
                $itemType = is_object($item) ? $item->type : $item['type'];
                $itemId = is_object($item) ? $item->item_id : $item['item_id'];
                $itemQuantity = is_object($item) ? $item->quantity : $item['quantity'];
                $itemName = is_object($item) ? $item->name : $item['name'];
                
                $model = match($itemType) {
                    'unitps' => UnitPS::class,
                    'game' => Game::class,
                    'accessory' => Accessory::class,
                };

                $rentable = $model::lockForUpdate()->find($itemId);
                
                if (!$rentable) {
                    throw new \Exception("Item {$itemName} tidak ditemukan");
                }
                
                if (($rentable->stok ?? 0) < $itemQuantity) {
                    throw new \App\Exceptions\InsufficientStockException(
                        $itemName, 
                        $itemQuantity, 
                        $rentable->stok ?? 0
                    );
                }

                // Calculate duration (simplified)
                $rentalDate = \Carbon\Carbon::parse($validated['rental_date']);
                $returnDate = \Carbon\Carbon::parse($validated['return_date']);
                $itemPriceType = is_object($item) ? $item->price_type : $item['price_type'];
                $itemPrice = is_object($item) ? $item->price : $item['price'];
                
                $duration = ($itemPriceType === 'per_jam')
                    ? max(1, $rentalDate->diffInHours($returnDate))
                    : max(1, $rentalDate->diffInDays($returnDate));

                $subtotal = $itemPrice * $itemQuantity * $duration;

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'rentable_type' => $model,
                    'rentable_id' => $itemId,
                    'quantity' => $itemQuantity,
                    'price' => $itemPrice,
                    'total' => $subtotal,
                ]);

                // Update stock with pessimistic locking to prevent race condition
                $rentable->stok -= $itemQuantity;
                $rentable->save();

                $totalAmount += $subtotal;
            }

            // Update rental total
            $rental->update(['total' => $totalAmount]);

            // Clear DB cart only
            Cart::where('user_id', auth()->id())->delete();
            
            // Clear session cart
            session()->forget('cart');

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

            // Use Midtrans if server key is available
            $serverKey = config('midtrans.server_key');
            $useSnap = !empty($serverKey);

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
