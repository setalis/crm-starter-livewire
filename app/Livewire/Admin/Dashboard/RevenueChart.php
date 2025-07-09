<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;

class RevenueChart extends Component
{
    protected $listeners = ['period-changed' => 'handlePeriodChange'];

    public $periodData = [];
    public $chartData = [];
    public $totalRevenue = 0;
    public $revenueChange = 0;

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
        return view('livewire.admin.dashboard.revenue-chart');
    }

    public function handlePeriodChange($data)
    {
        \Log::info('RevenueChart: получено событие period-changed', $data);
        
        // Сохраняем и период и данные периода
        if (isset($data['periodData']) && is_array($data['periodData'])) {
            $this->periodData = $data['periodData'];
            $this->periodData['period'] = $data['period'] ?? 'unknown'; // Добавляем период
        } else {
            $this->periodData = ['period' => $data['period'] ?? 'unknown'];
        }
        
        $this->loadData();
        
        // Отправляем обновленные данные графика на фронтенд
        $this->dispatch('revenue-chart-data-updated', [
            'chartData' => $this->chartData,
            'totalRevenue' => $this->totalRevenue,
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
            \Log::info('RevenueChart: нет данных периода, пропускаем загрузку');
            return;
        }
        
        $selectedPeriod = ($this->periodData && isset($this->periodData['period'])) ? $this->periodData['period'] : 'unknown';
        
        \Log::info('RevenueChart: загружаем данные', [
            'selected_period' => $selectedPeriod,
            'period_start' => $period['start']->format('Y-m-d H:i:s'),
            'period_end' => $period['end']->format('Y-m-d H:i:s'),
            'period_label' => $period['label']
        ]);
        
        // Получаем операции за выбранный период
        $operations = Operation::whereBetween('created_at', [$period['start'], $period['end']])
            ->orderBy('created_at')
            ->get();
            
        \Log::info('RevenueChart: операции найдены', [
            'operations_count' => $operations->count(),
            'total_amount' => $operations->sum('total_amount'),
            'first_operation' => $operations->first() ? $operations->first()->created_at->format('Y-m-d H:i:s') : 'нет',
            'last_operation' => $operations->last() ? $operations->last()->created_at->format('Y-m-d H:i:s') : 'нет'
        ]);

        // Рассчитываем общий оборот
        $this->totalRevenue = $operations->sum('total_amount');

        // Рассчитываем изменение относительно предыдущего периода
        $this->calculateRevenueChange($period);

        // Подготавливаем данные для графика
        $this->prepareChartData($operations, $period, $selectedPeriod);
        
        // Отладка: проверим итоговые данные
        \Log::info('RevenueChart финальные данные:', [
            'period' => $selectedPeriod,
            'operations_count' => $operations->count(),
            'total_revenue' => $this->totalRevenue,
            'labels_count' => count($this->chartData['labels'] ?? []),
            'data_count' => count($this->chartData['datasets'][0]['data'] ?? []),
            'data_sum' => array_sum($this->chartData['datasets'][0]['data'] ?? []),
            'first_5_labels' => array_slice($this->chartData['labels'] ?? [], 0, 5),
            'first_5_data' => array_slice($this->chartData['datasets'][0]['data'] ?? [], 0, 5)
        ]);
    }
    
    private function createTestData()
    {
        \Log::info('RevenueChart: создаём тестовые данные');
        
        $this->chartData = [
            'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            'datasets' => [
                [
                    'label' => 'Оборот',
                    'data' => [1000, 2500, 3200, 5000, 3800, 2200],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
        
        // Обновляем также показатели
        $this->totalRevenue = array_sum($this->chartData['datasets'][0]['data']);
        $this->revenueChange = 25.5; // Тестовое значение
        
        \Log::info('RevenueChart: тестовые данные созданы', [
            'labels_count' => count($this->chartData['labels']),
            'data_count' => count($this->chartData['datasets'][0]['data']),
            'total_revenue' => $this->totalRevenue
        ]);
    }

    private function calculateRevenueChange($currentPeriod)
    {
        // Рассчитываем предыдущий период
        $duration = $currentPeriod['end']->diff($currentPeriod['start']);
        $prevStart = $currentPeriod['start']->copy()->sub($duration);
        $prevEnd = $currentPeriod['start']->copy()->subSecond();

        $previousRevenue = Operation::whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total_amount');

        if ($previousRevenue > 0) {
            $this->revenueChange = (($this->totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        } else {
            $this->revenueChange = $this->totalRevenue > 0 ? 100 : 0;
        }
    }

    private function prepareChartData($operations, $period, $selectedPeriod)
    {
        \Log::info('RevenueChart: начинаем формирование данных графика', [
            'selected_period' => $selectedPeriod,
            'operations_count' => $operations->count()
        ]);
        
        $labels = [];
        $data = [];
        
        switch ($selectedPeriod) {
            case 'day':
                // Группируем по часам
                for ($hour = 0; $hour < 24; $hour++) {
                    $labels[] = sprintf('%02d:00', $hour);
                    $hourRevenue = $operations->filter(function ($operation) use ($hour) {
                        return $operation->created_at->hour == $hour;
                    })->sum('total_amount');
                    $data[] = (float) $hourRevenue;
                }
                break;
                
            case 'week':
                // Группируем по дням недели
                $start = $period['start']->copy();
                for ($i = 0; $i < 7; $i++) {
                    $currentDay = $start->copy()->addDays($i);
                    $labels[] = $currentDay->format('d.m');
                    
                    $dayRevenue = $operations->filter(function ($operation) use ($currentDay) {
                        return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
                    })->sum('total_amount');
                    $data[] = (float) $dayRevenue;
                }
                break;
                
            case 'month':
            case 'custom':
                // Группируем по дням
                $start = $period['start']->copy();
                $end = $period['end']->copy();
                $daysDiff = $start->diffInDays($end) + 1;
                
                // Если период больше 31 дня, группируем по неделям
                if ($daysDiff > 31) {
                    $currentWeek = $start->copy()->startOfWeek();
                    while ($currentWeek->lte($end)) {
                        $weekEnd = $currentWeek->copy()->endOfWeek();
                        if ($weekEnd->gt($end)) {
                            $weekEnd = $end->copy();
                        }
                        
                        $labels[] = $currentWeek->format('d.m') . '-' . $weekEnd->format('d.m');
                        
                        $weekRevenue = $operations->filter(function ($operation) use ($currentWeek, $weekEnd) {
                            return $operation->created_at->between($currentWeek, $weekEnd);
                        })->sum('total_amount');
                        $data[] = (float) $weekRevenue;
                        
                        $currentWeek->addWeek();
                    }
                } else {
                    // Группируем по дням
                    for ($i = 0; $i < $daysDiff; $i++) {
                        $currentDay = $start->copy()->addDays($i);
                        $labels[] = $currentDay->format('d.m');
                        
                        $dayRevenue = $operations->filter(function ($operation) use ($currentDay) {
                            return $operation->created_at->format('Y-m-d') == $currentDay->format('Y-m-d');
                        })->sum('total_amount');
                        $data[] = (float) $dayRevenue;
                    }
                }
                break;
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Оборот',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
        
        \Log::info('RevenueChart: данные графика сформированы', [
            'labels_count' => count($labels),
            'data_count' => count($data),
            'data_sum' => array_sum($data),
            'first_3_labels' => array_slice($labels, 0, 3),
            'first_3_data' => array_slice($data, 0, 3),
            'last_3_labels' => array_slice($labels, -3),
            'last_3_data' => array_slice($data, -3)
        ]);
    }
} 