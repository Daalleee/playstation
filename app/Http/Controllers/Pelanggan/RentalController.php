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
                // Safely validate and process session cart
                if (is_array($sessionCart)) {
                    $validCartItems = [];
                    foreach ($sessionCart as $key => $item) {
                        // Ensure $item is an array and has required structure
                        if (is_array($item) && 
                            isset($item['type']) && 
                            isset($item['item_id']) && 
                            is_string($item['type']) && 
                            is_numeric($item['item_id'])) {
                            // Add the valid item to collection
                            $validCartItems[] = $item;
                        }
                    }
                    $cartItems = collect($validCartItems);
                } else {
                    $cartItems = collect([]);
                }
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
        
        // Get cart items for this rental - ensure we have items to process
        $cartItems = Cart::where('user_id', auth()->id())->get();
        
        // If DB cart is empty, check session cart - with added safety check
        if ($cartItems->isEmpty()) {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                // Safely validate and process session cart
                if (is_array($sessionCart)) {
                    $validCartItems = [];
                    foreach ($sessionCart as $key => $item) {
                        // Ensure $item is an array and has required structure
                        if (is_array($item) && 
                            isset($item['type']) && 
                            isset($item['item_id']) && 
                            is_string($item['type']) && 
                            is_numeric($item['item_id'])) {
                            // Add the valid item to collection
                            $validCartItems[] = $item;
                        } else {
                            \Log::warning('Invalid cart item in session', [
                                'key' => $key,
                                'item' => $item,
                                'user_id' => auth()->id()
                            ]);
                        }
                    }
                    $cartItems = collect($validCartItems);
                } else {
                    $cartItems = collect([]);
                }
            } else {
                return redirect()->route('pelanggan.cart.index')->with('error', 'Keranjang kosong.');
            }
        }
        
        // Check if we have any cart items to process
        if ($cartItems->isEmpty()) {
            return redirect()->route('pelanggan.cart.index')->with('error', 'Tidak ada item dalam keranjang untuk diproses.');
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

            // Create rental items with extra safety
            foreach ($cartItems as $index => $item) {
                try {
                    // Validate that the item exists and has required fields
                    if (is_object($item)) {
                        // It's a Cart model object
                        $itemType = $item->type ?? null;
                        $itemId = $item->item_id ?? null;
                        $itemQuantity = $item->quantity ?? 1;
                        $itemName = $item->name ?? 'Unknown';
                        $itemPrice = $item->price ?? 0;
                        $itemPriceType = $item->price_type ?? 'per_hari';
                    } else if (is_array($item)) {
                        // It's an array from session
                        $itemType = $item['type'] ?? null;
                        $itemId = $item['item_id'] ?? null;
                        $itemQuantity = $item['quantity'] ?? 1;
                        $itemName = $item['name'] ?? 'Unknown';
                        $itemPrice = $item['price'] ?? 0;
                        $itemPriceType = $item['price_type'] ?? 'per_hari';
                    } else {
                        throw new \Exception("Format item tidak dikenal: " . gettype($item) . " pada indeks: " . $index);
                    }
                    
                    if (!$itemType || !$itemId) {
                        throw new \Exception("Data item tidak lengkap: item type atau ID tidak ditemukan. Type: {$itemType}, ID: {$itemId}, Indeks: {$index}");
                    }
                    
                    $model = match($itemType) {
                        'unitps' => UnitPS::class,
                        'game' => Game::class,
                        'accessory' => Accessory::class,
                    };

                    if (!$model) {
                        throw new \Exception("Tipe item tidak valid: {$itemType} pada indeks: {$index}");
                    }

                    $rentable = $model::lockForUpdate()->find($itemId);
                    
                    if (!$rentable) {
                        throw new \Exception("Item {$itemName} (ID: {$itemId}) tidak ditemukan pada indeks: {$index}");
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
                } catch (\Exception $e) {
                    \Log::error('Error processing cart item: ' . $e->getMessage(), [
                        'index' => $index,
                        'item' => $item,
                        'user_id' => auth()->id(),
                        'rental_id' => $rental->id ?? 'not_created_yet'
                    ]);
                    throw $e;
                }
            }

            // Update rental total
            $rental->update(['total' => $totalAmount]);

            // Clear DB cart only
            Cart::where('user_id', auth()->id())->delete();
            
            // Clear session cart
            session()->forget('cart');

            // Midtrans: build params - gunakan total per item agar sesuai durasi
            // Load items separately to avoid potential relationship loading issues
            $rental->load('items');
            
            $items = [];
            foreach ($rental->items as $ri) {
                try {
                    // Manually load the rentable relationship to avoid potential issues
                    $rentable = null;
                    
                    // Validate rentable_type and rentable_id before attempting to load
                    $modelClass = match($ri->rentable_type) {
                        'App\Models\UnitPS', 'unitps' => UnitPS::class,
                        'App\Models\Game', 'game' => Game::class,
                        'App\Models\Accessory', 'accessory' => Accessory::class,
                        default => null,
                    };
                    
                    if ($modelClass && $ri->rentable_id) {
                        $rentable = $modelClass::find($ri->rentable_id);
                    }
                    
                    $baseName = $rentable ? strtolower(class_basename($ri->rentable_type)) : 'item';
                    $displayName = $rentable ? 
                        ($rentable->nama ?? $rentable->judul ?? $rentable->name ?? ucfirst($baseName)) : 
                        'Item Tidak Ditemukan';
                    
                    $items[] = [
                        'id' => $baseName.'-'.$ri->rentable_id,
                        'price' => (int) $ri->total, // total item sudah termasuk qty x durasi
                        'quantity' => 1,
                        'name' => $displayName,
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing rental item for Midtrans: ' . $e->getMessage(), [
                        'rental_id' => $rental->id,
                        'rental_item_id' => $ri->id ?? 'unknown',
                        'rentable_type' => $ri->rentable_type ?? 'unknown',
                        'rentable_id' => $ri->rentable_id ?? 'unknown'
                    ]);
                    throw $e; // Re-throw to handle properly
                }
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
                
                try {
                    $snapToken = $midtrans->createSnapToken($params);
                } catch (\Exception $e) {
                    \Log::error('Error creating Midtrans snap token: ' . $e->getMessage(), [
                        'rental_id' => $rental->id,
                        'total_amount' => $totalAmount,
                        'items_count' => count($items)
                    ]);
                    throw $e;
                }

                DB::commit();
                return view('pelanggan.payment.midtrans', compact('rental', 'snapToken', 'orderId'));
            }

            DB::commit();
            return redirect()->route('pelanggan.rentals.show', $rental)->with('status', 'Penyewaan dibuat (mode lokal, pembayaran dilewati).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-pelanggan');
        
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }
        
        $rental->load(['items', 'payments']);
        
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
        
        return view('pelanggan.rentals.show', compact('rental'));
    }
}