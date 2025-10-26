<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        
        // Get cart items from database
        $cartItems = Cart::where('user_id', auth()->id())->get();
        
        return view('pelanggan.cart.index', compact('cartItems'));
    }
    
    public function add(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        // Validation
        $request->validate([
            'type' => 'required|in:unitps,game,accessory',
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price_type' => 'required|in:per_jam,per_hari'
        ]);
        
        $quantity = $request->quantity;
        
        // Get the model class based on type
        $modelClass = null;
        switch($request->type) {
            case 'unitps':
                $modelClass = 'App\Models\UnitPS';
                break;
            case 'game':
                $modelClass = 'App\Models\Game';
                break;
            case 'accessory':
                $modelClass = 'App\Models\Accessory';
                break;
        }
        
        if(!$modelClass) {
            $message = 'Tipe item tidak valid!';
            if($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Find the item
        $item = $modelClass::find($request->id);
        if(!$item) {
            $message = 'Item tidak ditemukan!';
            if($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }
        
        // Check stock
        if($item->stok < $request->quantity) {
            if($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }
        
        // Check if item already in cart
        $existingCartItem = Cart::where('user_id', auth()->id())
            ->where('type', $request->type)
            ->where('item_id', $request->id)
            ->first();
            
        if($existingCartItem) {
            // Update quantity
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            if($newQuantity > $item->stok) {
                if($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
            $existingCartItem->update(['quantity' => $newQuantity]);
        } else {
            // Add new item to cart
            Cart::create([
                'user_id' => auth()->id(),
                'type' => $request->type,
                'item_id' => $request->id,
                'quantity' => $request->quantity,
                'price' => $request->type === 'unitps' ? $item->harga_per_jam : $item->harga_per_hari,
                'name' => $item->nama ?? $item->judul ?? $item->name,
                'price_type' => $request->price_type,
            ]);
        }
        
        $message = 'Item berhasil ditambahkan ke keranjang!';
        if($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        return redirect()->back()->with('success', $message);
    }
    
    public function update(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        // In a real application, this would update cart item quantity
        $request->validate([
            'type' => 'required|string',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Find the cart item
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->first();
            
        if($cartItem) {
            // Get the actual item to check stock
            $modelClass = null;
            switch($request->type) {
                case 'unitps':
                    $modelClass = 'App\Models\UnitPS';
                    break;
                case 'game':
                    $modelClass = 'App\Models\Game';
                    break;
                case 'accessory':
                    $modelClass = 'App\Models\Accessory';
                    break;
            }
            
            if($modelClass) {
                $item = $modelClass::find($request->item_id);
                if($item && $item->stok >= $request->quantity) {
                    $cartItem->update(['quantity' => $request->quantity]);
                    $message = 'Jumlah item telah diperbarui!';
                    if($request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => $message
                        ]);
                    }
                    return redirect()->back()->with('success', $message);
                } else {
                    $message = 'Stok tidak mencukupi!';
                    if($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], 400);
                    }
                    return redirect()->back()->with('error', $message);
                }
            }
        }
        
        $message = 'Item tidak ditemukan di keranjang!';
        if($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }
        return redirect()->back()->with('error', $message);
    }
    
    public function remove(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        // In a real application, this would remove an item from cart
        // Find and remove the specific item
        $request->validate([
            'type' => 'required|string',
            'item_id' => 'required|integer',
        ]);
        
        Cart::where('user_id', auth()->id())
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->delete();
        
        $message = 'Item telah dihapus dari keranjang!';
        if($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        return redirect()->back()->with('success', $message);
    }
    
    public function clear(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        // Clear the entire cart from database
        Cart::where('user_id', auth()->id())->delete();
        
        $message = 'Keranjang telah dibersihkan!';
        if($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        return redirect()->back()->with('success', $message);
    }
}