<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use App\Models\User;
use Livewire\Component;

class OperationalStats extends Component
{
    protected $listeners = ['period-changed' => 'handlePeriodChange'];

    public $periodData = [];
    public $totalOperations = 0;
    public $salesCount = 0;
    public $purchasesCount = 0;
    public $averageCheck = 0;
    public $averageSaleCheck = 0;
    public $averagePurchaseCheck = 0;
    public $topUsers = [];
    public $operationsChange = 0;

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
        return view('livewire.admin.dashboard.operational-stats');
    }

    public function handlePeriodChange($data)
    {
        \Log::info('OperationalStats: получено событие period-changed', $data);
        
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
            \Log::info('OperationalStats: нет данных периода, пропускаем загрузку');
            return;
        }
        
        // Получаем операции за выбранный период
        $operations = Operation::with('user')
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->get();

        $sales = $operations->where('type', 'sale');
        $purchases = $operations->where('type', 'purchase');

        // Основные показатели
        $this->totalOperations = $operations->count();
        $this->salesCount = $sales->count();
        $this->purchasesCount = $purchases->count();

        // Средние чеки
        $totalAmount = $operations->sum('total_amount');
        $this->averageCheck = $this->totalOperations > 0 ? $totalAmount / $this->totalOperations : 0;
        
        $salesAmount = $sales->sum('total_amount');
        $this->averageSaleCheck = $this->salesCount > 0 ? $salesAmount / $this->salesCount : 0;
        
        $purchasesAmount = $purchases->sum('total_amount');
        $this->averagePurchaseCheck = $this->purchasesCount > 0 ? $purchasesAmount / $this->purchasesCount : 0;

        // Топ пользователи по количеству операций
        $this->topUsers = $operations->groupBy('user_id')
            ->map(function ($userOperations) {
                $user = $userOperations->first()->user;
                return [
                    'name' => $user->name,
                    'operations_count' => $userOperations->count(),
                    'total_amount' => $userOperations->sum('total_amount')
                ];
            })
            ->sortByDesc('operations_count')
            ->take(5)
            ->values()
            ->toArray();

        // Изменение количества операций
        $this->calculateOperationsChange($period);
    }

    private function calculateOperationsChange($currentPeriod)
    {
        // Рассчитываем предыдущий период
        $duration = $currentPeriod['end']->diff($currentPeriod['start']);
        $prevStart = $currentPeriod['start']->copy()->sub($duration);
        $prevEnd = $currentPeriod['start']->copy()->subSecond();

        $prevOperationsCount = Operation::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        if ($prevOperationsCount > 0) {
            $this->operationsChange = (($this->totalOperations - $prevOperationsCount) / $prevOperationsCount) * 100;
        } else {
            $this->operationsChange = $this->totalOperations > 0 ? 100 : 0;
        }
    }
} 