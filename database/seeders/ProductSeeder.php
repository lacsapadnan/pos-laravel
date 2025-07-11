<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create some basic categories if they don't exist
        $categories = [
            'Minuman',
            'Makanan',
            'Snack',
            'Alat Tulis',
            'Kebutuhan Pokok'
        ];

        $categoryIds = [];
        foreach ($categories as $name) {
            $category = Category::firstOrCreate(['name' => $name]);
            $categoryIds[$name] = $category->id;
        }

        // Create some basic units if they don't exist
        $units = [
            'PCS' => ['abbreviation' => 'Pcs', 'description' => 'Pieces'],
            'BOX' => ['abbreviation' => 'Box', 'description' => 'Box'],
            'KG' => ['abbreviation' => 'Kg', 'description' => 'Kilogram'],
            'PACK' => ['abbreviation' => 'Pack', 'description' => 'Pack'],
            'BTL' => ['abbreviation' => 'Btl', 'description' => 'Botol']
        ];

        $unitIds = [];
        foreach ($units as $name => $details) {
            $unit = Unit::firstOrCreate(
                ['name' => $name],
                [
                    'abbreviation' => $details['abbreviation'],
                    'description' => $details['description']
                ]
            );
            $unitIds[$name] = $unit->id;
        }

        // Product data
        $products = [
            [
                'name' => 'Aqua 600ml',
                'code' => 'AQ600',
                'description' => 'Air mineral Aqua ukuran 600ml',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 3500,
                'stock' => 100,
                'minimum_stock' => 20,
            ],
            [
                'name' => 'Coca Cola 1.5L',
                'code' => 'CC15L',
                'description' => 'Minuman bersoda Coca Cola 1.5 Liter',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 12000,
                'stock' => 50,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Indomie Goreng',
                'code' => 'IMG01',
                'description' => 'Mie instan goreng Indomie',
                'category' => 'Makanan',
                'unit' => 'PCS',
                'price' => 3500,
                'stock' => 200,
                'minimum_stock' => 50,
            ],
            [
                'name' => 'Beras Premium 5kg',
                'code' => 'BP5KG',
                'description' => 'Beras premium kualitas terbaik',
                'category' => 'Kebutuhan Pokok',
                'unit' => 'PACK',
                'price' => 68000,
                'stock' => 30,
                'minimum_stock' => 5,
            ],
            [
                'name' => 'Chitato Original',
                'code' => 'CTO01',
                'description' => 'Keripik kentang rasa original',
                'category' => 'Snack',
                'unit' => 'PCS',
                'price' => 9500,
                'stock' => 75,
                'minimum_stock' => 15,
            ],
            [
                'name' => 'Pensil 2B Faber Castell',
                'code' => 'P2BFC',
                'description' => 'Pensil 2B merek Faber Castell',
                'category' => 'Alat Tulis',
                'unit' => 'PCS',
                'price' => 3500,
                'stock' => 150,
                'minimum_stock' => 30,
            ],
            [
                'name' => 'Minyak Goreng 2L',
                'code' => 'MG2L',
                'description' => 'Minyak goreng kemasan 2 Liter',
                'category' => 'Kebutuhan Pokok',
                'unit' => 'BTL',
                'price' => 28000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Teh Botol Sosro',
                'code' => 'TBS01',
                'description' => 'Teh dalam kemasan botol',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 5000,
                'stock' => 80,
                'minimum_stock' => 20,
            ],
            [
                'name' => 'Roti Tawar',
                'code' => 'RT001',
                'description' => 'Roti tawar segar',
                'category' => 'Makanan',
                'unit' => 'PACK',
                'price' => 15000,
                'stock' => 25,
                'minimum_stock' => 5,
            ],
            [
                'name' => 'Buku Tulis',
                'code' => 'BT001',
                'description' => 'Buku tulis 58 lembar',
                'category' => 'Alat Tulis',
                'unit' => 'PCS',
                'price' => 4500,
                'stock' => 100,
                'minimum_stock' => 20,
            ],
            [
                'name' => 'Pocari Sweat 500ml',
                'code' => 'PS500',
                'description' => 'Minuman isotonik Pocari Sweat',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 7000,
                'stock' => 60,
                'minimum_stock' => 12,
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'code' => 'GP1KG',
                'description' => 'Gula pasir kemasan 1kg',
                'category' => 'Kebutuhan Pokok',
                'unit' => 'PACK',
                'price' => 16000,
                'stock' => 50,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Lays Classic',
                'code' => 'LC001',
                'description' => 'Keripik kentang Lays Original',
                'category' => 'Snack',
                'unit' => 'PCS',
                'price' => 9000,
                'stock' => 45,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Pulpen Pilot',
                'code' => 'PP001',
                'description' => 'Pulpen hitam merek Pilot',
                'category' => 'Alat Tulis',
                'unit' => 'PCS',
                'price' => 2500,
                'stock' => 200,
                'minimum_stock' => 50,
            ],
            [
                'name' => 'Sprite 425ml',
                'code' => 'SP425',
                'description' => 'Minuman bersoda Sprite',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 5000,
                'stock' => 70,
                'minimum_stock' => 15,
            ],
            [
                'name' => 'Telur 1kg',
                'code' => 'TL1KG',
                'description' => 'Telur ayam segar',
                'category' => 'Kebutuhan Pokok',
                'unit' => 'KG',
                'price' => 25000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Oreo Classic',
                'code' => 'OC001',
                'description' => 'Biskuit sandwich Oreo Classic',
                'category' => 'Snack',
                'unit' => 'PCS',
                'price' => 8500,
                'stock' => 85,
                'minimum_stock' => 20,
            ],
            [
                'name' => 'Penghapus Steadtler',
                'code' => 'PS001',
                'description' => 'Penghapus putih Steadtler',
                'category' => 'Alat Tulis',
                'unit' => 'PCS',
                'price' => 4000,
                'stock' => 120,
                'minimum_stock' => 30,
            ],
            [
                'name' => 'Fanta 1L',
                'code' => 'FT1L',
                'description' => 'Minuman bersoda Fanta 1 Liter',
                'category' => 'Minuman',
                'unit' => 'BTL',
                'price' => 10000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Tepung Terigu 1kg',
                'code' => 'TT1KG',
                'description' => 'Tepung terigu protein tinggi',
                'category' => 'Kebutuhan Pokok',
                'unit' => 'KG',
                'price' => 12000,
                'stock' => 35,
                'minimum_stock' => 8,
            ],
            [
                'name' => 'Taro Net',
                'code' => 'TN001',
                'description' => 'Snack Taro Net Original',
                'category' => 'Snack',
                'unit' => 'PCS',
                'price' => 8500,
                'stock' => 60,
                'minimum_stock' => 15,
            ],
            [
                'name' => 'Penggaris 30cm',
                'code' => 'PG30',
                'description' => 'Penggaris plastik 30cm',
                'category' => 'Alat Tulis',
                'unit' => 'PCS',
                'price' => 5000,
                'stock' => 80,
                'minimum_stock' => 20,
            ],
            [
                'name' => 'Nescafe Classic',
                'code' => 'NC001',
                'description' => 'Kopi instan Nescafe Classic',
                'category' => 'Minuman',
                'unit' => 'PCS',
                'price' => 13000,
                'stock' => 45,
                'minimum_stock' => 10,
            ],
            [
                'name' => 'Mie Sedaap Goreng',
                'code' => 'MSG01',
                'description' => 'Mie instan goreng Mie Sedaap',
                'category' => 'Makanan',
                'unit' => 'PCS',
                'price' => 3300,
                'stock' => 150,
                'minimum_stock' => 30,
            ],
            [
                'name' => 'Good Time Cookies',
                'code' => 'GTC01',
                'description' => 'Biskuit Good Time',
                'category' => 'Snack',
                'unit' => 'PCS',
                'price' => 10000,
                'stock' => 55,
                'minimum_stock' => 15,
            ]
        ];

        // Create products
        foreach ($products as $product) {
            Product::firstOrCreate(
                ['code' => $product['code']],
                [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'category_id' => $categoryIds[$product['category']],
                    'unit_id' => $unitIds[$product['unit']],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'minimum_stock' => $product['minimum_stock'],
                    'status' => true,
                ]
            );
        }
    }
}
