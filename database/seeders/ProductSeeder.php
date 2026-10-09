<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Laptop Basic', 'elektronik', 6500000, 12],
            ['Laptop Pro', 'elektronik', 12500000, 8],
            ['Mouse Wireless', 'elektronik', 150000, 30],
            ['Keyboard Mechanical', 'elektronik', 450000, 18],
            ['Headset Gaming', 'elektronik', 350000, 20],
            ['Monitor 24 Inch', 'elektronik', 1850000, 10],
            ['Webcam HD', 'elektronik', 275000, 15],
            ['Speaker Bluetooth', 'elektronik', 225000, 22],
            ['Flashdisk 64GB', 'elektronik', 95000, 40],
            ['Power Bank', 'elektronik', 250000, 25],

            ['Kaos Polos', 'pakaian', 75000, 40],
            ['Kemeja Formal', 'pakaian', 175000, 20],
            ['Celana Jeans', 'pakaian', 225000, 18],
            ['Jaket Hoodie', 'pakaian', 200000, 15],
            ['Jaket Denim', 'pakaian', 275000, 12],
            ['Celana Chino', 'pakaian', 185000, 16],
            ['Kemeja Flanel', 'pakaian', 160000, 20],
            ['Sweater Rajut', 'pakaian', 195000, 14],
            ['Topi Baseball', 'pakaian', 65000, 30],
            ['Kaos Olahraga', 'pakaian', 95000, 25],

            ['Jam Tangan', 'aksesori', 350000, 12],
            ['Kacamata Fashion', 'aksesori', 125000, 20],
            ['Dompet Pria', 'aksesori', 85000, 25],
            ['Tas Selempang', 'aksesori', 175000, 18],
            ['Ikat Pinggang', 'aksesori', 65000, 22],
            ['Gelang Stainless', 'aksesori', 45000, 30],
            ['Kalung Minimalis', 'aksesori', 55000, 24],
            ['Anting Fashion', 'aksesori', 35000, 28],
            ['Tas Ransel', 'aksesori', 250000, 15],
            ['Gantungan Kunci', 'aksesori', 15000, 50],

            ['Lampu Meja', 'perlengkapan-rumah', 125000, 20],
            ['Bantal Tidur', 'perlengkapan-rumah', 85000, 25],
            ['Botol Minum', 'perlengkapan-rumah', 45000, 35],
            ['Rak Buku', 'perlengkapan-rumah', 275000, 10],
            ['Tempat Sampah', 'perlengkapan-rumah', 65000, 20],
            ['Kipas Angin Mini', 'perlengkapan-rumah', 95000, 18],
            ['Kotak Penyimpanan', 'perlengkapan-rumah', 55000, 25],
            ['Gantungan Baju', 'perlengkapan-rumah', 35000, 30],
            ['Sprei Single', 'perlengkapan-rumah', 150000, 12],
            ['Keset Kaki', 'perlengkapan-rumah', 30000, 28],

            ['Buku Catatan', 'alat-tulis', 15000, 50],
            ['Pulpen Gel', 'alat-tulis', 8000, 100],
            ['Pensil 2B', 'alat-tulis', 5000, 100],
            ['Penghapus', 'alat-tulis', 3000, 80],
            ['Penggaris 30cm', 'alat-tulis', 7000, 60],
            ['Spidol Warna', 'alat-tulis', 25000, 25],
            ['Map Dokumen', 'alat-tulis', 5000, 70],
            ['Kertas A4', 'alat-tulis', 55000, 30],
            ['Stabilo', 'alat-tulis', 12000, 45],
            ['Tempat Pensil', 'alat-tulis', 35000, 20],
        ];

        foreach ($products as $item) {
            [$name, $categorySlug, $price, $stock] = $item;

            $category = Category::where('slug', $categorySlug)->firstOrFail();

            Product::updateOrCreate(
                ['slug' => str($name)->slug()->toString()],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => 'Produk ' . $name . ' untuk kebutuhan sehari-hari.',
                    'price' => $price,
                    'stock' => $stock,
                    'image' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
