<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Akun Default (Admin & Kasir)
        User::create([
            'name'     => 'Admin Cafe',
            'email'    => 'admin@mahogany.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Kasir 1',
            'email'    => 'kasir@mahogany.com',
            'password' => Hash::make('password123'),
            'role'     => 'kasir',
        ]);

        // 2. Seed Kategori Menu
        $kopi = Category::create(['name' => 'Kopi']);
        $nonKopi = Category::create(['name' => 'Non-Kopi']);
        $makanan = Category::create(['name' => 'Makanan Ringan']);

        // 3. Seed Produk / Menu Cafe
        Product::create([
            'category_id' => $kopi->id,
            'name'        => 'Espresso',
            'price'       => 15000,
        ]);

        Product::create([
            'category_id' => $kopi->id,
            'name'        => 'Americano',
            'price'       => 18000,
        ]);

        Product::create([
            'category_id' => $kopi->id,
            'name'        => 'Caffe Latte',
            'price'       => 22000,
        ]);

        Product::create([
            'category_id' => $nonKopi->id,
            'name'        => 'Matcha Latte',
            'price'       => 24000,
        ]);

        Product::create([
            'category_id' => $nonKopi->id,
            'name'        => 'Chocolate Ice',
            'price'       => 20000,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name'        => 'French Fries',
            'price'       => 15000,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'name'        => 'Croissant Butter',
            'price'       => 18000,
        ]);
    }
}