<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use App\Models\CashTransaction;
use Livewire\Component;

class FinancialStats extends Component
{
    protected $listeners = ['period-changed' => 'handlePeriodChange'];

    public $periodData = [];
    public $totalRevenue = 0;
    public $totalProfit = 0;
    public $profitMargin = 0;
    public $averageCheck = 0;
    public $cashBalance = 0;
    public $salesRevenue = 0;
    public $purchasesCost = 0;
    public $revenueChange = 0;
    public $profitChange = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.financial-stats');
    }

    public function handlePeriodChange($data)
    {
        \Log::info('FinancialStats: получено событие period-changed', $data);
        
        // Сохраняем и период и данные периода
        if (isset($data['periodData']) && is_array($data['periodData'])) {
            $this->periodData = $data['periodData'];
            $this->periodData['period'] = $data['period'] ?? 'unknown'; // Добавляем период
        } else {
            $this->periodData = ['period' => $data['period'] ?? 'unknown'];
        }
        
        $this->loadData();
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
            \Log::info('FinancialStats: нет данных периода, пропускаем загрузку');
            return;
        }
        
        // Получаем операции за выбранный период
        $operations = Operation::whereBetween('created_at', [$period['start'], $period['end']])->get();
        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        // Основные показатели
        $this->salesRevenue = $sales->sum('total_amount');
        $this->purchasesCost = $purchases->sum('total_amount');
        $this->totalRevenue = $operations->sum('total_amount');
        $this->totalProfit = $this->salesRevenue - $this->purchasesCost;

        // Маржа
        if ($this->purchasesCost > 0) {
            $this->profitMargin = ($this->totalProfit / $this->purchasesCost) * 100;
        } else {
            $this->profitMargin = $this->salesRevenue > 0 ? 100 : 0;
        }

        // Средний чек
        $totalOperationsCount = $operations->count();
        $this->averageCheck = $totalOperationsCount > 0 ? $this->totalRevenue / $totalOperationsCount : 0;

        // Баланс кассы (общий)
        $this->cashBalance = CashTransaction::sum('amount');

        // Изменения относительно предыдущего периода
        $this->calculateChanges($period);
    }

    private function calculateChanges($currentPeriod)
    {
        // Рассчитываем предыдущий период
        $duration = $currentPeriod['end']->diff($currentPeriod['start']);
        $prevStart = $currentPeriod['start']->copy()->sub($duration);
        $prevEnd = $currentPeriod['start']->copy()->subSecond();

        $prevOperations = Operation::whereBetween('created_at', [$prevStart, $prevEnd])->get();
        $prevSales = $prevOperations->where('type', 'sale');
        $prevPurchases = $prevOperations->where('type', 'purchase');
        
        $prevRevenue = $prevOperations->sum('total_amount');
        $prevProfit = $prevSales->sum('total_amount') - $prevPurchases->sum('total_amount');

        // Изменение оборота
        if ($prevRevenue > 0) {
            $this->revenueChange = (($this->totalRevenue - $prevRevenue) / $prevRevenue) * 100;
        } else {
            $this->revenueChange = $this->totalRevenue > 0 ? 100 : 0;
        }

        // Изменение прибыли
        if ($prevProfit > 0) {
            $this->profitChange = (($this->totalProfit - $prevProfit) / $prevProfit) * 100;
        } else {
            $this->profitChange = $this->totalProfit > 0 ? 100 : 0;
        }
    }
} 