<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil | Toko Kita</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f6fb; padding: 24px; color: #172033; }
        main { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; }
        .success { color: #15803d; }
        a { display: inline-block; background: #2057a5; color: white; text-decoration: none; padding: 12px 16px; border-radius: 8px; }
    </style>
</head>
<body>
<main>
    <h1 class="success">Pesanan Berhasil Dibuat!</h1>
    <p>Nomor pesanan: <strong>{{ $order->order_number }}</strong></p>
    <p>Status: {{ ucfirst($order->status) }}</p>
    <p>Metode pembayaran: {{ $order->payment_method === 'cod' ? 'COD' : 'Transfer Bank' }}</p>
    <p>Total: <strong>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</strong></p>
    <p>Alamat pengiriman: {{ $order->shipping_address }}</p>

    <h2>Rincian Barang</h2>
    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product->name }} × {{ $item->quantity }}
                (Rp{{ number_format((float) $item->price, 0, ',', '.') }} per item)
            </li>
        @endforeach
    </ul>

    <a href="{{ route('products.index') }}">Lanjut Belanja</a>
</main>
</body>
</html>
