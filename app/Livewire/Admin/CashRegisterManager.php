<?php

namespace App\Livewire\Admin;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class CashRegisterManager extends Component
{
    use WithPagination;

    public $cashRegisters;
    public $selectedRegister;
    
    // Modal states
    public $showAddMoneyModal = false;
    public $showWithdrawMoneyModal = false;
    public $showCreateRegisterModal = false;
    
    // Form fields
    public $amount;
    public $description;
    public $registerName;
    public $registerDescription;

    public function mount()
    {
        $this->loadCashRegisters();
        $this->selectedRegister = $this->cashRegisters && $this->cashRegisters->count() > 0 
            ? $this->cashRegisters->first() 
            : null;
    }

    public function loadCashRegisters()
    {
        $this->cashRegisters = CashRegister::where('is_active', true)->orderBy('id')->get();
    }

    public function selectRegister($registerId)
    {
        $this->selectedRegister = CashRegister::find($registerId);
        $this->resetPage(); // Сбрасываем пагинацию при смене кассы
    }

    public function showAddMoneyModalAction()
    {
        $this->resetFields();
        $this->showAddMoneyModal = true;
    }

    public function showWithdrawMoneyModalAction()
    {
        $this->resetFields();
        $this->showWithdrawMoneyModal = true;
    }

    public function showCreateRegisterModalAction()
    {
        $this->resetFields();
        $this->showCreateRegisterModal = true;
    }

    public function closeModals()
    {
        $this->showAddMoneyModal = false;
        $this->showWithdrawMoneyModal = false;
        $this->showCreateRegisterModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->amount = null;
        $this->description = null;
        $this->registerName = null;
        $this->registerDescription = null;
    }

    public function addMoney()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $this->selectedRegister->addMoney(
                $this->amount, 
                $this->description ?: 'Пополнение кассы', 
                auth()->id()
            );

            session()->flash('message', 'Средства успешно добавлены в кассу');
            $this->closeModals();
            $this->loadCashRegisters();
            $this->selectedRegister = $this->selectedRegister->fresh();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function withdrawMoney()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $this->selectedRegister->withdrawMoney(
                $this->amount, 
                $this->description ?: 'Снятие средств из кассы', 
                auth()->id()
            );

            session()->flash('message', 'Средства успешно сняты из кассы');
            $this->closeModals();
            $this->loadCashRegisters();
            $this->selectedRegister = $this->selectedRegister->fresh();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function createRegister()
    {
        $this->validate([
            'registerName' => 'required|string|max:255',
            'registerDescription' => 'nullable|string|max:1000',
        ]);

        CashRegister::create([
            'name' => $this->registerName,
            'description' => $this->registerDescription,
            'balance' => 0,
            'is_active' => true,
        ]);

        session()->flash('message', 'Касса успешно создана');
        $this->closeModals();
        $this->loadCashRegisters();
    }



    public function getTransactionsProperty()
    {
        if (!$this->selectedRegister) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                15,
                $this->getPage(),
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        }

        return CashTransaction::where('cash_register_id', $this->selectedRegister->id)
            ->with(['user', 'operation'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page', $this->getPage());
    }

    public function render()
    {
        return view('livewire.admin.cash-register-manager');
    }
}
