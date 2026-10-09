<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; font-family: Arial, sans-serif; background: #f3f6fb; color: #172033; }
        main { max-width: 900px; margin: auto; }
        .panel { background: white; padding: 22px; margin: 16px 0; border-radius: 12px; }
        .item { display: flex; justify-content: space-between; gap: 16px; align-items: center; padding: 16px 0; border-bottom: 1px solid #e5eaf2; }
        .price { font-weight: bold; }
        button, .link { display: inline-block; padding: 10px 14px; border: 0; border-radius: 7px; background: #2057a5; color: white; text-decoration: none; cursor: pointer; }
        .delete { background: #b42318; }
        .message { padding: 12px; border-radius: 8px; margin: 12px 0; background: #e4f7e9; }
        .error { background: #ffe7e7; }
        .total { font-size: 21px; font-weight: bold; }
        @media (max-width: 550px) { .item { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
<main>
    <h1>Keranjang Belanja</h1>
    <p><a class="link" href="{{ route('products.index') }}">Kembali ke Produk</a></p>

    @if (session('success'))
        <div class="message">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <section class="panel">
        @forelse ($cart->items as $item)
            <div class="item">
                <div>
                    <h3>{{ $item->product->name }}</h3>
                    <p>Jumlah: {{ $item->quantity }}</p>
                    <p>Harga satuan: Rp{{ number_format((float) $item->product->price, 0, ',', '.') }}</p>
                    <p class="price">
                        Subtotal: Rp{{ number_format((float) ($item->quantity * $item->product->price), 0, ',', '.') }}
                    </p>
                </div>

                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="delete" type="submit">Hapus</button>
                </form>
            </div>
        @empty
            <p>Keranjang masih kosong. Silakan pilih produk terlebih dahulu.</p>
        @endforelse

        <p class="total">Total: Rp{{ number_format((float) $total, 0, ',', '.') }}</p>
    
        @if ($cart->items->isNotEmpty())
            <div style="margin-top: 24px; text-align: right;">
                <a href="{{ route('checkout.index') }}"
                   style="display: inline-block; background: #198754; color: white; padding: 12px 22px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                    Lanjut ke Checkout
                </a>
            </div>
        @endif
</section>
</main>
</body>
</html>