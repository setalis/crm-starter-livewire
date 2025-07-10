<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class OperationalStats extends Component
{
    public $period = 'month';
    public $periodLabel = 'За месяц';
    public $startDate;
    public $endDate;
    
    public $totalOperations = 0;
    public $salesCount = 0;
    public $purchasesCount = 0;
    public $averageCheck = 0;
    public $averageSaleCheck = 0;
    public $averagePurchaseCheck = 0;
    public $topUsers = [];

    public function mount()
    {
        // Читаем период из URL параметров или используем по умолчанию
        $this->period = request('period', 'month');
        
        $this->setPeriodDates();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.operational-stats');
    }

    private function setPeriodDates()
    {
        $lastOperation = Operation::latest()->first();
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

    private function loadData()
    {
        // Получаем операции за период
        $operations = Operation::with('user')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        // Основные показатели
        $this->totalOperations = $operations->count();
        $this->salesCount = $sales->count();
        $this->purchasesCount = $purchases->count();

        // Средние чеки
        $this->averageCheck = $this->totalOperations > 0 ? $operations->sum('total_amount') / $this->totalOperations : 0;
        $this->averageSaleCheck = $this->salesCount > 0 ? $sales->sum('total_amount') / $this->salesCount : 0;
        $this->averagePurchaseCheck = $this->purchasesCount > 0 ? $purchases->sum('total_amount') / $this->purchasesCount : 0;

        // Топ пользователи
        $this->topUsers = $operations->groupBy('user_id')
            ->map(function ($userOperations) {
                $user = $userOperations->first()->user;
                return [
                    'name' => $user->name,
                    'count' => $userOperations->count(),
                    'amount' => $userOperations->sum('total_amount')
                ];
            })
            ->sortByDesc('amount')
            ->take(3)
            ->values()
            ->toArray();
    }
} 