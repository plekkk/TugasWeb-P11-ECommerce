<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = $cart->items->sum(
            fn ($item) => $item->quantity * $item->product->price
        );

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:transfer,cod'],
        ]);

        try {
            $order = DB::transaction(function () use ($request, $validated) {
                $cart = Cart::where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->first();

                if (!$cart) {
                    throw new RuntimeException('Keranjang tidak ditemukan.');
                }

                $cartItems = $cart->items()->with('product')->get();

                if ($cartItems->isEmpty()) {
                    throw new RuntimeException('Keranjang masih kosong.');
                }

                $total = 0;
                $itemsToOrder = [];

                foreach ($cartItems as $cartItem) {
                    $product = Product::whereKey($cartItem->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$product ||
                        !$product->is_active ||
                        $product->stock < $cartItem->quantity
                    ) {
                        throw new RuntimeException(
                            'Stok produk "' . ($product?->name ?? 'tidak tersedia') .
                            '" tidak mencukupi. Periksa kembali keranjang.'
                        );
                    }

                    $price = (float) $product->price;
                    $total += $price * $cartItem->quantity;

                    $itemsToOrder[] = [
                        'product' => $product,
                        'quantity' => $cartItem->quantity,
                        'price' => $price,
                    ];
                }

                $order = new Order();
                $order->user_id = $request->user()->id;
                $order->order_number = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));
                $order->total_amount = $total;
                $order->status = 'pending';
                $order->shipping_address = $validated['shipping_address'];
                $order->payment_method = $validated['payment_method'];
                $order->ordered_at = now();
                $order->save();

                foreach ($itemsToOrder as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $item['product']->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->price = $item['price'];
                    $orderItem->save();

                    $item['product']->stock -= $item['quantity'];
                    $item['product']->save();
                }

                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (RuntimeException $exception) {
            return redirect()->route('cart.index')
                ->with('error', $exception->getMessage());
        }
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items.product');

        return view('checkout.success', compact('order'));
    }
}
