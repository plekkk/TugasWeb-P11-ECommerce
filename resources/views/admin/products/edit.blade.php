<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk | Toko Kita</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 22px 5%; }
        header a { color: white; margin-right: 18px; text-decoration: none; }
        main { max-width: 760px; margin: 30px auto; padding: 25px; background: white; border-radius: 12px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 11px; margin-top: 7px; border: 1px solid #cbd5e1; border-radius: 6px; }
        textarea { min-height: 100px; resize: vertical; }
        .check { width: auto; }
        .btn { margin-top: 20px; padding: 12px 18px; background: #2563eb; color: white; border: 0; border-radius: 7px; cursor: pointer; text-decoration: none; display: inline-block; }
        .cancel { background: #64748b; }
        .errors { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 7px; }
        .current-image { max-width: 150px; display: block; margin-top: 10px; border-radius: 8px; }
    </style>
</head>
<body>
<header>
    <h1>Panel Admin</h1>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}">Kelola Produk</a>
</header>
<main>
    <h2>Edit Produk</h2>
    <p>Perbarui informasi produk di bawah ini.</p>

    @if ($errors->any())
        <div class="errors">
            <strong>Periksa kembali data:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="name">Nama Produk</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required maxlength="255">

        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="description">Deskripsi</label>
        <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>

        <label for="price">Harga (Rp)</label>
        <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>

        <label for="stock">Stok</label>
        <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>

        <label for="image">Ganti Gambar Produk (opsional, maksimal 2 MB)</label>
        <input id="image" type="file" name="image" accept="image/*">
        @if ($product->image)
            <img class="current-image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        @endif

        <label>
            <input class="check" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
            Produk aktif dan tampil di katalog
        </label>

        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn cancel" href="{{ route('admin.products.index') }}">Batal</a>
    </form>
</main>
</body>
</html>