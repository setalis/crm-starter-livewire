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
    public $showDeleteRegisterModal = false;
    
    // Filter
    public $transactionTypeFilter = 'all'; // 'all', 'income', 'expense', 'money_only'
    
    // Form fields
    public $amount;
    public $description;
    public $registerName;
    public $registerDescription;
    public $registerToDelete;

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
        $this->transactionTypeFilter = 'all'; // Сбрасываем фильтр при смене кассы
        $this->resetPage(); // Сбрасываем пагинацию при смене кассы
    }

    public function setTransactionFilter($filter)
    {
        $this->transactionTypeFilter = $filter;
        $this->resetPage(); // Сбрасываем пагинацию при изменении фильтра
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

    public function showDeleteRegisterModalAction($registerId)
    {
        $this->registerToDelete = CashRegister::find($registerId);
        $this->showDeleteRegisterModal = true;
    }

    public function closeModals()
    {
        $this->showAddMoneyModal = false;
        $this->showWithdrawMoneyModal = false;
        $this->showCreateRegisterModal = false;
        $this->showDeleteRegisterModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->amount = null;
        $this->description = null;
        $this->registerName = null;
        $this->registerDescription = null;
        $this->registerToDelete = null;
    }

    public function addMoney()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $this->selectedRegister->addMoney(
            $this->amount, 
            $this->description ?: 'Пополнение кассы', 
            auth()->id()
        );

        session()->flash('message', 'Средства успешно добавлены в кассу');
        $this->closeModals();
        $this->loadCashRegisters();
        $this->selectedRegister = $this->selectedRegister->fresh();
    }

    public function withdrawMoney()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $this->selectedRegister->withdrawMoney(
            $this->amount, 
            $this->description ?: 'Снятие средств из кассы', 
            auth()->id()
        );

        session()->flash('message', 'Средства успешно сняты из кассы');
        $this->closeModals();
        $this->loadCashRegisters();
        $this->selectedRegister = $this->selectedRegister->fresh();
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

    public function deleteRegister()
    {
        if (!$this->registerToDelete) {
            session()->flash('error', 'Касса для удаления не найдена');
            return;
        }

        try {
            // Проверяем, есть ли транзакции в кассе
            $transactionsCount = CashTransaction::where('cash_register_id', $this->registerToDelete->id)->count();
            
            if ($transactionsCount > 0) {
                // Если есть транзакции, помечаем кассу как неактивную вместо удаления
                $this->registerToDelete->update(['is_active' => false]);
                $message = 'Касса была деактивирована, так как в ней есть транзакции';
            } else {
                // Если транзакций нет, можно удалить полностью
                $this->registerToDelete->delete();
                $message = 'Касса успешно удалена';
            }

            // Если удаляемая касса была выбрана, выбираем другую или сбрасываем выбор
            if ($this->selectedRegister && $this->selectedRegister->id === $this->registerToDelete->id) {
                $this->loadCashRegisters();
                $this->selectedRegister = $this->cashRegisters && $this->cashRegisters->count() > 0 
                    ? $this->cashRegisters->first() 
                    : null;
            } else {
                $this->loadCashRegisters();
            }

            session()->flash('message', $message);
            $this->closeModals();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении кассы: ' . $e->getMessage());
        }
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

        $query = CashTransaction::where('cash_register_id', $this->selectedRegister->id)
            ->with(['user', 'operation']);

        // Применяем фильтр по типу транзакции
        if ($this->transactionTypeFilter === 'income') {
            $query->where('type', 'income');
        } elseif ($this->transactionTypeFilter === 'expense') {
            $query->where('type', 'expense');
        } elseif ($this->transactionTypeFilter === 'money_only') {
            // Показываем только денежные операции (без связи с операциями продаж)
            $query->whereNull('operation_id');
        }
        // Если 'all', то не добавляем условие where

        return $query->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page', $this->getPage());
    }

    public function render()
    {
        return view('livewire.admin.cash-register-manager');
    }
}
