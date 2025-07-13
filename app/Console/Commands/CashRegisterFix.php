<?php

namespace App\Console\Commands;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Operation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CashRegisterFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash:fix {--check-only : Только проверить проблемы без исправления} {--fix-duplicates : Исправить дублированные транзакции} {--fix-balance : Исправить баланс кассы} {--clean-orphans : Очистить осиротевшие транзакции возврата} {--recalculate-history : Пересчитать баланс в истории транзакций}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет и исправляет проблемы с кассой';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Диагностика кассы ===');
        
        // Получаем все кассы
        $cashRegisters = CashRegister::with('transactions.operation')->get();
        
        if ($cashRegisters->isEmpty()) {
            $this->warn('Кассы не найдены');
            return;
        }
        
        foreach ($cashRegisters as $cashRegister) {
            $this->info("Касса: {$cashRegister->name}");
            $this->info("Текущий баланс: {$cashRegister->balance}");
            
            // Проверяем транзакции
            $transactions = $cashRegister->transactions()->with('operation')->orderBy('created_at')->get();
            $this->info("Всего транзакций: {$transactions->count()}");
            
            // Показываем последние 10 транзакций
            $this->info("Последние транзакции:");
            foreach ($transactions->take(-10) as $transaction) {
                $operationInfo = $transaction->operation ? 
                    "Операция: {$transaction->operation->operation_number}" : 
                    "Без операции";
                $this->line("  ID: {$transaction->id} | {$transaction->created_at} | {$transaction->type} | {$transaction->amount} | {$operationInfo} | Баланс: {$transaction->balance_after}");
            }
            
            // Проверяем транзакции без привязки к операциям
            $orphanTransactions = $cashRegister->transactions()
                ->whereNull('operation_id')
                ->where(function ($query) {
                    $query->where('description', 'like', '%Возврат средств при отмене%')
                          ->orWhere('description', 'like', '%Списание средств при отмене%')
                          ->orWhere('description', 'like', '%редактировании%');
                })
                ->get();
                
            $this->info("Транзакций возврата без операций: {$orphanTransactions->count()}");
            
            if ($orphanTransactions->count() > 0) {
                $this->warn("Найдены потенциально дублированные транзакции:");
                foreach ($orphanTransactions as $transaction) {
                    $this->line("- ID: {$transaction->id}, Дата: {$transaction->created_at}, Сумма: {$transaction->amount}, Описание: {$transaction->description}");
                }
            }
            
            // Проверяем баланс
            $calculatedBalance = $this->calculateBalance($cashRegister);
            $this->info("Расчетный баланс: {$calculatedBalance}");
            
            if (abs($cashRegister->balance - $calculatedBalance) > 0.01) {
                $this->error("ОШИБКА: Баланс кассы не соответствует расчетному!");
                $this->error("Разница: " . ($cashRegister->balance - $calculatedBalance));
            } else {
                $this->info("Баланс кассы корректен");
            }
            
            $this->line('');
        }
        
        // Если указана опция исправления дублированных транзакций
        if ($this->option('fix-duplicates')) {
            $this->fixDuplicateTransactions();
        }
        
        // Если указана опция исправления баланса
        if ($this->option('fix-balance')) {
            $this->fixBalance();
        }
        
        // Если указана опция пересчета истории
        if ($this->option('recalculate-history')) {
            $this->recalculateTransactionHistory();
        }
        
        if ($this->option('check-only')) {
            $this->info('Проверка завершена. Исправления не выполнены.');
        }
    }
    
    private function calculateBalance(CashRegister $cashRegister)
    {
        $balance = 0;
        
        $transactions = $cashRegister->transactions()->orderBy('created_at')->get();
        
        foreach ($transactions as $transaction) {
            if ($transaction->type === 'income') {
                $balance += $transaction->amount;
            } else {
                $balance -= $transaction->amount;
            }
        }
        
        return $balance;
    }
    
    private function fixDuplicateTransactions()
    {
        $this->info('=== Исправление дублированных транзакций ===');
        
        // Находим транзакции возврата без привязки к операциям
        $duplicates = CashTransaction::whereNull('operation_id')
            ->where(function ($query) {
                $query->where('description', 'like', '%Возврат средств при отмене%')
                      ->orWhere('description', 'like', '%Списание средств при отмене%');
            })
            ->get();
            
        if ($duplicates->isEmpty()) {
            $this->info('Дублированные транзакции не найдены');
            return;
        }
        
        $this->warn("Найдено {$duplicates->count()} потенциально дублированных транзакций");
        
        if ($this->confirm('Удалить найденные дублированные транзакции?')) {
            DB::transaction(function () use ($duplicates) {
                foreach ($duplicates as $duplicate) {
                    $cashRegister = $duplicate->cashRegister;
                    
                    // Корректируем баланс кассы
                    if ($duplicate->type === 'income') {
                        $cashRegister->decrement('balance', $duplicate->amount);
                    } else {
                        $cashRegister->increment('balance', $duplicate->amount);
                    }
                    
                    // Удаляем дублированную транзакцию
                    $duplicate->delete();
                    
                    $this->info("Удалена транзакция ID: {$duplicate->id}, Сумма: {$duplicate->amount}");
                }
            });
            
            $this->info('Дублированные транзакции успешно удалены');
        }
    }

    private function fixBalance()
    {
        $this->info('=== Исправление баланса кассы ===');
        
        $cashRegisters = CashRegister::all();
        
        foreach ($cashRegisters as $cashRegister) {
            $calculatedBalance = $this->calculateBalance($cashRegister);
            
            if (abs($cashRegister->balance - $calculatedBalance) > 0.01) {
                $this->warn("Касса: {$cashRegister->name}");
                $this->warn("Текущий баланс: {$cashRegister->balance}");
                $this->warn("Расчетный баланс: {$calculatedBalance}");
                $this->warn("Разница: " . ($cashRegister->balance - $calculatedBalance));
                
                if ($this->confirm("Исправить баланс кассы '{$cashRegister->name}'?")) {
                    $cashRegister->update(['balance' => $calculatedBalance]);
                    $this->info("Баланс кассы '{$cashRegister->name}' исправлен: {$calculatedBalance}");
                }
            } else {
                $this->info("Баланс кассы '{$cashRegister->name}' корректен");
            }
        }
    }

    private function recalculateTransactionHistory()
    {
        $this->info('=== Пересчет истории транзакций ===');
        
        $cashRegisters = CashRegister::all();
        
        foreach ($cashRegisters as $cashRegister) {
            $this->info("Пересчитываем историю для кассы: {$cashRegister->name}");
            
            // Получаем все транзакции в хронологическом порядке
            $transactions = $cashRegister->transactions()->orderBy('created_at')->get();
            
            if ($transactions->isEmpty()) {
                $this->info("Транзакций нет");
                continue;
            }
            
            $runningBalance = 0;
            $updatedCount = 0;
            
            foreach ($transactions as $transaction) {
                // Рассчитываем баланс после этой транзакции
                if ($transaction->type === 'income') {
                    $runningBalance += $transaction->amount;
                } else {
                    $runningBalance -= $transaction->amount;
                }
                
                // Обновляем balance_after если он отличается
                if (abs($transaction->balance_after - $runningBalance) > 0.01) {
                    $transaction->update(['balance_after' => $runningBalance]);
                    $updatedCount++;
                }
            }
            
            $this->info("Обновлено транзакций: {$updatedCount}");
            $this->info("Финальный баланс: {$runningBalance}");
            
            // Проверяем, соответствует ли финальный баланс балансу кассы
            if (abs($cashRegister->balance - $runningBalance) > 0.01) {
                $this->warn("Внимание: Баланс кассы ({$cashRegister->balance}) не соответствует расчетному ({$runningBalance})");
            }
        }
    }
}
