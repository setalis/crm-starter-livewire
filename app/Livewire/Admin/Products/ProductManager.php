<?php

namespace App\Livewire\Admin\Products;

use App\Models\Element;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductManager extends Component
{
    use WithFileUploads;

    public $products;
    public $elements;
    public $units;
    public $isModal = false;
    public $product_id;
    public $name;
    public $type = 'simple';
    public $unit_id;
    public $purchase_price;
    public $selling_price;
    public $clogging;
    public $photo;
    public $image;
    public $element_id_to_add;
    public $element_percentage_to_add;
    public $stock = 0;
    public $user_comment = '';

    // Для обработки загрузки файлов
    public $uploadProgress = 0;

    #[Session]
    public bool $is_published = false;
    #[Session]
    public array $priceScales = [];
    #[Session]
    public array $selectedElements = [];

    public function mount()
    {
        $this->units = Unit::all();
        $this->elements = Element::all();
        
        // Устанавливаем единицу измерения "Килограмм" по умолчанию при загрузке
        $defaultUnit = Unit::where('name', 'Килограмм')->first();
        $this->unit_id = $defaultUnit ? $defaultUnit->id : ($this->units->first()?->id ?? null);
    }

    public function render()
    {
        $this->products = Product::with('unit', 'elements')->get();
        return view('livewire.admin.products.product-manager');
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModal = true;
    }

    public function closeModal()
    {
        $this->isModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->product_id = null;
        $this->name = null;
        $this->type = 'simple';
        // Устанавливаем единицу измерения "Килограмм" по умолчанию
        $defaultUnit = Unit::where('name', 'Килограмм')->first();
        $this->unit_id = $defaultUnit ? $defaultUnit->id : ($this->units->first()?->id ?? null);
        $this->purchase_price = null;
        $this->selling_price = null;
        $this->clogging = null;
        $this->photo = null;
        $this->image = null;
        $this->is_published = false;
        $this->priceScales = [];
        $this->selectedElements = [];
        $this->element_id_to_add = null;
        $this->element_percentage_to_add = null;
        $this->stock = 0;
        $this->user_comment = '';
    }

    public function addElement()
    {
        $this->validate([
            'element_id_to_add' => ['required', 'integer', 'exists:elements,id'],
            'element_percentage_to_add' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        if (isset($this->selectedElements[$this->element_id_to_add])) {
             throw \Illuminate\Validation\ValidationException::withMessages([
                'element_id_to_add' => 'Этот элемент уже добавлен.',
            ]);
        }

        $futureSelectedElements = $this->selectedElements;
        $futureSelectedElements[$this->element_id_to_add] = ['percentage' => $this->element_percentage_to_add];

        if (array_sum(array_column($futureSelectedElements, 'percentage')) > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'element_percentage_to_add' => 'Сумма процентов не может быть больше 100.',
            ]);
        }

        $this->selectedElements = $futureSelectedElements;

        $this->element_id_to_add = null;
        $this->element_percentage_to_add = null;
    }

    public function removeElement($elementId)
    {
        unset($this->selectedElements[$elementId]);
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

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:simple,composite',
            'unit_id' => 'required|exists:units,id',
            'selling_price' => 'nullable|numeric',
            'photo' => 'nullable|image|max:1024',
            'is_published' => 'boolean',
            'priceScales.*.threshold_kg' => 'required|numeric',
            'priceScales.*.price' => 'required|numeric',
            'stock' => 'required|numeric|min:0',
        ];

        if ($this->type === 'simple') {
            $rules['purchase_price'] = 'required|numeric';
            $rules['selling_price'] = 'required|numeric'; // Для простых продуктов стоимость продажи обязательна
            $rules['clogging'] = 'nullable|numeric|min:0|max:100';
        }

        if ($this->type === 'composite') {
            $rules['selectedElements'] = 'required|array|min:1';
            $rules['selectedElements.*.percentage'] = 'required|numeric|min:0|max:100';

            if (array_sum(array_column($this->selectedElements, 'percentage')) > 100) {
                 throw \Illuminate\Validation\ValidationException::withMessages([
                    'selectedElements' => 'Сумма процентов не может быть больше 100.',
                ]);
            }
        } 
        
        $this->validate($rules);

        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('products', 'public');
        } elseif ($this->image) {
            $imagePath = $this->image;
        }

        $data = [
            'user_id' => auth()->id(),
            'name' => $this->name,
            'type' => $this->type,
            'unit_id' => $this->unit_id,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'clogging' => $this->clogging,
            'image' => $imagePath,
            'is_published' => $this->is_published,
            'stock' => $this->stock,
        ];

        $product = Product::updateOrCreate(['id' => $this->product_id], $data);

        // Добавляем системный комментарий
        if ($this->product_id) {
            $product->addSystemComment(
                "Продукт отредактирован пользователем " . auth()->user()->name,
                ['action' => 'edit', 'product_data' => $data]
            );
        } else {
            $product->addSystemComment(
                "Продукт создан пользователем " . auth()->user()->name,
                ['action' => 'create', 'product_data' => $data]
            );
        }

        // Добавляем пользовательский комментарий если есть
        if (!empty($this->user_comment)) {
            $product->addComment(
                $this->user_comment,
                'comment',
                false
            );
            $this->dispatch('comment-added');
        }

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
        session()->flash('message', 'Продукт успешно сохранен.');
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
        $this->stock = $product->stock;
        $this->priceScales = $product->priceScales->map(function ($scale) {
            return [
                'threshold_kg' => $scale->threshold_kg,
                'price' => $scale->price
            ];
        })->toArray();
        $this->selectedElements = $product->elements->mapWithKeys(function ($element) {
            return [$element->id => ['percentage' => $element->pivot->percentage]];
        })->toArray();

        // Очищаем поля для добавления элементов
        $this->element_id_to_add = null;
        $this->element_percentage_to_add = null;
        $this->photo = null;

        $this->isModal = true;
        
        // Принудительно обновляем компонент
        $this->dispatch('refresh');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        
        // Добавляем системный комментарий перед удалением
        $product->addSystemComment(
            "Продукт удален пользователем " . auth()->user()->name,
            ['action' => 'delete', 'product_name' => $product->name]
        );
        
        $product->delete();
        session()->flash('message', 'Продукт удален.');
    }

    /**
     * Рассчитывает стоимость составного продукта за 1 кг на основе выбранных элементов
     */
    public function getCalculatedCompositePrice()
    {
        if ($this->type !== 'composite' || empty($this->selectedElements)) {
            return 0;
        }

        $totalPrice = 0;
        
        foreach ($this->selectedElements as $elementId => $data) {
            $element = $this->elements->find($elementId);
            if ($element) {
                // Стоимость элемента = цена за 1% * процентное содержание
                $percentage = is_numeric($data['percentage']) ? (float)$data['percentage'] : 0;
                $elementPrice = $element->price * $percentage;
                $totalPrice += $elementPrice;
            }
        }

        return round($totalPrice, 2);
    }

    /**
     * Получает общий процент содержания элементов
     */
    public function getTotalElementsPercentage()
    {
        return array_sum(array_column($this->selectedElements, 'percentage'));
    }

    public function updatedPhoto()
    {
        // Сбрасываем прогресс загрузки после успешной загрузки
        $this->uploadProgress = 0;
    }
}
