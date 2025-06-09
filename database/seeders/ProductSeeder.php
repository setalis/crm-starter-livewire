<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
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
        ]);
    }
} 