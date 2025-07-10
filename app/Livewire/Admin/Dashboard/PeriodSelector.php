<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;

class PeriodSelector extends Component
{
    public $period = 'month';
    public $periodLabel = '';
    public $dateRange = '';
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Читаем период из URL параметров или используем по умолчанию
        $this->period = request('period', 'month');
        
        $this->updatePeriod();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.period-selector');
    }

    public function updatedPeriod()
    {
        \Log::info('PERIOD SELECTOR: Период изменен на', ['period' => $this->period]);
        
        // Перенаправляем с новым периодом - полная перезагрузка страницы
        return redirect()->route('dashboard', ['period' => $this->period]);
    }

    private function updatePeriod()
    {
        // Отталкиваемся от даты последней операции, чтобы всегда были релевантные данные
        $lastOperation = Operation::latest()->first();
        $now = $lastOperation ? Carbon::parse($lastOperation->created_at) : Carbon::now();
        
        // Устанавливаем русскую локаль
        $now->locale('ru');
        
        switch ($this->period) {
            case 'day':
                $this->startDate = $now->copy()->startOfDay();
                $this->endDate = $now->copy()->endOfDay();
                $this->periodLabel = 'За день';
                $this->dateRange = $now->format('d') . ' ' . $now->translatedFormat('F');
                break;
                
            case 'week':
                $this->startDate = $now->copy()->startOfWeek();
                $this->endDate = $now->copy()->endOfWeek();
                $this->periodLabel = 'За неделю';
                $this->dateRange = $this->startDate->format('d.m') . ' - ' . $this->endDate->format('d.m');
                break;
                
            case 'month':
            default:
                $this->startDate = $now->copy()->startOfMonth();
                $this->endDate = $now->copy()->endOfMonth();
                $this->periodLabel = 'За месяц';
                $this->dateRange = $now->translatedFormat('F Y');
                break;
        }
    }

    private function dispatchPeriodChange()
    {
        $this->dispatch('period-changed', [
            'period' => $this->period,
            'start' => $this->startDate->toDateTimeString(),
            'end' => $this->endDate->toDateTimeString(),
            'label' => $this->periodLabel
        ]);
    }
} 