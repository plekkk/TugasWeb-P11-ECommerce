<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px 16px;
            background: #f1f5fb;
            color: #172b4d;
            font-family: Arial, sans-serif;
        }
        .container { max-width: 950px; margin: auto; }
        h1 { margin-bottom: 8px; }
        .back {
            display: inline-block;
            margin: 14px 0 24px;
            padding: 11px 16px;
            color: white;
            background: #2459a9;
            text-decoration: none;
            border-radius: 7px;
        }
        .order {
            padding: 22px;
            margin-bottom: 18px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 12px #172b4d10;
        }
        .top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .status {
            align-self: flex-start;
            padding: 7px 12px;
            border-radius: 20px;
            background: #fff0c2;
            color: #805500;
        }
        .item { padding: 10px 0; }
        .total {
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-size: 19px;
            font-weight: bold;
        }
        .empty {
            padding: 28px;
            background: white;
            border-radius: 12px;
        }
        @media (max-width: 600px) {
            body { padding: 20px 12px; }
            .order { padding: 16px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Riwayat Pesanan</h1>
    <p>Lihat transaksi yang pernah kamu buat di Toko Kita.</p>

    <a class="back" href="{{ route('products.index') }}">Kembali ke Produk</a>

    @forelse ($orders as $order)
        <div class="order">
            <div class="top">
                <div>
                    <strong>Nomor Pesanan</strong><br>
                    {{ $order->order_number }}<br>
                    <small>
                        {{ $order->ordered_at
                            ? $order->ordered_at->format('d-m-Y H:i')
                            : $order->created_at->format('d-m-Y H:i') }}
                    </small>
                </div>
                <span class="status">{{ ucfirst($order->status) }}</span>
            </div>

            <h3>Detail Produk</h3>

            @foreach ($order->items as $item)
                <div class="item">
                    {{ $item->product->name ?? 'Produk tidak tersedia' }}
                    × {{ $item->quantity }}<br>
                    <small>
                        Harga satuan:
                        Rp{{ number_format((float) $item->price, 0, ',', '.') }}
                    </small>
                </div>
            @endforeach

            <p><strong>Alamat pengiriman:</strong>
                {{ $order->shipping_address }}
            </p>

            <p><strong>Metode pembayaran:</strong>
                {{ strtoupper($order->payment_method ?? '-') }}
            </p>

            <div class="total">
                Total: Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}
            </div>
        </div>
    @empty
        <div class="empty">
            <h3>Belum Ada Pesanan</h3>
            <p>Pesanan yang berhasil dibuat akan muncul di sini.</p>
            <a href="{{ route('products.index') }}">Mulai Belanja</a>
        </div>
    @endforelse

    {{ $orders->links() }}
</div>
</body>
</html>