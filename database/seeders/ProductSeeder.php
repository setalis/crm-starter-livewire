<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Черный металл',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 1,
                'selling_price' => 2,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Нержавеющая сталь 304',
                'type' => 'composite',
                'unit_id' => 1, // кг
                'purchase_price' => null,
                'selling_price' => 25,
                'clogging' => null,
                'image' => null,
                'is_published' => true,
                'position' => 2,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId($product);
            
            // Добавляем элементы для составного продукта
            if ($product['type'] === 'composite' && $product['name'] === 'Нержавеющая сталь 304') {
                // Никель 8%, Молибден 2%
                DB::table('element_product')->insert([
                    [
                        'product_id' => $productId,
                        'element_id' => 2, // Никель
                        'percentage' => 8.0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'product_id' => $productId,
                        'element_id' => 1, // Молибден
                        'percentage' => 2.0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }
} 