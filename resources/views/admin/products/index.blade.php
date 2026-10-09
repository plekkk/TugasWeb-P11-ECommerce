<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Produk | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 22px 5%; }
        header h1 { margin: 0 0 14px; }
        header a { color: white; text-decoration: none; margin-right: 18px; }
        main { max-width: 1200px; margin: 30px auto; padding: 0 18px; }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 10px 14px; background: #2563eb; color: white; text-decoration: none; border: 0; border-radius: 7px; cursor: pointer; }
        .btn-edit { background: #d97706; }
        .btn-delete { background: #dc2626; }
        .notice { padding: 12px; margin: 14px 0; border-radius: 8px; background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
        .table-wrap { overflow-x: auto; background: white; padding: 16px; border-radius: 12px; }
        table { width: 100%; min-width: 800px; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: middle; }
        th { background: #f8fafc; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .actions { display: flex; gap: 7px; align-items: center; flex-wrap: wrap; }
        .pagination { margin-top: 18px; }
        @media (max-width: 600px) { header { padding: 18px; } main { margin-top: 18px; } }
    </style>
</head>
<body>
<header>
    <h1>Panel Admin</h1>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.products.index') }}">Kelola Produk</a>
        <a href="{{ route('admin.orders.index') }}">Kelola Pesanan</a>
        <a href="{{ route('products.index') }}">Lihat Toko</a>
    </nav>
</header>

<main>
    <div class="top">
        <div>
            <h2>Kelola Produk</h2>
            <p>Tambah, perbarui, dan kelola stok produk toko.</p>
        </div>
        <a class="btn" href="{{ route('admin.products.create') }}">+ Tambah Produk</a>
    </div>

    @if (session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
    @endif

    <div class="table-wrap">
        @if ($products->isEmpty())
            <p>Belum ada produk.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img class="product-img" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <span>Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-edit" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-delete" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="pagination">{{ $products->links() }}</div>
    </div>
</main>
</body>
</html>