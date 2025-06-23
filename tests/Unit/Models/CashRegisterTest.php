<?php

namespace Tests\Unit\Models;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CashRegisterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_add_money_to_cash_register()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        $initialBalance = $cashRegister->balance;
        
        $amount = 500;
        $description = 'Пополнение кассы';
        
        $transaction = $cashRegister->addMoney($amount, $description, $this->user->id);
        
        // Проверяем обновление баланса
        $this->assertEquals($initialBalance + $amount, $cashRegister->fresh()->balance);
        
        // Проверяем создание транзакции
        $this->assertInstanceOf(CashTransaction::class, $transaction);
        $this->assertEquals('income', $transaction->type);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals($description, $transaction->description);
        $this->assertEquals($this->user->id, $transaction->user_id);
        $this->assertEquals($initialBalance + $amount, $transaction->balance_after);
    }

    /** @test */
    public function it_can_withdraw_money_from_cash_register()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        $initialBalance = $cashRegister->balance;
        
        $amount = 300;
        $description = 'Снятие средств';
        
        $transaction = $cashRegister->withdrawMoney($amount, $description, $this->user->id);
        
        // Проверяем обновление баланса
        $this->assertEquals($initialBalance - $amount, $cashRegister->fresh()->balance);
        
        // Проверяем создание транзакции
        $this->assertInstanceOf(CashTransaction::class, $transaction);
        $this->assertEquals('expense', $transaction->type);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals($description, $transaction->description);
        $this->assertEquals($this->user->id, $transaction->user_id);
        $this->assertEquals($initialBalance - $amount, $transaction->balance_after);
    }

    /** @test */
    public function it_allows_withdrawal_that_results_in_negative_balance()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 100]);
        
        $amount = 150; // Больше чем баланс
        
        $transaction = $cashRegister->withdrawMoney($amount, 'Тестовое снятие', $this->user->id);
        
        // Проверяем что баланс стал отрицательным
        $this->assertEquals(-50, $cashRegister->fresh()->balance);
        $this->assertEquals(-50, $transaction->balance_after);
    }

    /** @test */
    public function it_creates_transaction_records_for_money_operations()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        
        // Добавляем деньги
        $cashRegister->addMoney(500, 'Пополнение 1', $this->user->id);
        $cashRegister->addMoney(300, 'Пополнение 2', $this->user->id);
        
        // Снимаем деньги
        $cashRegister->withdrawMoney(200, 'Снятие 1', $this->user->id);
        
        // Проверяем количество транзакций
        $this->assertEquals(3, $cashRegister->transactions()->count());
        
        // Проверяем типы транзакций
        $incomeTransactions = $cashRegister->transactions()->where('type', 'income')->count();
        $expenseTransactions = $cashRegister->transactions()->where('type', 'expense')->count();
        
        $this->assertEquals(2, $incomeTransactions);
        $this->assertEquals(1, $expenseTransactions);
        
        // Проверяем итоговый баланс
        $expectedBalance = 1000 + 500 + 300 - 200; // 1600
        $this->assertEquals($expectedBalance, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function it_updates_balance_correctly_after_each_transaction()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        
        // Первая транзакция
        $transaction1 = $cashRegister->addMoney(500, 'Пополнение');
        $this->assertEquals(1500, $transaction1->balance_after);
        
        // Вторая транзакция
        $transaction2 = $cashRegister->withdrawMoney(200, 'Снятие');
        $this->assertEquals(1300, $transaction2->balance_after);
        
        // Третья транзакция
        $transaction3 = $cashRegister->addMoney(100, 'Еще пополнение');
        $this->assertEquals(1400, $transaction3->balance_after);
        
        // Проверяем финальный баланс
        $this->assertEquals(1400, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function it_handles_null_description_gracefully()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        
        $transaction = $cashRegister->addMoney(100, null, $this->user->id);
        
        $this->assertNull($transaction->description);
        $this->assertEquals(1100, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function it_handles_null_user_id_gracefully()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000]);
        
        $transaction = $cashRegister->addMoney(100, 'Тест', null);
        
        $this->assertNull($transaction->user_id);
        $this->assertEquals(1100, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function it_handles_decimal_amounts_correctly()
    {
        $cashRegister = CashRegister::factory()->create(['balance' => 1000.50]);
        
        $transaction1 = $cashRegister->addMoney(123.75, 'Тестовое пополнение');
        $this->assertEquals(1124.25, $cashRegister->fresh()->balance);
        
        $transaction2 = $cashRegister->withdrawMoney(50.25, 'Тестовое снятие');
        $this->assertEquals(1074.00, $cashRegister->fresh()->balance);
    }

    /** @test */
    public function cash_register_has_relationships_configured()
    {
        $cashRegister = CashRegister::factory()->create();
        
        // Создаем несколько транзакций
        $cashRegister->addMoney(100, 'Тест 1');
        $cashRegister->withdrawMoney(50, 'Тест 2');
        
        // Проверяем отношение к транзакциям
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $cashRegister->transactions);
        $this->assertEquals(2, $cashRegister->transactions()->count());
        
        // Проверяем отношение к операциям (должно существовать)
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $cashRegister->operations());
    }
} 