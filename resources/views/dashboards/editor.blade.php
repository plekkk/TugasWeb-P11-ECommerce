<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Editor</title>
</head>
<body>
    <h1>Dashboard Editor</h1>
    <p>Selamat datang, {{ auth()->user()->name }}.</p>
    <p>Anda dapat mengelola dan memperbarui informasi produk.</p>
    <a href="{{ route('dashboard') }}">Kembali ke Dashboard Utama</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
