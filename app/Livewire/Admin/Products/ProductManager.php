<?php

namespace App\Livewire\Admin\Products;

use App\Models\Element;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Session;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    #[Session]
    public ?string $name = null;
    #[Session]
    public string $type = 'simple';
    #[Session]
    public ?int $unit_id = null;
    #[Session]
    public ?float $purchase_price = null;
    #[Session]
    public ?float $selling_price = null;
    #[Session]
    public ?float $clogging = 0;
    public $image;
    #[Session]
    public bool $is_published = false;
    #[Session]
    public array $priceScales = [];
    #[Session]
    public array $selectedElements = [];

    #[Session]
    public ?int $element_id_to_add = null;

    #[Session]
    public ?int $product_id = null;
    #[Session]
    public bool $isModal = false;
    public $photo;


    public function render()
    {
        $products = Product::with('unit')->orderBy('position')->paginate(10);
        $units = Unit::all();
        $elements = Element::all();
        return view('livewire.admin.products.product-manager', [
            'products' => $products,
            'units' => $units,
            'elements' => $elements,
        ]);
    }

    public function create()
    {
        $this->isModal = true;
    }

    public function closeModal()
    {
        $this->isModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = null;
        $this->type = 'simple';
        $this->unit_id = null;
        $this->purchase_price = null;
        $this->selling_price = null;
        $this->clogging = 0;
        $this->image = null;
        $this->photo = null;
        $this->is_published = false;
        $this->priceScales = [];
        $this->selectedElements = [];
        $this->element_id_to_add = null;
        $this->product_id = null;
    }

    public function addElement()
    {
        if ($this->element_id_to_add && !in_array($this->element_id_to_add, $this->selectedElements)) {
            $this->selectedElements[] = $this->element_id_to_add;
        }
        $this->element_id_to_add = null;
    }

    public function removeElement($elementId)
    {
        $this->selectedElements = array_filter($this->selectedElements, fn ($id) => $id != $elementId);
    }

    public function addPriceScale()
    {
        $this->priceScales[] = ['threshold_kg' => '', 'price' => ''];
    }

    public function removePriceScale($index)
    {
        unset($this->priceScales[$index]);
        $this->priceScales = array_values($this->priceScales);
    }

    public function updateProductOrder($items)
    {
        foreach ($items as $item) {
            Product::find($item['value'])->update(['position' => $item['order']]);
        }
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'type' => 'required|in:simple,composite',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'clogging' => 'nullable|numeric',
            'photo' => 'nullable|image|max:1024',
            'is_published' => 'boolean',
            'priceScales.*.threshold_kg' => 'required|numeric',
            'priceScales.*.price' => 'required|numeric',
        ];

        if ($this->type === 'composite') {
            $rules['selectedElements'] = 'required|array|min:1';
        }

        $this->validate($rules);


        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('products', 'public');
        }

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'unit_id' => $this->unit_id,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'clogging' => $this->clogging,
            'image' => $imagePath ?? $this->image,
            'is_published' => $this->is_published,
        ];
        
        if ($this->product_id === null) {
            $data['position'] = Product::max('position') + 1;
        }

        $product = Product::updateOrCreate(['id' => $this->product_id], $data);

        $product->priceScales()->delete();
        foreach ($this->priceScales as $scale) {
            $product->priceScales()->create($scale);
        }

        if ($this->type === 'composite') {
            $product->elements()->sync($this->selectedElements);
        } else {
            $product->elements()->detach();
        }

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::with('priceScales', 'elements')->findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->type = $product->type;
        $this->unit_id = $product->unit_id;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->clogging = $product->clogging;
        $this->image = $product->image;
        $this->is_published = $product->is_published;
        $this->priceScales = $product->priceScales->toArray();
        $this->selectedElements = $product->elements->pluck('id')->toArray();

        $this->isModal = true;
    }

    public function delete($id)
    {
        Product::find($id)->delete();
    }
}
