<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashRegister = CashRegister::create([
            'name' => 'Основная касса',
            'description' => 'Главная касса для операций с металлами',
            'balance' => 0,
            'is_active' => true,
        ]);

        // Добавляем начальную сумму в кассу
        $cashRegister->addMoney(100000, 'Начальный капитал', 1);
    }
}
