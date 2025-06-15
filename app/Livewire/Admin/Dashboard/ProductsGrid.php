<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Product;
use Livewire\Component;

class ProductsGrid extends Component
{
    public $products;
    public $editingProduct = null;
    public $editingPurchasePrice = '';
    public $editingSellingPrice = '';
    public $searchTerm = '';

    public function mount()
    {
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.products-grid');
    }

    public function loadProducts()
    {
        $query = Product::with('unit')->where('is_published', true);
        
        if (!empty($this->searchTerm)) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        $this->products = $query->orderBy('name')->get();
    }

    public function updatedSearchTerm()
    {
        $this->loadProducts();
    }

    public function startEditing($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->editingProduct = $productId;
            $this->editingPurchasePrice = $product->purchase_price;
            $this->editingSellingPrice = $product->selling_price;
        }
    }

    public function savePrices()
    {
        $this->validate([
            'editingPurchasePrice' => 'required|numeric|min:0',
            'editingSellingPrice' => 'required|numeric|min:0'
        ]);

        $product = Product::find($this->editingProduct);
        if ($product) {
            $product->update([
                'purchase_price' => $this->editingPurchasePrice,
                'selling_price' => $this->editingSellingPrice
            ]);
            
            $this->loadProducts();
            $this->cancelEditing();
            
            $this->dispatch('product-updated', [
                'message' => "Цены товара '{$product->name}' обновлены"
            ]);
        }
    }

    public function cancelEditing()
    {
        $this->editingProduct = null;
        $this->editingPurchasePrice = '';
        $this->editingSellingPrice = '';
    }

    public function goToPurchase($productId)
    {
        // Переходим к созданию операции покупки с выбранным товаром
        return $this->redirect(route('admin.operations.create', ['type' => 'purchase', 'product_id' => $productId]));
    }
}
