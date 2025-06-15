<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;

class OperationsWidget extends Component
{
    public $recentOperations;
    public $todayStats;
    public $monthStats;

    public function mount()
    {
        $this->loadOperations();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.operations-widget');
    }

    public function loadOperations()
    {
        // Последние операции
        $this->recentOperations = Operation::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // Статистика за сегодня
        $today = Carbon::today();
        $todayOperations = Operation::whereDate('created_at', $today)->get();
        
        $this->todayStats = [
            'total' => $todayOperations->count(),
            'purchases' => $todayOperations->where('type', 'purchase')->count(),
            'sales' => $todayOperations->where('type', 'sale')->count(),
            'amount' => $todayOperations->sum('total_amount'),
        ];

        // Статистика за месяц
        $monthStart = Carbon::now()->startOfMonth();
        $monthOperations = Operation::where('created_at', '>=', $monthStart)->get();
        
        $this->monthStats = [
            'total' => $monthOperations->count(),
            'purchases' => $monthOperations->where('type', 'purchase')->count(),
            'sales' => $monthOperations->where('type', 'sale')->count(),
            'amount' => $monthOperations->sum('total_amount'),
        ];
    }
}
