<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;

class OperationsSalesChart extends Component
{
    protected $listeners = ['period-changed' => 'handlePeriodChange'];

    public $periodData = [];
    public $chartData = [];
    public $salesCount = 0;
    public $purchasesCount = 0;
    public $salesAmount = 0;
    public $purchasesAmount = 0;
    public $profitMargin = 0;

    public function mount()
    {
        // Если нет данных периода - устанавливаем день по умолчанию
        if (empty($this->periodData)) {
            $now = now();
            $this->periodData = [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'label' => 'За сегодня',
                'period' => 'day'
            ];
        }
        
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.operations-sales-chart');
    }

    public function handlePeriodChange($data)
    {
        \Log::info('OperationsSalesChart: получено событие period-changed', $data);
        
        // Сохраняем и период и данные периода
        if (isset($data['periodData']) && is_array($data['periodData'])) {
            $this->periodData = $data['periodData'];
            $this->periodData['period'] = $data['period'] ?? 'unknown'; // Добавляем период
        } else {
            $this->periodData = ['period' => $data['period'] ?? 'unknown'];
        }
        
        $this->loadData();
        
        // Отправляем обновленные данные графика на фронтенд
        $this->dispatch('operations-sales-chart-data-updated', [
            'chartData' => $this->chartData,
            'salesCount' => $this->salesCount,
            'purchasesCount' => $this->purchasesCount,
            'salesAmount' => $this->salesAmount,
            'purchasesAmount' => $this->purchasesAmount,
            'profitMargin' => $this->profitMargin,
            'period' => $data['period'] ?? 'unknown'
        ]);
    }

    protected function getPeriodDates()
    {
        // Если есть данные от центрального селектора - используем их
        if (!empty($this->periodData) && is_array($this->periodData) && 
            isset($this->periodData['start']) && isset($this->periodData['end']) && isset($this->periodData['label'])) {
            return [
                'start' => \Carbon\Carbon::parse($this->periodData['start']),
                'end' => \Carbon\Carbon::parse($this->periodData['end']),
                'label' => $this->periodData['label']
            ];
        }
        
        // Если данных нет - возвращаем null, не загружаем данные
        return null;
    }

    protected function loadData()
    {
        $period = $this->getPeriodDates();
        
        // Если нет данных периода - не загружаем
        if (!$period) {
            \Log::info('OperationsSalesChart: нет данных периода, пропускаем загрузку');
            return;
        }
        
        $selectedPeriod = ($this->periodData && isset($this->periodData['period'])) ? $this->periodData['period'] : 'unknown';
        
        // Получаем операции за выбранный период
        $operations = Operation::whereBetween('created_at', [$period['start'], $period['end']])
            ->orderBy('created_at')
            ->get();

        // Отладка: логируем период и операции
        \Log::info('OperationsSalesChart загрузка данных:', [
            'selected_period' => $selectedPeriod,
            'period_start' => $period['start']->format('Y-m-d H:i:s'),
            'period_end' => $period['end']->format('Y-m-d H:i:s'),
            'operations_count' => $operations->count(),
            'operations_total' => $operations->sum('total_amount'),
            'first_operation' => $operations->first() ? $operations->first()->created_at->format('Y-m-d H:i:s') : 'нет',
            'last_operation' => $operations->last() ? $operations->last()->created_at->format('Y-m-d H:i:s') : 'нет'
        ]);

        // Разделяем на продажи и покупки
        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        // Рассчитываем показатели
        $this->salesCount = $sales->count();
        $this->purchasesCount = $purchases->count();
        $this->salesAmount = $sales->sum('total_amount');
        $this->purchasesAmount = $purchases->sum('total_amount');

        // Отладка: логируем разделение на типы
        \Log::info('OperationsSalesChart разделение операций:', [
            'sales_count' => $this->salesCount,
            'purchases_count' => $this->purchasesCount,
            'sales_amount' => $this->salesAmount,
            'purchases_amount' => $this->purchasesAmount
        ]);

        // Рассчитываем маржу
        if ($this->purchasesAmount > 0) {
            $this->profitMargin = (($this->salesAmount - $this->purchasesAmount) / $this->purchasesAmount) * 100;
        } else {
            $this->profitMargin = $this->salesAmount > 0 ? 100 : 0;
        }

        // Подготавливаем данные для графика
        $this->prepareChartData($operations, $period, $selectedPeriod);
    }
    
    private function createTestData()
    {
        \Log::info('OperationsSalesChart: создаём тестовые данные');
        
        $this->chartData = [
            'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            'datasets' => [
                [
                    'label' => 'Продажи',
                    'data' => [500, 1200, 1800, 2500, 1900, 1100],
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Покупки',
                    'data' => [300, 800, 1000, 1500, 1200, 700],
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ]
            ]
        ];
        
        // Обновляем тестовые показатели
        $this->salesCount = 15;
        $this->purchasesCount = 12;
        $this->salesAmount = 8500;
        $this->purchasesAmount = 6200;
        $this->profitMargin = 37.1;
        
        \Log::info('OperationsSalesChart: тестовые данные созданы', [
            'labels_count' => count($this->chartData['labels']),
            'datasets_count' => count($this->chartData['datasets']),
            'sales_amount' => $this->salesAmount,
            'purchases_amount' => $this->purchasesAmount
        ]);
    }

    private function prepareChartData($operations, $period, $selectedPeriod)
    {
        $labels = [];
        $salesData = [];
        $purchasesData = [];
        
        switch ($selectedPeriod) {
            case 'day':
                // Группируем по часам
                for ($hour = 0; $hour < 24; $hour++) {
                    $labels[] = sprintf('%02d:00', $hour);
                    
                    $hourOperations = $operations->filter(function ($operation) use ($hour) {
                        return $operation->created_at->hour == $hour;
                    });
                    
                    $salesData[] = (float) $hourOperations->where('type', 'sale')->sum('total_amount');
                    $purchasesData[] = (float) $hourOperations->where('type', 'purchase')->sum('total_amount');
                }
                break;
                
            case 'week':
                // Группируем по дням недели
                $start = $period['start']->copy();
                for ($i = 0; $i < 7; $i++) {
                    $currentDay = $start->copy()->addDays($i);
                    $labels[] = $currentDay->format('d.m');
                    
                    $dayOperations = $operations->filter(function ($operation) use ($currentDay) {
                        return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
                    });
                    
                    $salesData[] = (float) $dayOperations->where('type', 'sale')->sum('total_amount');
                    $purchasesData[] = (float) $dayOperations->where('type', 'purchase')->sum('total_amount');
                }
                break;
                
            case 'month':
            case 'custom':
                // Группируем по дням или неделям в зависимости от длительности
                $start = $period['start']->copy();
                $end = $period['end']->copy();
                $daysDiff = $start->diffInDays($end) + 1;
                
                if ($daysDiff > 31) {
                    // Группируем по неделям
                    $currentWeek = $start->copy()->startOfWeek();
                    while ($currentWeek->lte($end)) {
                        $weekEnd = $currentWeek->copy()->endOfWeek();
                        if ($weekEnd->gt($end)) {
                            $weekEnd = $end->copy();
                        }
                        
                        $labels[] = $currentWeek->format('d.m') . '-' . $weekEnd->format('d.m');
                        
                        $weekOperations = $operations->filter(function ($operation) use ($currentWeek, $weekEnd) {
                            return $operation->created_at->between($currentWeek, $weekEnd);
                        });
                        
                        $salesData[] = (float) $weekOperations->where('type', 'sale')->sum('total_amount');
                        $purchasesData[] = (float) $weekOperations->where('type', 'purchase')->sum('total_amount');
                        
                        $currentWeek->addWeek();
                    }
                } else {
                    // Группируем по дням
                    for ($i = 0; $i < $daysDiff; $i++) {
                        $currentDay = $start->copy()->addDays($i);
                        $labels[] = $currentDay->format('d.m');
                        
                        $dayOperations = $operations->filter(function ($operation) use ($currentDay) {
                            return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
                        });
                        
                        $salesData[] = (float) $dayOperations->where('type', 'sale')->sum('total_amount');
                        $purchasesData[] = (float) $dayOperations->where('type', 'purchase')->sum('total_amount');
                    }
                }
                break;
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Продажи',
                    'data' => $salesData,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Покупки',
                    'data' => $purchasesData,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ]
            ]
        ];

        // Отладка: логируем финальные данные графика
        \Log::info('OperationsSalesChart финальные данные графика:', [
            'period' => $selectedPeriod,
            'labels_count' => count($labels),
            'first_5_labels' => array_slice($labels, 0, 5),
            'sales_data_sum' => array_sum($salesData),
            'purchases_data_sum' => array_sum($purchasesData),
            'first_5_sales' => array_slice($salesData, 0, 5),
            'first_5_purchases' => array_slice($purchasesData, 0, 5)
        ]);
    }
} 