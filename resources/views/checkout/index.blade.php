<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f6fb; color: #172033; margin: 0; padding: 24px; }
        main { max-width: 850px; margin: auto; }
        .panel { background: white; padding: 24px; border-radius: 12px; margin: 20px 0; }
        label { display: block; font-weight: bold; margin: 16px 0 8px; }
        textarea, select { width: 100%; padding: 12px; border: 1px solid #ccd5e3; border-radius: 8px; font: inherit; }
        button, a { display: inline-block; padding: 12px 16px; border: 0; border-radius: 8px; background: #2057a5; color: white; text-decoration: none; cursor: pointer; }
        li { margin: 12px 0; }
        .total { font-size: 22px; font-weight: bold; }
        .error { color: #b42318; }
    </style>
</head>
<body>
<main>
    <h1>Checkout</h1>
    <a href="{{ route('cart.index') }}">Kembali ke Keranjang</a>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <section class="panel">
        <h2>Ringkasan Pesanan</h2>
        <ul>
            @foreach ($cart->items as $item)
                <li>
                    {{ $item->product->name }} × {{ $item->quantity }}
                    = Rp{{ number_format((float) ($item->product->price * $item->quantity), 0, ',', '.') }}
                </li>
            @endforeach
        </ul>

        <p class="total">Total: Rp{{ number_format((float) $total, 0, ',', '.') }}</p>
    </section>

    <form class="panel" method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <h2>Informasi Pengiriman</h2>

        <label for="shipping_address">Alamat Lengkap</label>
        <textarea id="shipping_address" name="shipping_address" rows="4" required>{{ old('shipping_address') }}</textarea>

        <label for="payment_method">Metode Pembayaran</label>
        <select id="payment_method" name="payment_method" required>
            <option value="">Pilih metode pembayaran</option>
            <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer Bank</option>
            <option value="cod" @selected(old('payment_method') === 'cod')>Bayar di Tempat (COD)</option>
        </select>

        <p style="color:#64748b">Pilihan pembayaran dicatat pada pesanan. Pembayaran online otomatis belum diintegrasikan.</p>

        <button type="submit">Buat Pesanan</button>
    </form>
</main>
</body>
</html>
