<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pesanan | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 22px 5%; }
        header h1 { margin: 0 0 14px; }
        header a { color: white; text-decoration: none; margin-right: 18px; }
        main { max-width: 1250px; margin: 30px auto; padding: 0 18px; }
        .notice { padding: 12px; border-radius: 8px; margin-bottom: 16px; background: #dcfce7; color: #166534; }
        .table-wrap { overflow-x: auto; background: white; padding: 16px; border-radius: 12px; }
        table { width: 100%; min-width: 950px; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        th { background: #f8fafc; }
        select, button { padding: 9px; border-radius: 6px; }
        select { border: 1px solid #cbd5e1; }
        button { border: 0; background: #2563eb; color: white; cursor: pointer; margin-top: 7px; }
        .empty { padding: 25px; text-align: center; color: #64748b; }
        .pagination { margin-top: 18px; }
    </style>
</head>
<body>
<header>
    <h1>Panel Admin</h1>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
        <a href="{{ route('admin.orders.index') }}">Kelola Pesanan</a>
        <a href="{{ route('products.index') }}">Katalog Produk</a>
    </nav>
</header>

<main>
    <h2>Daftar Pesanan Pelanggan</h2>
    <p>Lihat pesanan, informasi pelanggan, produk, dan perbarui status pesanan.</p>

    @if (session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="notice" style="background:#fee2e2;color:#991b1b;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="table-wrap">
        @if ($orders->isEmpty())
            <div class="empty">Belum ada pesanan pelanggan.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong><br>
                                <small>{{ $order->ordered_at?->format('d-m-Y H:i') }}</small>
                            </td>
                            <td>
                                {{ $order->user?->name ?? 'Pengguna tidak tersedia' }}<br>
                                <small>{{ $order->user?->email }}</small>
                            </td>
                            <td>
                                @foreach ($order->items as $item)
                                    {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                    × {{ $item->quantity }}<br>
                                @endforeach
                            </td>
                            <td>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($order->payment_method ?? '-') }}</td>
                            <td>{{ $order->shipping_address }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" required>
                                        @foreach ([
                                            'pending' => 'Pending',
                                            'diproses' => 'Diproses',
                                            'dikirim' => 'Dikirim',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Dibatalkan'
                                        ] as $value => $label)
                                            <option value="{{ $value }}" @selected($order->status === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Simpan Status</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="pagination">{{ $orders->links() }}</div>
    </div>
</main>
</body>
</html>