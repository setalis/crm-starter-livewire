<?php

namespace Database\Seeders;

use App\Models\Recount;
use App\Models\RecountItem;
use App\Models\CashRecount;
use App\Models\Product;
use App\Models\Element;
use App\Models\CashRegister;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecountSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('Пользователь не найден. Пропускаем сиддер переучетов.');
            return;
        }

        // Создаем переучет продуктов
        $this->createProductRecount($user);
        
        // Создаем переучет элементов  
        $this->createElementRecount($user);
        
        // Создаем переучет кассы
        $this->createCashRecount($user);
    }

    private function createProductRecount($user)
    {
        $products = Product::where('stock', '>', 0)->take(5)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('Продукты с остатками не найдены.');
            return;
        }

        $recount = Recount::create([
            'number' => Recount::generateNumber(),
            'type' => 'products',
            'user_id' => $user->id,
            'reason' => 'Плановый переучет продуктов на конец месяца',
            'notes' => 'Переучет проводится в рамках инвентаризации',
            'started_at' => now()->subHours(2),
        ]);

        foreach ($products as $product) {
            RecountItem::create([
                'recount_id' => $recount->id,
                'countable_type' => Product::class,
                'countable_id' => $product->id,
                'expected_quantity' => $product->stock,
                'actual_quantity' => $product->stock + rand(-5, 5) / 10, // Имитация небольших расхождений
                'unit_price' => $product->purchase_price ?? 0,
            ]);
        }

        // Пересчитываем расхождения
        foreach ($recount->items as $item) {
            $item->calculateDiscrepancy();
        }

        $this->command->info("Создан переучет продуктов: {$recount->number}");
    }

    private function createElementRecount($user)
    {
        $elements = Element::where('stock', '>', 0)->take(3)->get();
        
        if ($elements->isEmpty()) {
            $this->command->warn('Элементы с остатками не найдены.');
            return;
        }

        $recount = Recount::create([
            'number' => Recount::generateNumber(),
            'type' => 'elements',
            'user_id' => $user->id,
            'reason' => 'Внеплановый переучет элементов после жалобы на недостачу',
            'notes' => 'Переучет проводится для выяснения причин расхождений',
        ]);

        foreach ($elements as $element) {
            RecountItem::create([
                'recount_id' => $recount->id,
                'countable_type' => Element::class,
                'countable_id' => $element->id,
                'expected_quantity' => $element->stock,
                'unit_price' => $element->price ?? 0,
            ]);
        }

        $this->command->info("Создан переучет элементов: {$recount->number}");
    }

    private function createCashRecount($user)
    {
        $cashRegister = CashRegister::where('is_active', true)->first();
        
        if (!$cashRegister) {
            $this->command->warn('Активная касса не найдена.');
            return;
        }

        $recount = CashRecount::create([
            'number' => CashRecount::generateNumber(),
            'cash_register_id' => $cashRegister->id,
            'user_id' => $user->id,
            'expected_balance' => $cashRegister->balance,
            'actual_balance' => $cashRegister->balance + rand(-100, 100), // Имитация небольшого расхождения
            'reason' => 'Ежедневный переучет кассы',
            'notes' => 'Стандартная процедура пересчета денежных средств',
            'started_at' => now()->subHour(),
            'completed_at' => now()->subMinutes(30),
            'status' => 'completed',
        ]);

        // Рассчитываем расхождение
        $recount->discrepancy = $recount->actual_balance - $recount->expected_balance;
        $recount->save();

        $this->command->info("Создан переучет кассы: {$recount->number}");
    }
} 