<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Product;
use Livewire\Component;

class ProductsWidget extends Component
{
    public $products;
    public $lowStockProducts;

    public function mount()
    {
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.products-widget');
    }

    public function loadProducts()
    {
        $this->products = Product::with('unit')->orderBy('name')->get();
        $this->lowStockProducts = $this->products->filter(function($product) {
            return $product->stock <= 10;
        });
    }
}
