<?php

namespace App\Livewire\Admin\Recounts;

use App\Models\Recount;
use App\Models\RecountItem;
use App\Models\Product;
use App\Models\Element;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RecountManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showViewModal = false;
    public $showItemModal = false;
    
    public $selectedRecount = null;
    public $selectedItem = null;
    public $quickEditQuantities = [];
    
    // Создание переучета
    public $form = [
        'type' => 'products',
        'reason' => '',
        'notes' => '',
    ];
    
    // Редактирование элемента
    public $itemForm = [
        'actual_quantity' => null,
        'notes' => '',
    ];

    public function mount()
    {
        $this->resetForm();
        $this->showCreateModal = false;
        $this->showViewModal = false;
        $this->showItemModal = false;
    }

    public function render()
    {
        $recounts = Recount::with(['user', 'items'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.recounts.recount-manager', compact('recounts'));
    }

    public function createRecount()
    {
        if (!auth()->user()->can('recounts.create')) {
            session()->flash('error', 'Недостаточно прав для создания переучетов');
            return;
        }

        $this->validate([
            'form.type' => 'required|in:products,elements',
            'form.reason' => 'required|string|max:1000',
        ]);

        $recount = Recount::create([
            'number' => Recount::generateNumber(),
            'type' => $this->form['type'],
            'user_id' => Auth::id(),
            'reason' => $this->form['reason'],
            'notes' => $this->form['notes'],
        ]);

        // Создаем элементы переучета
        $this->createRecountItems($recount);

        $this->showCreateModal = false;
        $this->resetForm();
        
        $itemsCount = $recount->items()->count();
        session()->flash('message', "Переучет создан успешно. Добавлено позиций: {$itemsCount}");
    }

    protected function createRecountItems($recount)
    {
        if ($recount->type === 'products') {
            $items = Product::all();
            
            foreach ($items as $product) {
                RecountItem::create([
                    'recount_id' => $recount->id,
                    'countable_type' => Product::class,
                    'countable_id' => $product->id,
                    'expected_quantity' => $product->stock ?? 0,
                    'unit_price' => $product->purchase_price ?? 0,
                ]);
            }
        } else {
            $items = Element::all();
            
            foreach ($items as $element) {
                RecountItem::create([
                    'recount_id' => $recount->id,
                    'countable_type' => Element::class,
                    'countable_id' => $element->id,
                    'expected_quantity' => $element->stock ?? 0,
                    'unit_price' => $element->price ?? 0,
                ]);
            }
        }
    }

    public function viewRecount($id)
    {
        $this->selectedRecount = Recount::with(['user', 'items.countable.unit'])->find($id);
        
        // Подготавливаем массив для быстрого редактирования
        $this->quickEditQuantities = [];
        foreach ($this->selectedRecount->items as $item) {
            $this->quickEditQuantities[$item->id] = $item->actual_quantity;
        }
        
        $this->showViewModal = true;
    }

    public function startRecount($id)
    {
        if (!auth()->user()->can('recounts.start')) {
            session()->flash('error', 'Недостаточно прав для начала переучетов');
            return;
        }

        $recount = Recount::find($id);
        $recount->start();
        
        // Автоматически открываем форму заполнения
        $this->viewRecount($id);
        
        session()->flash('message', 'Переучет начат. Заполните фактические остатки по позициям.');
    }

    public function completeRecount($id)
    {
        if (!auth()->user()->can('recounts.complete')) {
            session()->flash('error', 'Недостаточно прав для завершения переучетов');
            return;
        }

        $recount = Recount::with('items')->find($id);
        
        // Проверяем что все позиции заполнены
        $incompleteItems = $recount->items->filter(fn($item) => $item->actual_quantity === null);
        
        if ($incompleteItems->count() > 0) {
            session()->flash('error', 'Не все позиции переучета заполнены');
            return;
        }
        
        $recount->complete();
        
        session()->flash('message', 'Переучет завершен');
    }

    public function cancelRecount($id)
    {
        if (!auth()->user()->can('recounts.cancel')) {
            session()->flash('error', 'Недостаточно прав для отмены переучетов');
            return;
        }

        $recount = Recount::find($id);
        $recount->cancel();
        
        session()->flash('message', 'Переучет отменен');
    }

    public function deleteRecount($id)
    {
        if (!auth()->user()->can('recounts.delete')) {
            session()->flash('error', 'Недостаточно прав для удаления переучетов');
            return;
        }

        $recount = Recount::with('items')->find($id);
        $number = $recount->number;
        
        // Удаляем все позиции переучета
        $recount->items()->delete();
        
        // Удаляем сам переучет
        $recount->delete();
        
        session()->flash('message', "Переучет {$number} удален");
    }

    public function editItem($itemId)
    {
        $this->selectedItem = RecountItem::with('countable.unit')->find($itemId);
        $this->itemForm = [
            'actual_quantity' => $this->selectedItem->actual_quantity,
            'notes' => $this->selectedItem->notes,
        ];
        $this->showItemModal = true;
    }

    public function updateItem()
    {
        if (!auth()->user()->can('recounts.edit')) {
            session()->flash('error', 'Недостаточно прав для редактирования переучетов');
            return;
        }

        $this->validate([
            'itemForm.actual_quantity' => 'required|numeric|min:0',
        ]);

        $this->selectedItem->update([
            'actual_quantity' => $this->itemForm['actual_quantity'],
            'notes' => $this->itemForm['notes'],
        ]);

        $this->selectedItem->calculateDiscrepancy();

        $this->showItemModal = false;
        $this->selectedItem = null;
        
        // Обновляем выбранный переучет
        if ($this->selectedRecount) {
            $this->selectedRecount->refresh();
            $this->selectedRecount->load('items.countable.unit');
        }
        
        session()->flash('message', 'Позиция обновлена');
    }

    public function saveQuickEdit()
    {
        foreach ($this->quickEditQuantities as $itemId => $quantity) {
            if ($quantity !== null && $quantity !== '') {
                $item = RecountItem::find($itemId);
                if ($item) {
                    $item->update(['actual_quantity' => $quantity]);
                    $item->calculateDiscrepancy();
                }
            }
        }
        
        // Обновляем данные переучета
        $this->selectedRecount->refresh();
        $this->selectedRecount->load('items.countable.unit');
        
        session()->flash('message', 'Все позиции обновлены');
    }

    public function resetForm()
    {
        $this->form = [
            'type' => 'products',
            'reason' => '',
            'notes' => '',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showViewModal = false;
        $this->showItemModal = false;
        $this->selectedRecount = null;
        $this->selectedItem = null;
        $this->quickEditQuantities = [];
        $this->resetForm();
    }
} 