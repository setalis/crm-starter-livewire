<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operation;
use Carbon\Carbon;

class DebugChartData extends Command
{
    protected $signature = 'debug:chart-data {period=month}';
    protected $description = 'Отладка данных для графиков';

    public function handle()
    {
        $period = $this->argument('period');
        $this->info("=== Отладка данных графиков для периода: {$period} ===");
        
        // Получаем период как в компонентах
        $periodData = $this->getPeriodDates($period);
        
        $this->info("Период: {$periodData['label']}");
        $this->info("Начало: {$periodData['start']->toDateTimeString()}");
        $this->info("Конец: {$periodData['end']->toDateTimeString()}");
        
        // Получаем операции
        $operations = Operation::whereBetween('created_at', [$periodData['start'], $periodData['end']])
            ->orderBy('created_at')
            ->get();
            
        $this->info("Найдено операций: {$operations->count()}");
        $this->info("Общая сумма: {$operations->sum('total_amount')}");
        
        if ($operations->count() > 0) {
            $this->info("\nОперации в периоде:");
            $operations->each(function($op) {
                $this->line("ID: {$op->id} | {$op->operation_number} | {$op->type} | {$op->total_amount} | {$op->created_at}");
            });
        }
        
        // Проверим также все операции в базе
        $this->info("\n=== Все операции в БД ===");
        $allOperations = Operation::orderBy('created_at', 'desc')->take(10)->get();
        $this->info("Всего операций в БД: " . Operation::count());
        $this->info("Последние 10 операций:");
        $allOperations->each(function($op) {
            $this->line("ID: {$op->id} | {$op->operation_number} | {$op->type} | {$op->total_amount} | {$op->created_at}");
        });
        
        // Проверим текущую дату
        $this->info("\nТекущая дата/время: " . now()->toDateTimeString());
    }
    
    private function getPeriodDates($period)
    {
        $now = now();
        
        switch ($period) {
            case 'day':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => 'За сегодня'
                ];
            
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                    'label' => 'За неделю'
                ];
            
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'label' => 'За месяц'
                ];
                
            default:
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => 'За сегодня'
                ];
        }
    }
} 