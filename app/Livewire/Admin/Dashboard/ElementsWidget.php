<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Element;
use Livewire\Component;

class ElementsWidget extends Component
{
    public $elements;
    public $editingElement = null;
    public $editingUnitPrice = '';

    public function mount()
    {
        $this->loadElements();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.elements-widget');
    }

    public function loadElements()
    {
        $this->elements = Element::with('unit')->orderBy('name')->get();
    }

    public function startEditing($elementId)
    {
        $element = Element::find($elementId);
        if ($element) {
            $this->editingElement = $elementId;
            $this->editingUnitPrice = $element->unit_price;
        }
    }

    public function saveUnitPrice()
    {
        $this->validate([
            'editingUnitPrice' => 'required|numeric|min:0'
        ]);

        $element = Element::find($this->editingElement);
        if ($element) {
            $element->update(['unit_price' => $this->editingUnitPrice]);
            $this->loadElements();
            $this->cancelEditing();
            
            $this->dispatch('element-updated', [
                'message' => "Цена элемента '{$element->name}' обновлена"
            ]);
        }
    }

    public function cancelEditing()
    {
        $this->editingElement = null;
        $this->editingUnitPrice = '';
    }
}
