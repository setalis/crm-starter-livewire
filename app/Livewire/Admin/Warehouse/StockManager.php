<?php

namespace App\Livewire\Admin\Warehouse;

use App\Models\Element;
use App\Models\Product;
use Livewire\Component;

class StockManager extends Component
{
    public $products;
    public $elements;
    public $activeTab = 'products';

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.warehouse.stock-manager');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function loadData()
    {
        $this->products = Product::with('unit')->get();
        $this->elements = Element::with('unit')->get();
    }
} 