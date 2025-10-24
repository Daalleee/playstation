<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;

class CartController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        $cart = Cart::where('user_id', auth()->id())->get();
        $total = $cart->sum(function($item) {
            return $item->price * $item->quantity;
        });
        return view('pelanggan.cart.index', ['cart' => $cart, 'total' => $total]);
    }

    public function add(Request $request)
    {
        Gate::authorize('access-pelanggan');
        $validated = $request->validate([
            'type' => ['required', 'in:unitps,game,accessory'],
            'id' => ['required', 'integer'],
            'price_type' => ['required', 'in:per_jam,per_hari'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        // Ambil data dari DB sesuai type/id
        $modelClass = match($validated['type']) {
            'unitps' => UnitPS::class,
            'game' => Game::class,
            'accessory' => Accessory::class,
        };
        $product = $modelClass::findOrFail($validated['id']);

        // Tentukan nama & harga berdasarkan jenis harga
        $name = $product->nama ?? $product->judul ?? $product->name;
        $price = 0;
        if ($validated['type'] === 'unitps') {
            $price = (float) ($product->harga_per_jam ?? $product->price_per_hour ?? 0);
        } elseif ($validated['type'] === 'game') {
            $price = (float) ($product->harga_per_hari ?? $product->price_per_day ?? 0);
        } else { // accessory
            $price = (float) ($product->harga_per_hari ?? $product->price_per_day ?? 0);
        }

        $qty = $validated['quantity'] ?? 1;
        if (($product->stok ?? 0) < $qty) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        // Cek apakah sudah ada item yang sama di cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('type', $validated['type'])
            ->where('item_id', $validated['id'])
            ->first();
        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->price_type = $validated['price_type'];
            $cartItem->price = $price; // sinkronkan harga terbaru
            $cartItem->name = $name;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'item_id' => $validated['id'],
                'name' => $name,
                'price' => $price,
                'price_type' => $validated['price_type'],
                'quantity' => $qty,
            ]);
        }
        return redirect()->route('pelanggan.cart.index')->with('status', 'Item ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        Gate::authorize('access-pelanggan');
        $validated = $request->validate([
            'cart_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $cartItem = Cart::where('user_id', auth()->id())->where('id', $validated['cart_id'])->first();
        if ($cartItem) {
            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();
        }
        return redirect()->route('pelanggan.cart.index');
    }

    public function remove(Request $request)
    {
        Gate::authorize('access-pelanggan');
        $validated = $request->validate([
            'cart_id' => ['required', 'integer'],
        ]);
        Cart::where('user_id', auth()->id())->where('id', $validated['cart_id'])->delete();
        return redirect()->route('pelanggan.cart.index')->with('status', 'Item dihapus dari keranjang');
    }

    public function clear()
    {
        Gate::authorize('access-pelanggan');
        Cart::where('user_id', auth()->id())->delete();
        return redirect()->route('pelanggan.cart.index')->with('status', 'Keranjang dikosongkan');
    }
}
