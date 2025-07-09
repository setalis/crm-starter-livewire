<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operation;
use Carbon\Carbon;

class CheckOperationsData extends Command
{
    protected $signature = 'check:operations';
    protected $description = 'Проверить данные операций для отладки графиков';

    public function handle()
    {
        $this->info('=== Проверка данных операций ===');
        
        // Общая статистика
        $totalOperations = Operation::count();
        $this->info("Всего операций в БД: {$totalOperations}");
        
        if ($totalOperations === 0) {
            $this->warn('В базе данных нет операций! Создаем тестовые данные...');
            $this->createTestOperations();
            return;
        }
        
        // Статистика по типам
        $sales = Operation::where('type', 'sale')->count();
        $purchases = Operation::where('type', 'purchase')->count();
        $this->info("Продажи: {$sales}, Покупки: {$purchases}");
        
        // За сегодня
        $today = Carbon::today();
        $todayOperations = Operation::whereDate('created_at', $today)->count();
        $todayAmount = Operation::whereDate('created_at', $today)->sum('total_amount');
        $this->info("За сегодня: {$todayOperations} операций на сумму {$todayAmount}");
        
        // За неделю
        $weekStart = Carbon::now()->startOfWeek();
        $weekOperations = Operation::where('created_at', '>=', $weekStart)->count();
        $weekAmount = Operation::where('created_at', '>=', $weekStart)->sum('total_amount');
        $this->info("За неделю: {$weekOperations} операций на сумму {$weekAmount}");
        
        // Последние операции
        $this->info("\nПоследние 5 операций:");
        Operation::latest()->take(5)->get()->each(function($op) {
            $this->line("{$op->id} | {$op->operation_number} | {$op->type} | {$op->total_amount} | {$op->created_at}");
        });
    }
    
    private function createTestOperations()
    {
        $this->info('Создаем тестовые операции...');
        
        // Операции за последние 7 дней
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // 2-4 операции в день
            $operationsPerDay = rand(2, 4);
            
            for ($j = 0; $j < $operationsPerDay; $j++) {
                $type = rand(0, 1) ? 'sale' : 'purchase';
                $amount = rand(1000, 10000);
                
                Operation::create([
                    'operation_number' => 'TEST-' . $date->format('Ymd') . '-' . ($j + 1),
                    'type' => $type,
                    'total_amount' => $amount,
                    'user_id' => 1, // Предполагаем, что есть пользователь с ID 1
                    'created_at' => $date->addHours(rand(8, 18))->addMinutes(rand(0, 59)),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->info('Создано тестовых операций: ' . Operation::count());
    }
} 