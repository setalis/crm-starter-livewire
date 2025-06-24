<?php

namespace Tests\Unit\Models;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleCashRegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_money_to_cash_register()
    {
        $cashRegister = CashRegister::create([
            'name' => 'Тестовая касса',
            'balance' => 1000,
            'is_active' => true
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $initialBalance = $cashRegister->balance;
        $amount = 500;
        $description = 'Пополнение кассы';

        $transaction = $cashRegister->addMoney($amount, $description, $user->id);

        // Проверяем обновление баланса
        $this->assertEquals($initialBalance + $amount, $cashRegister->fresh()->balance);

        // Проверяем создание транзакции
        $this->assertInstanceOf(CashTransaction::class, $transaction);
        $this->assertEquals('income', $transaction->type);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals($description, $transaction->description);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals($initialBalance + $amount, $transaction->balance_after);
    }

    /** @test */
    public function it_can_withdraw_money_from_cash_register()
    {
        $cashRegister = CashRegister::create([
            'name' => 'Тестовая касса',
            'balance' => 1000,
            'is_active' => true
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $initialBalance = $cashRegister->balance;
        $amount = 300;
        $description = 'Снятие средств';

        $transaction = $cashRegister->withdrawMoney($amount, $description, $user->id);

        // Проверяем обновление баланса
        $this->assertEquals($initialBalance - $amount, $cashRegister->fresh()->balance);

        // Проверяем создание транзакции
        $this->assertInstanceOf(CashTransaction::class, $transaction);
        $this->assertEquals('expense', $transaction->type);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals($description, $transaction->description);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals($initialBalance - $amount, $transaction->balance_after);
    }

    /** @test */
    public function it_allows_negative_balance()
    {
        $cashRegister = CashRegister::create([
            'name' => 'Тестовая касса',
            'balance' => 100,
            'is_active' => true
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $amount = 150; // Больше чем баланс

        $transaction = $cashRegister->withdrawMoney($amount, 'Тестовое снятие', $user->id);

        // Проверяем что баланс стал отрицательным
        $this->assertEquals(-50, $cashRegister->fresh()->balance);
        $this->assertEquals(-50, $transaction->balance_after);
    }

    /** @test */
    public function it_handles_multiple_transactions()
    {
        $cashRegister = CashRegister::create([
            'name' => 'Тестовая касса',
            'balance' => 1000,
            'is_active' => true
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Добавляем деньги
        $cashRegister->addMoney(500, 'Пополнение 1', $user->id);
        $cashRegister->addMoney(300, 'Пополнение 2', $user->id);

        // Снимаем деньги
        $cashRegister->withdrawMoney(200, 'Снятие 1', $user->id);

        // Проверяем количество транзакций
        $this->assertEquals(3, $cashRegister->transactions()->count());

        // Проверяем итоговый баланс
        $expectedBalance = 1000 + 500 + 300 - 200; // 1600
        $this->assertEquals($expectedBalance, $cashRegister->fresh()->balance);
    }
} 