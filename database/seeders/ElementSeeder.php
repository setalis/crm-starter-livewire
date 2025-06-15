<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElementSeeder extends Seeder
{
    public function run()
    {
        DB::table('elements')->insert([
            [
                'name' => 'Молибден',
                'unit_id' => 1, // кг
                'price' => 2.50, // грн за 1% содержания
                'unit_price' => 250.00, // грн за 1 кг
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Никель',
                'unit_id' => 1, // кг
                'price' => 1.80, // грн за 1% содержания
                'unit_price' => 180.00, // грн за 1 кг
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],  
            [
                'name' => 'Ванадий',
                'unit_id' => 1, // кг
                'price' => 3.20, // грн за 1% содержания
                'unit_price' => 320.00, // грн за 1 кг
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 