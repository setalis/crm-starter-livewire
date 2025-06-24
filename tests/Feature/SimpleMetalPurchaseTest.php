<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Unit;
use App\Models\Element;
use App\Models\Product;
use App\Models\CashRegister;
use App\Models\Operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleMetalPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_purchase_operation()
    {
        // Создаем пользователя
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        // Создаем единицу измерения
        $unit = Unit::create([
            'name' => 'Килограмм',
            'short_name' => 'кг'
        ]);

        // Создаем кассу
        $cashRegister = CashRegister::create([
            'name' => 'Основная касса',
            'balance' => 10000,
            'is_active' => true
        ]);

        // Создаем продукт
        $product = Product::create([
            'name' => 'Медь',
            'type' => 'simple',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'purchase_price' => 650,
            'selling_price' => 700,
            'stock' => 100,
            'is_published' => true
        ]);

        // Создаем операцию покупки
        $operation = Operation::create([
            'type' => 'purchase',
            'total_amount' => 3250, // 5 кг * 650 руб
            'user_id' => $user->id,
            'cash_register_id' => $cashRegister->id,
            'comment' => 'Покупка меди у клиента'
        ]);

        // Проверяем создание операции
        $this->assertDatabaseHas('operations', [
            'id' => $operation->id,
            'type' => 'purchase',
            'total_amount' => 3250,
            'user_id' => $user->id
        ]);

        // Проверяем основные атрибуты
        $this->assertEquals('purchase', $operation->type);
        $this->assertEquals(3250, $operation->total_amount);
        $this->assertEquals($user->id, $operation->user_id);
        $this->assertEquals($cashRegister->id, $operation->cash_register_id);
    }

    /** @test */
    public function cash_register_balance_updates_after_purchase()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        $cashRegister = CashRegister::create([
            'name' => 'Основная касса',
            'balance' => 10000,
            'is_active' => true
        ]);

        $initialBalance = $cashRegister->balance;

        // Проводим покупку на 2000 рублей
        $cashRegister->withdrawMoney(2000, 'Покупка металла', $user->id);

        // Проверяем обновление баланса
        $this->assertEquals($initialBalance - 2000, $cashRegister->fresh()->balance);
        $this->assertEquals(8000, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function product_stock_can_be_updated()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $unit = Unit::create(['name' => 'Килограмм', 'short_name' => 'кг']);

        $product = Product::create([
            'name' => 'Алюминий',
            'type' => 'simple',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'purchase_price' => 150,
            'selling_price' => 200,
            'stock' => 50
        ]);

        $initialStock = $product->stock;
        
        // Обновляем остаток после покупки (добавляем 10 кг)
        $product->update(['stock' => $product->stock + 10]);

        $this->assertEquals($initialStock + 10, $product->fresh()->stock);
        $this->assertEquals(60, $product->fresh()->stock);
    }

    /** @test */
    public function composite_product_price_calculation_works()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $unit = Unit::create(['name' => 'Килограмм', 'short_name' => 'кг']);

        // Создаем элементы
        $copper = Element::create([
            'name' => 'Медь',
            'unit_id' => $unit->id,
            'price' => 650,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        $zinc = Element::create([
            'name' => 'Цинк',
            'unit_id' => $unit->id,
            'price' => 200,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        // Создаем составной продукт (латунь)
        $product = Product::create([
            'name' => 'Латунь',
            'type' => 'composite',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'selling_price' => 500,
            'stock' => 0
        ]);

        // Добавляем состав: 70% меди, 30% цинка
        $product->elements()->attach($copper->id, ['percentage' => 70]);
        $product->elements()->attach($zinc->id, ['percentage' => 30]);

        // Проверяем расчет цены
        // Ожидаемая цена: (650 * 0.7) + (200 * 0.3) = 455 + 60 = 515
        $calculatedPrice = $product->getCompositeProductPricePerKg();
        
        $this->assertEquals(51500, $calculatedPrice); // В копейках
        
        // Проверяем расчет для 3 кг
        $totalPrice = $product->getCompositeProductTotalPrice(3);
        $this->assertEquals(154500, $totalPrice); // 515 * 3 * 100
    }
} 