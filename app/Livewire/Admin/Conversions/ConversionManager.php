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
    public $user_comment = '';

    // Элементы для составного продукта (автоматически рассчитываются)
    public $calculatedElements = [];
    
    // Флаг для отслеживания ручного изменения target_quantity
    public $targetQuantityManuallyChanged = false;
    


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
        $this->user_comment = '';
        $this->calculatedElements = [];
        $this->targetQuantityManuallyChanged = false;
    }

    public function sourceProductChanged()
    {
        // Метод вызывается при изменении исходного продукта
        // Никаких дополнительных действий не требуется
    }

    public function targetProductChanged()
    {
        // Метод вызывается при изменении целевого продукта
        // Расчеты будут обновлены по кнопке или при сохранении
    }

    public function sourceQuantityChanged()
    {
        // Автоматически синхронизируем target_quantity с source_quantity, 
        // если пользователь не изменял target_quantity вручную
        if ($this->source_quantity && !$this->targetQuantityManuallyChanged) {
            $this->target_quantity = $this->source_quantity;
        }
    }

    public function targetQuantityChanged()
    {
        // Отмечаем, что пользователь вручную изменил target_quantity
        $this->targetQuantityManuallyChanged = true;
    }

    public function refreshCalculatedElements()
    {
        // Простая функция для обновления расчетных элементов
        $this->calculateElements();
    }

    private function calculateElements()
    {
        try {
            // Сброс по умолчанию
            $this->calculatedElements = [];
            
            // Проверяем базовые условия
            if (empty($this->target_product_id) || empty($this->target_quantity)) {
                return;
            }
            
            // Получаем продукт напрямую без кэша для избежания циклов
            $targetProduct = Product::with(['elements.unit'])->find($this->target_product_id);
            
            // Проверяем что продукт найден и составной
            if (!$targetProduct || $targetProduct->type !== 'composite' || $targetProduct->elements->isEmpty()) {
                return;
            }
            
            // Рассчитываем элементы
            $elements = [];
            foreach ($targetProduct->elements as $element) {
                $elementQuantity = (floatval($this->target_quantity) * floatval($element->pivot->percentage ?? 0)) / 100;
                
                $elements[$element->id] = [
                    'name' => $element->name ?? 'Неизвестный элемент',
                    'quantity' => $elementQuantity,
                    'unit' => $element->unit->name ?? 'кг',
                    'percentage' => floatval($element->pivot->percentage ?? 0)
                ];
            }
            
            $this->calculatedElements = $elements;
            
        } catch (\Exception $e) {
            // В случае ошибки просто очищаем элементы
            $this->calculatedElements = [];
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

            // Добавляем системный комментарий
            $conversion->addSystemComment(
                "Конвертация создана пользователем " . auth()->user()->name,
                [
                    'action' => 'create',
                    'source_product' => $conversion->sourceProduct->name,
                    'target_product' => $conversion->targetProduct->name,
                    'source_quantity' => $conversion->source_quantity,
                    'target_quantity' => $conversion->target_quantity,
                ]
            );

            // Добавляем пользовательский комментарий если есть
            if (!empty($this->user_comment)) {
                $conversion->addComment(
                    $this->user_comment,
                    'comment',
                    false
                );
                $this->dispatch('comment-added');
            }

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

            // Добавляем системный комментарий перед отменой
            $operation->conversion->addSystemComment(
                "Конвертация отменена пользователем " . auth()->user()->name,
                [
                    'action' => 'reverse',
                    'source_product' => $operation->conversion->sourceProduct->name,
                    'target_product' => $operation->conversion->targetProduct->name,
                ]
            );

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