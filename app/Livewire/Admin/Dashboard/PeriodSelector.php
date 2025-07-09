<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class PeriodSelector extends Component
{
    public $selectedPeriod = 'day';

    public function mount()
    {
        // Отправляем начальный период при загрузке
        $this->dispatch('period-changed', [
            'period' => $this->selectedPeriod,
            'periodData' => $this->getPeriodDates()
        ]);
    }

    public function render()
    {
        return view('livewire.admin.dashboard.period-selector');
    }

    public function updatedSelectedPeriod()
    {
        $periodData = $this->getPeriodDates();
        
        // Отладка
        \Log::info('PeriodSelector: отправляем событие period-changed', [
            'period' => $this->selectedPeriod,
            'periodData' => $periodData
        ]);
        
        // Отправляем событие всем виджетам о смене периода
        $this->dispatch('period-changed', [
            'period' => $this->selectedPeriod,
            'periodData' => $periodData
        ]);
    }

    protected function getPeriodDates()
    {
        $now = now();
        
        switch ($this->selectedPeriod) {
            case 'day':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => 'За сегодня'
                ];
            
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                    'label' => 'За неделю'
                ];
            
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'label' => 'За месяц'
                ];
                
            default:
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => 'За сегодня'
                ];
        }
    }

    protected function getPeriodOptions()
    {
        return [
            'day' => 'День',
            'week' => 'Неделя', 
            'month' => 'Месяц'
        ];
    }
} 