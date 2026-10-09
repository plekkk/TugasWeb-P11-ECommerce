<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Transaksi - Admin</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #172033;
        }
        header {
            background: #102544;
            color: white;
            padding: 22px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        header h1 { margin: 0; font-size: 23px; }
        header a { color: white; text-decoration: none; }
        main { max-width: 1200px; margin: 28px auto; padding: 0 20px; }
        .intro p { color: #64748b; }
        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin: 22px 0;
        }
        .card, .panel {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 3px 14px #0f172a0a;
        }
        .card span { color: #64748b; font-size: 13px; }
        .card strong { display: block; font-size: 23px; margin-top: 12px; overflow-wrap: anywhere; }
        .panel { margin-bottom: 22px; }
        .filters {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            align-items: end;
        }
        label { display: block; font-size: 13px; margin-bottom: 7px; }
        input, select, button, .reset {
            width: 100%;
            min-height: 42px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            font: inherit;
        }
        button {
            background: #1d4ed8;
            color: white;
            border: 0;
            cursor: pointer;
            font-weight: bold;
        }
        .reset {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 8px;
            text-decoration: none;
            color: #334155;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 750px; }
        th, td { padding: 13px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-size: 12px; color: #475569; }
        td { font-size: 14px; }
        .status { background: #e2e8f0; padding: 5px 9px; border-radius: 20px; white-space: nowrap; }
        .status.selesai { background: #dcfce7; color: #166534; }
        .status.dibatalkan { background: #fee2e2; color: #991b1b; }
        .status.pending { background: #fef3c7; color: #92400e; }
        .empty { text-align: center; padding: 25px; color: #64748b; }
        .pagination { margin-top: 20px; }
        @media (max-width: 850px) {
            .cards, .filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 480px) {
            main { padding: 0 12px; }
            .cards, .filters { grid-template-columns: 1fr; }
            .card, .panel { padding: 16px; }
        }
    </style>
</head>
<body>
<header>
    <h1>Laporan Transaksi</h1>
    <a href="{{ route('admin.dashboard') }}">&#8592; Dashboard Admin</a>
</header>

<main>
    <section class="intro">
        <h2>Ringkasan Penjualan</h2>
        <p>Pantau jumlah pesanan dan pendapatan toko.</p>
    </section>

    <section class="cards">
        <div class="card">
            <span>Total seluruh pesanan</span>
            <strong>{{ number_format($totalOrders, 0, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Pesanan selesai</span>
            <strong>{{ number_format($completedOrders, 0, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Pesanan menunggu / diproses</span>
            <strong>{{ number_format($pendingOrders, 0, ',', '.') }}</strong>
        </div>
        <div class="card">
            <span>Omzet pesanan selesai</span>
            <strong>Rp {{ number_format((float) $revenue, 0, ',', '.') }}</strong>
        </div>
    </section>

    <section class="panel">
        <h3>Filter Transaksi</h3>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="filters">
            <div>
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">Semua status</option>
                    @foreach (['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $statusOption)
                        <option value="{{ $statusOption }}" @selected(request('status') === $statusOption)>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="start_date">Dari tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date">Sampai tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
            </div>
            <div>
                <button type="submit">Terapkan Filter</button>
                <a class="reset" href="{{ route('admin.reports.index') }}">Reset Filter</a>
                <a class="reset" href="{{ route('admin.reports.export', request()->only(['status', 'start_date', 'end_date'])) }}"
                   style="background:#15803d;color:white;border-color:#15803d;margin-top:8px;font-weight:bold;">
                    Unduh CSV
                </a>
            </div>
        </form>
    </section>

    <section class="panel">
        <h3>Daftar Transaksi</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pembeli</th>
                        <th>Tanggal</th>
                        <th>Pembayaran</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user?->name ?? 'Pengguna dihapus' }}</td>
                            <td>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ strtoupper($order->payment_method) }}</td>
                            <td>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="status {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Tidak ada transaksi yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $orders->links() }}</div>
    </section>
</main>
</body>
</html>