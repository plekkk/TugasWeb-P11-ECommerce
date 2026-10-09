<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f6fb; color: #172033; }
        header { background: #142b52; color: white; padding: 22px 6%; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
        header a { color: white; text-decoration: none; margin-left: 18px; }
        header nav { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        main { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
        .intro { margin-bottom: 24px; }
        .intro h1 { margin-bottom: 8px; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
        input, select, .search-button { padding: 12px; border: 1px solid #ccd5e3; border-radius: 8px; font-size: 14px; }
        .filters input { flex: 1; min-width: 180px; }
        .filters select { min-width: 160px; background: white; }
        .search-button { background: #2057a5; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 3px 14px #15294a12; }
        .product-image { width: 100%; height: 160px; object-fit: contain; display: block; background: #e7eef9; border-radius: 8px; margin-bottom: 14px; }
        .image-placeholder { height: 160px; display: grid; place-items: center; background: #e7eef9; border-radius: 8px; font-size: 48px; margin-bottom: 14px; }
        .category { color: #5c6d86; font-size: 13px; }
        .price { font-size: 19px; font-weight: bold; color: #174c91; }
        .stock { font-size: 13px; color: #526176; }
        .empty { background: white; padding: 24px; border-radius: 10px; }
        .pagination { margin: 28px 0; }
        .pagination nav { display: flex; justify-content: space-between; gap: 12px; }
        footer { text-align: center; padding: 24px; color: #64748b; }
        @media (max-width: 600px) {
            header { padding: 18px; }
            header a { margin-left: 0; margin-right: 8px; }
            .filters { flex-direction: column; }
            .filters input, .filters select, .filters button { width: 100%; }
        }
    </style>
</head>
<body>
<header>
    <strong>TOKO KITA</strong>
    <nav>
        <a href="{{ url('/') }}">Beranda</a>
        <a href="{{ route('products.index') }}">Produk</a>
        @auth
            <a href="{{ route('cart.index') }}">Keranjang Belanja</a>
            <a href="{{ route('orders.history') }}">Riwayat Pesanan</a>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="border:0;background:transparent;color:inherit;cursor:pointer;font:inherit;padding:8px;">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>
</header>

<main>
    <section class="intro">
        <h1>Katalog Produk</h1>
        <p>Temukan produk yang sesuai dengan kebutuhanmu.</p>
    </section>

    <form class="filters" method="GET" action="{{ route('products.index') }}">
        <input
            type="search"
            name="search"
            placeholder="Cari nama produk..."
            value="{{ request('search') }}"
        >

        <select name="category">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="sort">
            <option value="latest" @selected(($sort ?? 'latest') === 'latest')>Produk terbaru</option>
            <option value="price_low" @selected(($sort ?? '') === 'price_low')>Harga termurah</option>
            <option value="price_high" @selected(($sort ?? '') === 'price_high')>Harga termahal</option>
        </select>

        <button class="search-button" type="submit">Terapkan</button>
        <a class="search-button" href="{{ route('products.index') }}">Reset</a>
    </form>

    @if ($products->count())
        <section class="grid">
            @foreach ($products as $product)
                <article class="card">
                    @if ($product->image)
                        <img class="product-image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="image-placeholder" aria-label="Tidak ada gambar produk">📦</div>
                    @endif

                    <div class="category">{{ $product->category?->name ?? 'Tanpa kategori' }}</div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <div class="price">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</div>
                    <p class="stock">
                        @if ($product->stock > 0)
                            Stok: {{ $product->stock }}
                        @else
                            Stok habis
                        @endif
                    </p>

                    @if ($product->stock > 0)
                        @auth
                            <form action="{{ route('cart.store', $product) }}" method="POST" style="margin-top: 14px;">
                                @csrf
                                <label for="quantity-{{ $product->id }}">Jumlah:</label>
                                <input id="quantity-{{ $product->id }}" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required style="width: 75px; padding: 8px; margin: 6px 0;">
                                <button type="submit" class="search-button">Tambah ke Keranjang</button>
                            </form>
                        @else
                            <a class="search-button" href="{{ route('login') }}">Login untuk Membeli</a>
                        @endauth
                    @else
                        <p>Stok habis</p>
                    @endif
                </article>
            @endforeach
        </section>

        <div class="pagination">{{ $products->links() }}</div>
    @else
        <div class="empty">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</div>
    @endif
</main>

<footer>&copy; {{ date('Y') }} Toko Kita · Laravel E-Commerce</footer>
</body>
</html>