<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operation;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Очистим старые операции
        Operation::query()->delete();

        $products = Product::where('is_published', true)->get();
        $users = User::all();
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subMonth();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->info('Недостаточно данных для создания операций (нет продуктов или пользователей).');
            return;
        }

        // Создадим 50-100 случайных операций за последний месяц
        for ($i = 0; $i < rand(50, 100); $i++) {
            $product = $products->random();
            $user = $users->random();
            $type = ['purchase', 'sale'][rand(0, 1)];
            
            // Случайная дата в пределах последнего месяца
            $date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));

            $quantity = rand(1, 100) / 10; // от 0.1 до 10.0
            $price = ($type === 'purchase') ? $product->purchase_price : $product->selling_price;
            $totalAmount = $quantity * $price;
            
            if ($price <= 0) continue; // Пропускаем, если цена не установлена

            $operation = Operation::create([
                'user_id' => $user->id,
                'type' => $type,
                'total_amount' => $totalAmount,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $operation->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalAmount,
            ]);
        }

        $this->command->info('Тестовые операции успешно созданы!');
    }
} 