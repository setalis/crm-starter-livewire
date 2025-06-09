<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            ['name' => 'Килограмм', 'short_name' => 'кг.'],
            ['name' => 'Грамм', 'short_name' => 'гр.'],
            ['name' => 'Штука', 'short_name' => 'шт.'],
            ['name' => 'Литр', 'short_name' => 'л.'],
        ]);
    }
}
