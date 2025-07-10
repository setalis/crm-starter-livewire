<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OperationsSalesChart extends Component
{
    public $period = 'month';
    public $periodLabel = 'Месяц';
    public $startDate;
    public $endDate;
    public $chartData = [];
    public $totalOperations = 0;
    public $salesCount = 0;
    public $purchasesCount = 0;
    public $profitMargin = 0;
    
    public function mount()
    {
        // Читаем период из URL параметров или используем по умолчанию
        $this->period = request('period', 'month');
        
        $this->setDefaultPeriod();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.operations-sales-chart');
    }

    private function setDefaultPeriod()
    {
        $lastOperation = Operation::latest()->first();
        $now = $lastOperation ? Carbon::parse($lastOperation->created_at) : Carbon::now();

        switch ($this->period) {
            case 'day':
                $this->startDate = $now->copy()->startOfDay();
                $this->endDate = $now->copy()->endOfDay();
                $this->periodLabel = $now->format('d F Y');
                break;
                
            case 'week':
                $this->startDate = $now->copy()->startOfWeek();
                $this->endDate = $now->copy()->endOfWeek();
                $this->periodLabel = 'Неделя (' . $now->format('W') . ')';
                break;
                
            case 'month':
            default:
                $this->startDate = $now->copy()->startOfMonth();
                $this->endDate = $now->copy()->endOfMonth();
                $this->periodLabel = $now->format('F Y');
                break;
        }
    }

    private function loadData()
    {
        $operations = Operation::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at')
            ->get();

        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        $this->totalOperations = $operations->count();
        $this->salesCount = $sales->count();
        $this->purchasesCount = $purchases->count();

        $salesAmount = $sales->sum('total_amount');
        $purchasesAmount = $purchases->sum('total_amount');

        $this->profitMargin = $purchasesAmount > 0 ? (($salesAmount - $purchasesAmount) / $purchasesAmount) * 100 : 0;

        $this->prepareChartData($operations);
    }

    private function prepareChartData($operations)
    {
        $labels = [];
        $salesData = [];
        $purchasesData = [];

        if ($this->period === 'day') {
            // Для дня группируем по часам
            $groupedOperations = $operations->groupBy(function ($operation) {
                return $operation->created_at->format('H');
            });

            for ($hour = 0; $hour < 24; $hour++) {
                $hourString = sprintf('%02d', $hour);
                $labels[] = $hourString . ':00';
                
                $dayOperations = $groupedOperations[$hourString] ?? collect();
                
                $salesData[] = (float) $dayOperations->where('type', 'sale')->sum('total_amount');
                $purchasesData[] = (float) $dayOperations->where('type', 'purchase')->sum('total_amount');
            }
        } else {
            // Для недели и месяца группируем по дням
            $groupedOperations = $operations->groupBy(function ($operation) {
                return $operation->created_at->format('Y-m-d');
            });

            $period = CarbonPeriod::create($this->startDate, $this->endDate);

            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                $labels[] = $date->format('d.m');
                
                $dayOperations = $groupedOperations[$dateString] ?? collect();
                
                $salesData[] = (float) $dayOperations->where('type', 'sale')->sum('total_amount');
                $purchasesData[] = (float) $dayOperations->where('type', 'purchase')->sum('total_amount');
            }
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Продажи',
                    'data' => $salesData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Покупки',
                    'data' => $purchasesData,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ]
            ]
        ];
    }
} 