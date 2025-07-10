<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use App\Models\CashTransaction;
use Livewire\Component;
use Carbon\Carbon;

class FinancialStats extends Component
{
    public $period = 'month';
    public $periodLabel = 'За месяц';
    public $startDate;
    public $endDate;
    
    public $totalRevenue = 0;
    public $totalProfit = 0;
    public $profitMargin = 0;
    public $averageCheck = 0;
    public $cashBalance = 0;
    public $salesRevenue = 0;
    public $purchasesCost = 0;

    public function mount()
    {
        // Читаем период из URL параметров или используем по умолчанию
        $this->period = request('period', 'month');
        
        $this->setPeriodDates();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.financial-stats');
    }

    private function loadData()
    {
        // Получаем операции за период
        $operations = Operation::whereBetween('created_at', [$this->startDate, $this->endDate])->get();
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

        // Баланс кассы
        $this->cashBalance = CashTransaction::sum('amount');
    }
    
    private function setPeriodDates()
    {
        $lastOperation = \App\Models\Operation::latest()->first();
        $now = $lastOperation ? Carbon::parse($lastOperation->created_at) : Carbon::now();
        
        switch ($this->period) {
            case 'day':
                $this->startDate = $now->copy()->startOfDay();
                $this->endDate = $now->copy()->endOfDay();
                $this->periodLabel = 'За день';
                break;
                
            case 'week':
                $this->startDate = $now->copy()->startOfWeek();
                $this->endDate = $now->copy()->endOfWeek();
                $this->periodLabel = 'За неделю';
                break;
                
            case 'month':
            default:
                $this->startDate = $now->copy()->startOfMonth();
                $this->endDate = $now->copy()->endOfMonth();
                $this->periodLabel = 'За месяц';
                break;
        }
    }
} 