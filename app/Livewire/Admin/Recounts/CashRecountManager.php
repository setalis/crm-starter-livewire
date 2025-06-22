<?php

namespace App\Livewire\Admin\Recounts;

use App\Models\CashRecount;
use App\Models\CashRegister;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CashRecountManager extends Component
{
    use WithPagination;

    public $isModal = false;
    public $isViewModal = false;
    public $isCompleteModal = false;
    
    public $selectedRecount = null;
    
    // Создание переучета
    public $form = [
        'cash_register_id' => null,
        'reason' => '',
        'notes' => '',
    ];
    
    // Завершение переучета
    public $completeForm = [
        'actual_balance' => null,
    ];

    public function mount()
    {
        $this->resetForm();
        $this->isModal = false;
        $this->isViewModal = false;
        $this->isCompleteModal = false;
    }

    public function render()
    {
        $recounts = CashRecount::with(['cashRegister', 'user'])
            ->latest()
            ->paginate(10);

        $cashRegisters = CashRegister::where('is_active', true)->get();

        return view('livewire.admin.recounts.cash-recount-manager', compact('recounts', 'cashRegisters'));
    }

    public function createRecount()
    {
        if (!auth()->user()->can('cash_recounts.create')) {
            session()->flash('error', 'Недостаточно прав для создания переучетов кассы');
            return;
        }

        $this->validate([
            'form.cash_register_id' => 'required|exists:cash_registers,id',
            'form.reason' => 'required|string|max:1000',
        ]);

        $cashRegister = CashRegister::find($this->form['cash_register_id']);

        $recount = CashRecount::create([
            'number' => CashRecount::generateNumber(),
            'cash_register_id' => $this->form['cash_register_id'],
            'user_id' => Auth::id(),
            'expected_balance' => $cashRegister->balance,
            'reason' => $this->form['reason'],
            'notes' => $this->form['notes'],
        ]);

        $this->isModal = false;
        $this->resetForm();
        
        session()->flash('message', 'Переучет кассы создан успешно');
    }

    public function viewRecount($id)
    {
        $this->selectedRecount = CashRecount::with(['cashRegister', 'user'])->find($id);
        $this->isViewModal = true;
    }

    public function startRecount($id)
    {
        if (!auth()->user()->can('cash_recounts.edit')) {
            session()->flash('error', 'Недостаточно прав для редактирования переучетов кассы');
            return;
        }

        $recount = CashRecount::find($id);
        $recount->start();
        
        // Автоматически открываем модальное окно для ввода фактического баланса
        $this->showCompleteModal($id);
        
        session()->flash('message', 'Переучет кассы начат. Введите фактический баланс.');
    }

    public function showCompleteModal($id)
    {
        $this->selectedRecount = CashRecount::find($id);
        $this->completeForm = [
            'actual_balance' => $this->selectedRecount->actual_balance,
        ];
        $this->isCompleteModal = true;
    }

    public function completeRecount()
    {
        if (!auth()->user()->can('cash_recounts.complete')) {
            session()->flash('error', 'Недостаточно прав для завершения переучетов кассы');
            return;
        }

        $this->validate([
            'completeForm.actual_balance' => 'required|numeric|min:0',
        ]);

        try {
            $wasAlreadyStarted = $this->selectedRecount->actual_balance !== null;
            
            $this->selectedRecount->update([
                'actual_balance' => $this->completeForm['actual_balance']
            ]);
            
            $this->selectedRecount->complete();
            
            $this->isCompleteModal = false;
            $this->selectedRecount = null;
            
            if ($wasAlreadyStarted) {
                session()->flash('message', 'Данные переучета кассы обновлены и переучет завершен');
            } else {
                session()->flash('message', 'Переучет кассы завершен');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancelRecount($id)
    {
        if (!auth()->user()->can('cash_recounts.cancel')) {
            session()->flash('error', 'Недостаточно прав для отмены переучетов кассы');
            return;
        }

        $recount = CashRecount::find($id);
        $recount->cancel();
        
        session()->flash('message', 'Переучет кассы отменен');
    }

    public function deleteRecount($id)
    {
        if (!auth()->user()->can('cash_recounts.delete')) {
            session()->flash('error', 'Недостаточно прав для удаления переучетов кассы');
            return;
        }

        $recount = CashRecount::find($id);
        $number = $recount->number;
        $recount->delete();
        
        session()->flash('message', "Переучет кассы {$number} удален");
    }

    public function resetForm()
    {
        $this->form = [
            'cash_register_id' => null,
            'reason' => '',
            'notes' => '',
        ];
        
        $this->completeForm = [
            'actual_balance' => null,
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModal = true;
    }

    public function closeModal()
    {
        $this->isModal = false;
        $this->resetForm();
    }

    public function closeViewModal()
    {
        $this->isViewModal = false;
        $this->selectedRecount = null;
    }

    public function closeCompleteModal()
    {
        $this->isCompleteModal = false;
        $this->selectedRecount = null;
        $this->resetForm();
    }
} 