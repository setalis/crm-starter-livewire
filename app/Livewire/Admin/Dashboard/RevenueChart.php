<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RevenueChart extends Component
{
    public $period = 'month';
    public $periodLabel = 'Месяц';
    public $startDate;
    public $endDate;
    public $chartData = [];
    public $totalRevenue = 0;
    public $averageCheck = 0;
    public $totalProfit = 0;
    public $salesRevenue = 0;
    public $purchasesCost = 0;
    
    public function mount()
    {
        // Читаем период из URL параметров или используем по умолчанию
        $this->period = request('period', 'month');
        
        $this->setDefaultPeriod();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.revenue-chart');
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
        // Оборот = все операции (продажи + покупки)
        $operations = Operation::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at')
            ->get();

        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        // Основные показатели
        $this->salesRevenue = $sales->sum('total_amount');
        $this->purchasesCost = $purchases->sum('total_amount');
        $this->totalRevenue = $operations->sum('total_amount');
        $this->totalProfit = $this->salesRevenue - $this->purchasesCost;

        // Средний чек
        $totalOperationsCount = $operations->count();
        $this->averageCheck = $totalOperationsCount > 0 ? $this->totalRevenue / $totalOperationsCount : 0;

        $this->prepareChartData($operations);
    }

    private function prepareChartData($operations)
    {
        $labels = [];
        $revenueData = [];
        $profitData = [];
        $averageCheckData = [];
        
        if ($this->period === 'day') {
            // Для дня группируем по часам
            $groupedOperations = $operations->groupBy(function ($operation) {
                return $operation->created_at->format('H');
            });

            for ($hour = 0; $hour < 24; $hour++) {
                $hourString = sprintf('%02d', $hour);
                $labels[] = $hourString . ':00';
                
                $hourOperations = $groupedOperations[$hourString] ?? collect();
                $hourSales = $hourOperations->where('type', 'sale');
                $hourPurchases = $hourOperations->where('type', 'purchase');
                
                $hourRevenue = $hourOperations->sum('total_amount');
                $hourProfit = $hourSales->sum('total_amount') - $hourPurchases->sum('total_amount');
                $hourAvgCheck = $hourOperations->count() > 0 ? $hourRevenue / $hourOperations->count() : 0;
                
                $revenueData[] = (float) $hourRevenue;
                $profitData[] = (float) $hourProfit;
                $averageCheckData[] = (float) $hourAvgCheck;
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
                $daySales = $dayOperations->where('type', 'sale');
                $dayPurchases = $dayOperations->where('type', 'purchase');
                
                $dayRevenue = $dayOperations->sum('total_amount');
                $dayProfit = $daySales->sum('total_amount') - $dayPurchases->sum('total_amount');
                $dayAvgCheck = $dayOperations->count() > 0 ? $dayRevenue / $dayOperations->count() : 0;
                
                $revenueData[] = (float) $dayRevenue;
                $profitData[] = (float) $dayProfit;
                $averageCheckData[] = (float) $dayAvgCheck;
            }
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Оборот',
                    'data' => $revenueData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Прибыль',
                    'data' => $profitData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Средний чек',
                    'data' => $averageCheckData,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'yAxisID' => 'y1'
                ]
            ]
        ];
    }
} 