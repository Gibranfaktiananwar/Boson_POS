<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem ;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    
    public function index()
    {
        $cart = Auth::user()->cart;
        $items = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return view('admintoko.cashier.index', compact('items', 'total'));
    }


    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate(['quantity' => 'required|integer|min:1']);
        $quantity = $request->quantity;

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);

        $cartItem = $cart->items()->where('product_id', $id)->first();
        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $newQty = $existingQty + $quantity;

        if ($newQty > $product->stock) {
            return back()->with('error', "Maaf, jumlah ({$newQty}) melebihi stok ({$product->stock}).");
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }


    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart) return back()->with('error', 'Keranjang tidak ditemukan.');

        $item = $cart->items()->where('product_id', $id)->first();
        if (!$item) return back()->with('error', 'Produk tidak ada di keranjang.');

        $product = Product::findOrFail($id);

        if ($request->quantity > $product->stock) {
            return back()->with('error', "Maaf, jumlah melebihi stok.");
        }

        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Quantity keranjang diperbarui.');
    }


    public function remove($id)
    {
        $cart = Auth::user()->cart;
        $cart?->items()->where('product_id', $id)->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }


    public function checkout()
    {
        $cart = Auth::user()->cart;
        $cart?->items()->delete();

        return redirect()->route('products.index')->with('success', 'Checkout berhasil!');
    }
}
