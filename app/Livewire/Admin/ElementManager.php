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
        $this->isModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        Element::query()->updateOrCreate(
            ['id' => $this->id],
            $validated
        );

        $this->isModal = false;
    }

    public function delete(int $id): void
    {
        Element::query()->find($id)->delete();
    }
}
