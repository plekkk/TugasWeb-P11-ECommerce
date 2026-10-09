<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Perangkat elektronik dan gadget.'],
            ['name' => 'Pakaian', 'slug' => 'pakaian', 'description' => 'Pakaian untuk berbagai kebutuhan.'],
            ['name' => 'Aksesori', 'slug' => 'aksesori', 'description' => 'Aksesori dan perlengkapan pribadi.'],
            ['name' => 'Perlengkapan Rumah', 'slug' => 'perlengkapan-rumah', 'description' => 'Perlengkapan untuk rumah tangga.'],
            ['name' => 'Alat Tulis', 'slug' => 'alat-tulis', 'description' => 'Alat tulis dan kebutuhan belajar.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
