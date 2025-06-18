<?php

namespace App\Livewire\Admin\Conversions;

use App\Models\Conversion;
use App\Models\ConversionElement;
use App\Models\Element;
use App\Models\Operation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConversionManager extends Component
{
    public $conversions;
    public $sourceProducts; // Продукты с остатками для исходного продукта
    public $allProducts;    // Все продукты для целевого продукта
    public $elements;
    public $isModal = false;
    public $conversion_id;

    // Основные поля конвертации
    public $source_product_id;
    public $target_product_id;
    public $source_quantity;
    public $target_quantity;
    public $notes;

    // Элементы для составного продукта (автоматически рассчитываются)
    public $calculatedElements = [];

    public function mount()
    {
        $this->sourceProducts = Product::where('stock', '>', 0)->get(); // Только продукты с остатками
        $this->allProducts = Product::all(); // Все продукты
        $this->elements = Element::all();
    }

    public function render()
    {
        $this->conversions = Operation::with(['conversion.sourceProduct', 'conversion.targetProduct', 'conversion.elements.element'])
            ->where('type', 'conversion')
            ->latest()
            ->get();

        return view('livewire.admin.conversions.conversion-manager');
    }

    public function openModal()
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
        $this->conversion_id = null;
        $this->source_product_id = null;
        $this->target_product_id = null;
        $this->source_quantity = null;
        $this->target_quantity = null;
        $this->notes = null;
        $this->calculatedElements = [];
    }

    public function updatedTargetProductId()
    {
        $this->calculateElements();
    }

    public function updatedTargetQuantity()
    {
        $this->calculateElements();
    }

    private function calculateElements()
    {
        $this->calculatedElements = [];
        
        if ($this->target_product_id && $this->target_quantity) {
            // Получаем продукт с элементами из базы данных для актуальных данных
            $targetProduct = Product::with(['elements.unit'])->find($this->target_product_id);
            
            if ($targetProduct && $targetProduct->type === 'composite') {
                foreach ($targetProduct->elements as $element) {
                    // Рассчитываем количество элемента на основе процентного содержания
                    $elementQuantity = ($this->target_quantity * $element->pivot->percentage) / 100;
                    
                    $this->calculatedElements[$element->id] = [
                        'name' => $element->name,
                        'quantity' => $elementQuantity,
                        'unit' => $element->unit->name ?? 'кг',
                        'percentage' => $element->pivot->percentage
                    ];
                }
            }
        }
    }



    public function store()
    {
        $rules = [
            'source_product_id' => 'required|exists:products,id',
            'target_product_id' => 'required|exists:products,id|different:source_product_id',
            'source_quantity' => 'required|numeric|min:0.001',
            'target_quantity' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string|max:1000',
        ];

        $this->validate($rules);

        // Проверяем наличие достаточного количества исходного продукта
        $sourceProduct = Product::find($this->source_product_id);
        if ($sourceProduct->stock < $this->source_quantity) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'source_quantity' => 'Недостаточно запасов исходного продукта.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Создаем операцию
            $operation = Operation::create([
                'operation_number' => $this->generateOperationNumber(),
                'user_id' => auth()->id(),
                'type' => 'conversion',
                'total_amount' => 0,
            ]);

            // Определяем тип конвертации на основе целевого продукта
            $targetProduct = Product::find($this->target_product_id);
            $conversionType = $targetProduct->type === 'composite' ? 'unequal' : 'equal';

            // Создаем конвертацию
            $conversion = Conversion::create([
                'operation_id' => $operation->id,
                'source_product_id' => $this->source_product_id,
                'target_product_id' => $this->target_product_id,
                'source_quantity' => $this->source_quantity,
                'target_quantity' => $this->target_quantity,
                'conversion_type' => $conversionType,
                'notes' => $this->notes,
            ]);

            // Если целевой продукт составной, добавляем элементы
            if ($targetProduct->type === 'composite') {
                foreach ($this->calculatedElements as $elementId => $data) {
                    ConversionElement::create([
                        'conversion_id' => $conversion->id,
                        'element_id' => $elementId,
                        'quantity' => $data['quantity'],
                    ]);
                }
            }

            // Выполняем конвертацию
            $conversion->load('elements.element');
            $conversion->execute();

            DB::commit();

            $this->closeModal();
            session()->flash('message', 'Конвертация успешно выполнена.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Ошибка при выполнении конвертации: ' . $e->getMessage());
        }
    }

    public function reverseConversion($operationId)
    {
        try {
            DB::beginTransaction();

            $operation = Operation::with('conversion.elements.element')->find($operationId);
            
            if (!$operation || !$operation->conversion) {
                throw new \Exception('Конвертация не найдена');
            }

            // Отменяем конвертацию
            $operation->conversion->reverse();

            // Удаляем операцию и связанные данные
            $operation->delete();

            DB::commit();

            session()->flash('message', 'Конвертация успешно отменена.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Ошибка при отмене конвертации: ' . $e->getMessage());
        }
    }

    private function generateOperationNumber()
    {
        $prefix = 'CNV';
        $date = now()->format('Ymd');
        $lastOperation = Operation::where('type', 'conversion')
            ->where('operation_number', 'like', $prefix . $date . '%')
            ->orderBy('operation_number', 'desc')
            ->first();

        $sequence = 1;
        if ($lastOperation) {
            $lastSequence = (int) substr($lastOperation->operation_number, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getTargetProductType()
    {
        if ($this->target_product_id) {
            $targetProduct = $this->allProducts->find($this->target_product_id);
            return $targetProduct ? $targetProduct->type : null;
        }
        return null;
    }
} 