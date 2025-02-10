<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Barang-barang Retail
        $products = [
            // Elektronik
            [
                'name' => 'Smartphone X1',
                'description' => 'Smartphone 6.5 inch, 128GB Storage',
                'price' => 3500000,
                'stock' => 20,
                'category' => 'Elektronik',
                'type' => 'Barang'
            ],
            [
                'name' => 'Laptop Basic',
                'description' => 'Laptop 14 inch, Intel i3, 8GB RAM',
                'price' => 6000000,
                'stock' => 15,
                'category' => 'Elektronik',
                'type' => 'Barang'
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Mouse wireless 2.4GHz',
                'price' => 150000,
                'stock' => 50,
                'category' => 'Elektronik',
                'type' => 'Barang'
            ],

            // Pakaian
            [
                'name' => 'Kemeja Formal',
                'description' => 'Kemeja lengan panjang',
                'price' => 250000,
                'stock' => 100,
                'category' => 'Pakaian',
                'type' => 'Barang'
            ],
            [
                'name' => 'Celana Jeans',
                'description' => 'Celana jeans slim fit',
                'price' => 350000,
                'stock' => 75,
                'category' => 'Pakaian',
                'type' => 'Barang'
            ],

            // Alat Tulis
            [
                'name' => 'Buku Tulis A4',
                'description' => 'Buku tulis 100 lembar',
                'price' => 25000,
                'stock' => 200,
                'category' => 'ATK',
                'type' => 'Barang'
            ],
            [
                'name' => 'Pulpen Pack',
                'description' => 'Pack 12 pulpen hitam',
                'price' => 48000,
                'stock' => 150,
                'category' => 'ATK',
                'type' => 'Barang'
            ],

            // Layanan/Jasa
            [
                'name' => 'Service Laptop Basic',
                'description' => 'Pembersihan dan maintenance dasar laptop',
                'price' => 250000,
                'stock' => 999,
                'category' => 'Service',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Service Laptop Full',
                'description' => 'Pembersihan, upgrade, dan perbaikan laptop',
                'price' => 500000,
                'stock' => 999,
                'category' => 'Service',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Install Software',
                'description' => 'Instalasi sistem operasi dan software',
                'price' => 200000,
                'stock' => 999,
                'category' => 'Service',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Konsultasi IT',
                'description' => 'Konsultasi masalah IT per jam',
                'price' => 150000,
                'stock' => 999,
                'category' => 'Konsultasi',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Training Basic Computer',
                'description' => 'Pelatihan dasar komputer (4 jam)',
                'price' => 400000,
                'stock' => 999,
                'category' => 'Training',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Desain Logo',
                'description' => 'Jasa pembuatan logo perusahaan',
                'price' => 750000,
                'stock' => 999,
                'category' => 'Design',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Website Development',
                'description' => 'Pembuatan website basic',
                'price' => 5000000,
                'stock' => 999,
                'category' => 'Development',
                'type' => 'Jasa'
            ],
            [
                'name' => 'Data Recovery',
                'description' => 'Pemulihan data yang hilang',
                'price' => 800000,
                'stock' => 999,
                'category' => 'Service',
                'type' => 'Jasa'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
