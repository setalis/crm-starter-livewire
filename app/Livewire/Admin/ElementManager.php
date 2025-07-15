<?php

namespace App\Livewire\Admin;

use App\Models\Element;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ElementManager extends Component
{
    public Collection $units;

    public bool $isModal = false;

    #[Locked]
    public ?int $id = null;

    public ?string $name = null;

    public ?int $unit_id = null;

    public ?string $price = null;

    public ?string $unit_price = null;

    public ?string $stock = '0';

    public string $user_comment = '';

    public function mount(): void
    {
        $this->units = Unit::all();
    }

    public function render(): View
    {
        $elements = Element::query()
            ->with('unit')
            ->get();

        return view('livewire.admin.elements.manager', compact('elements'));
    }

    #[On('create')]
    public function create(): void
    {
        $this->resetErrorBag();
        $this->resetExcept('units');
        $this->user_comment = '';
        $this->stock = '0'; // Set default stock value
        $this->price = null;
        $this->unit_price = null;
        $this->isModal = true;
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();
        $this->resetExcept('units');
        $element = Element::query()->find($id);
        $this->id = $element->id;
        $this->name = $element->name;
        $this->unit_id = $element->unit_id;
        $this->price = $element->price;
        $this->unit_price = $element->unit_price;
        $this->stock = $element->stock ?? '0';
        $this->user_comment = '';
        $this->isModal = true;
    }

    public function updatedPrice(): void
    {
        // Dynamically calculate unit_price when price changes
        if ($this->price && is_numeric($this->price)) {
            // Assuming 100% contains the full element, so unit price = price * 100
            $this->unit_price = number_format((float)$this->price * 100, 2, '.', '');
        } else {
            $this->unit_price = '0.00';
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['user_id'] = auth()->id();

        $element = Element::query()->updateOrCreate(
            ['id' => $this->id],
            $validated
        );

        // Добавляем системный комментарий
        if ($this->id) {
            $element->addSystemComment(
                "Элемент отредактирован пользователем " . auth()->user()->name,
                ['action' => 'edit', 'element_data' => $validated]
            );
        } else {
            $element->addSystemComment(
                "Элемент создан пользователем " . auth()->user()->name,
                ['action' => 'create', 'element_data' => $validated]
            );
        }

        // Добавляем пользовательский комментарий если есть
        if (!empty($this->user_comment)) {
            $element->addComment(
                $this->user_comment,
                'comment',
                false
            );
            $this->dispatch('comment-added');
        }

        $this->isModal = false;
    }

    public function delete(int $id): void
    {
        $element = Element::query()->find($id);
        
        // Добавляем системный комментарий перед удалением
        $element->addSystemComment(
            "Элемент удален пользователем " . auth()->user()->name,
            ['action' => 'delete', 'element_name' => $element->name]
        );
        
        $element->delete();
    }
}
