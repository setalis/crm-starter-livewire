<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Product;
use App\Models\Element;
use Livewire\Component;

class WarehouseStats extends Component
{
    public $totalProducts = 0;
    public $totalElements = 0;
    public $lowStockProducts = 0;
    public $lowStockElements = 0;
    public $warehouseValue = 0;
    public $elementsValue = 0;
    public $topProducts = [];
    public $criticalProducts = [];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.warehouse-stats');
    }

    protected function loadData()
    {
        // Основные показатели
        $products = Product::with('unit')->get();
        $elements = Element::with('unit')->get();

        $this->totalProducts = $products->count();
        $this->totalElements = $elements->count();

        // Товары с низким остатком (меньше 10)
        $this->lowStockProducts = $products->where('stock', '<=', 10)->count();
        $this->lowStockElements = $elements->where('stock', '<=', 10)->count();

        // Стоимость склада
        $this->warehouseValue = $products->sum(function($product) {
            return $product->stock * ($product->average_purchase_price ?? $product->purchase_price ?? 0);
        });

        $this->elementsValue = $elements->sum(function($element) {
            return $element->stock * ($element->unit_price ?? 0);
        });

        // Топ товары по стоимости
        $this->topProducts = $products->sortByDesc(function($product) {
            return $product->stock * ($product->average_purchase_price ?? $product->purchase_price ?? 0);
        })->take(5)->map(function($product) {
            return [
                'name' => $product->name,
                'stock' => $product->stock,
                'unit' => $product->unit->short_name,
                'value' => $product->stock * ($product->average_purchase_price ?? $product->purchase_price ?? 0)
            ];
        })->values()->toArray();

        // Критические остатки
        $this->criticalProducts = $products->where('stock', '<=', 5)
            ->take(5)
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'unit' => $product->unit->short_name
                ];
            })->values()->toArray();
    }
} 