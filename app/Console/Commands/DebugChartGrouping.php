<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operation;
use Carbon\Carbon;

class DebugChartGrouping extends Command
{
    protected $signature = 'debug:chart-grouping';
    protected $description = 'Отладка группировки данных для графиков';

    public function handle()
    {
        $this->info("=== Отладка группировки данных по дням месяца ===");
        
        $now = now();
        $period = [
            'start' => $now->copy()->startOfMonth(),
            'end' => $now->copy()->endOfMonth(),
        ];
        
        $this->info("Период: {$period['start']->toDateString()} - {$period['end']->toDateString()}");
        
        // Получаем операции за месяц
        $operations = Operation::whereBetween('created_at', [$period['start'], $period['end']])
            ->orderBy('created_at')
            ->get();
            
        $this->info("Всего операций в месяце: {$operations->count()}");
        
        if ($operations->count() == 0) {
            $this->warn("Нет операций в текущем месяце!");
            return;
        }
        
        // Показываем все операции с их датами
        $this->info("\nВсе операции в месяце:");
        $operations->each(function($op) {
            $this->line("ID: {$op->id} | {$op->created_at->format('Y-m-d H:i:s')} | {$op->type} | {$op->total_amount}");
        });
        
        // Тестируем группировку как в компоненте
        $this->info("\n=== Тестируем группировку по дням ===");
        
        $start = $period['start']->copy();
        $end = $period['end']->copy();
        $daysDiff = $start->diffInDays($end) + 1;
        
        $this->info("Дней в периоде: {$daysDiff}");
        
        $labels = [];
        $data = [];
        
        for ($i = 0; $i < $daysDiff; $i++) {
            $currentDay = $start->copy()->addDays($i);
            $dayLabel = $currentDay->format('d.m');
            
            // Фильтрация как в компоненте
            $dayRevenue = $operations->filter(function ($operation) use ($currentDay) {
                return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
            })->sum('total_amount');
            
            $labels[] = $dayLabel;
            $data[] = (float) $dayRevenue;
            
            if ($dayRevenue > 0) {
                $this->line("День {$dayLabel} ({$currentDay->format('Y-m-d')}): {$dayRevenue}");
                
                // Показываем операции этого дня
                $dayOperations = $operations->filter(function ($operation) use ($currentDay) {
                    return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
                });
                
                $dayOperations->each(function($op) {
                    $this->line("  - ID:{$op->id} {$op->created_at->format('H:i:s')} {$op->type} {$op->total_amount}");
                });
            }
        }
        
        $this->info("\nИтого данных:");
        $this->info("Меток: " . count($labels));
        $this->info("Данных: " . count($data));
        $this->info("Сумма всех данных: " . array_sum($data));
        $this->info("Первые 5 меток: " . implode(', ', array_slice($labels, 0, 5)));
        $this->info("Первые 5 значений: " . implode(', ', array_slice($data, 0, 5)));
    }
} 