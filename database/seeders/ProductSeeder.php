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
                'user_id' => 1, // системный пользователь
                'name' => 'Черный металл',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 5,
                'selling_price' => 7,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Медь',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 325,
                'selling_price' => 350,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Аллюминий',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 53,
                'selling_price' => 65,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Латунь',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 200,
                'selling_price' => 210,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Нержавейка',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 40,
                'selling_price' => 45,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Аккумуляторы (слитый)',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 20,
                'selling_price' => 25,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Аккумуляторы (залитый)',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 20,
                'selling_price' => 25,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Аккумуляторы (черный)',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 13,
                'selling_price' => 15,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // системный пользователь
                'name' => 'Свинец',
                'type' => 'simple',
                'unit_id' => 1, // кг
                'purchase_price' => 55,
                'selling_price' => 60,
                'clogging' => 0,
                'image' => null,
                'is_published' => true,
                'position' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'user_id' => 1, // системный пользователь
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
            
            // Добавляем ценовые шкалы для простых продуктов
            if ($product['type'] === 'simple' && $product['name'] === 'Черный металл') {
                DB::table('product_price_scales')->insert([
                    [
                        'product_id' => $productId,
                        'threshold_kg' => 10,
                        'price' => 0.95, // При покупке от 10 кг - 0.95 руб/кг
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'product_id' => $productId,
                        'threshold_kg' => 50,
                        'price' => 0.90, // При покупке от 50 кг - 0.90 руб/кг
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'product_id' => $productId,
                        'threshold_kg' => 100,
                        'price' => 0.85, // При покупке от 100 кг - 0.85 руб/кг
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }
} 