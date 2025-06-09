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
                'price' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Никель',
                'unit_id' => 1, // кг
                'price' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],  
            [
                'name' => 'Ванадий',
                'unit_id' => 1, // кг
                'price' => 1,
                'stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 