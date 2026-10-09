<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $cart->load('items.product');

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cart', 'total'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        if (!$product->is_active || $product->stock < 1) {
            return back()->with('error', 'Produk sedang tidak tersedia.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $existingQuantity = $item ? (int) $item->quantity : 0;
        $newQuantity = $existingQuantity + (int) $validated['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->with(
                'error',
                'Jumlah melebihi stok yang tersedia: ' . $product->stock
            );
        }

        if ($item) {
            $item->quantity = $newQuantity;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => (int) $validated['quantity'],
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if (!$cart || (int) $cartItem->cart_id !== (int) $cart->id) {
            abort(403, 'Anda tidak memiliki akses ke barang ini.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}