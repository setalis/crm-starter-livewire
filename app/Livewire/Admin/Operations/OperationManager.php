<?php

namespace App\Livewire\Admin\Operations;

use App\Models\Element;
use App\Models\Operation;
use App\Models\Product;
use App\Models\User;
use App\Models\CashRegister;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class OperationManager extends Component
{
    use WithPagination;

    #[Session]
    public array $operations = [];
    #[Session]
    public ?string $activeOperationId = null;

    public bool $isModal = false;
    public ?string $notification = null;
    
    // Product selection modal
    public bool $showProductModal = false;
    public string $productSearch = '';
    
    // Operation details modal
    public bool $showOperationDetailsModal = false;
    public ?Operation $selectedOperation = null;
    
    // Product selection
    public ?int $product_to_add = null;

    public function mount(?string $type = null)
    {
        if ($type) {
            // This is for /operations/create/{type} routes
            $this->addNewOperation($type);
            
            // Check if product_id is provided in the request
            $productId = request()->get('product_id');
            if ($productId && $this->activeOperationId) {
                $this->addProductToCart($productId);
            }
        } else {
            // This is for /operations index route
            $this->isModal = false;
        }

        // Ensure an active tab is set if there are any in the session
        if (!empty($this->operations) && !isset($this->operations[$this->activeOperationId])) {
            $this->activeOperationId = array_key_first($this->operations);
        }
    }

    private function generateOperationId($type): string
    {
        $prefix = $type === 'purchase' ? 'PUR' : 'SAL';
        $timestamp = now()->format('Ymd');
        $randomSuffix = substr(uniqid(), -4); // 4-char random suffix
        return sprintf('%s-%s-%s', $prefix, $timestamp, $randomSuffix);
    }

    public function addNewOperation($type = 'purchase')
    {
        $newId = $this->generateOperationId($type);
        $this->operations[$newId] = [
            'id' => $newId,
            'type' => $type,
            'user_id' => auth()->id(),
            'cartItems' => [],
            'totalAmount' => 0,
        ];
        $this->activeOperationId = $newId;
        $this->isModal = true;
    }

    public function showOperationsCart()
    {
        if (!empty($this->operations)) {
            $this->isModal = true;
        } else {
            // Fallback in case button is shown incorrectly
            $this->addNewOperation('purchase');
        }
    }

    public function switchOperation($operationId)
    {
        if (isset($this->operations[$operationId])) {
            $this->activeOperationId = $operationId;
        }
    }

    public function render()
    {
        $allOperations = Operation::with(['user', 'items.product'])->latest()->paginate(10);
        
        // Filter products for the modal
        $allProducts = Product::where('is_published', true)->orderBy('name')->get();
        
        if (!empty($this->productSearch)) {
            $searchTerm = mb_strtolower(trim($this->productSearch), 'UTF-8');
            $products = $allProducts->filter(function ($product) use ($searchTerm) {
                return mb_strpos(mb_strtolower($product->name, 'UTF-8'), $searchTerm, 0, 'UTF-8') !== false;
            });
        } else {
            $products = $allProducts;
        }
        
        $users = User::all();

        return view('livewire.admin.operations.operation-manager', [
            'operationsList' => $allOperations,
            'products' => $products,
            'users' => $users,
        ]);
    }
    
    public function getActiveOperationProperty()
    {
        return $this->operations[$this->activeOperationId] ?? null;
    }

    public function closeModal()
    {
        // Проверяем, есть ли другие операции в сессии
        if (!empty($this->operations)) {
            // Если есть операции, остаемся в модальном окне и переключаемся на первую доступную
            $this->activeOperationId = array_key_first($this->operations);
        } else {
            // Если операций нет, закрываем модальное окно и переходим к списку
            $this->isModal = false;
            return $this->redirect(route('admin.operations.index'), navigate: true);
        }
    }

    public function closeCurrentOperation()
    {
        if (!$this->activeOperationId) return;
        
        // Удаляем текущую активную операцию
        $currentOperationId = $this->activeOperationId;
        unset($this->operations[$currentOperationId]);
        
        // Проверяем, есть ли другие операции в сессии
        if (!empty($this->operations)) {
            // Если есть операции, переключаемся на первую доступную
            $this->activeOperationId = array_key_first($this->operations);
        } else {
            // Если операций нет, закрываем модальное окно и переходим к списку
            $this->activeOperationId = null;
            $this->isModal = false;
            return $this->redirect(route('admin.operations.index'), navigate: true);
        }
    }

    public function removeOperation($operationId)
    {
        if (!isset($this->operations[$operationId])) {
            return; // Операция не найдена, ничего не делаем
        }
        
        // Удаляем операцию
        unset($this->operations[$operationId]);

        // Если удалили активную вкладку, нужно переключиться на другую
        if ($this->activeOperationId === $operationId) {
            if (!empty($this->operations)) {
                // Переключаемся на первую доступную вкладку
                $this->activeOperationId = array_key_first($this->operations);
            } else {
                // Если вкладок не осталось, закрываем модальное окно и перенаправляем
                $this->activeOperationId = null;
                $this->isModal = false;
                return $this->redirect(route('admin.operations.index'), navigate: true);
            }
        }
        // Если удалили НЕ активную вкладку, то activeOperationId остается прежним
        // и ничего дополнительно делать не нужно
    }

    public function removeOperationTab($operationId)
    {
        // Специальный метод для крестиков на вкладках
        if (!isset($this->operations[$operationId])) {
            return;
        }
        
        $isRemovingActive = ($this->activeOperationId === $operationId);
        
        // Удаляем операцию
        unset($this->operations[$operationId]);
        
        // Если удаляли активную операцию И остались еще операции
        if ($isRemovingActive && !empty($this->operations)) {
            $this->activeOperationId = array_key_first($this->operations);
        }
        
        // Если удаляли активную операцию И это была последняя операция
        if ($isRemovingActive && empty($this->operations)) {
            $this->activeOperationId = null;
            $this->isModal = false;
            return $this->redirect(route('admin.operations.index'), navigate: true);
        }
        
        // Если удаляли НЕ активную операцию - ничего дополнительно не делаем
    }

    public function addProductToCart($productId = null)
    {
        if (!$this->activeOperationId || !$productId) return;

        $product = Product::with('unit', 'elements.unit')->find($productId);
        if (!$product) return;

        $newItem = [
            'product_id' => $product->id,
            'name' => $product->name,
            'type' => $product->type,
            'unit' => $product->unit->short_name,
            'weight' => 1,
            'clogging' => $product->type === 'simple' ? ($product->clogging ?? 0) : 0,
            'price_per_unit' => $this->activeOperation['type'] === 'purchase' ? $product->purchase_price : $product->selling_price,
            'price' => 0,
            'elements' => [],
        ];

        if ($product->type === 'composite') {
            foreach ($product->elements as $element) {
                $newItem['elements'][] = [
                    'element_id' => $element->id,
                    'name' => $element->name,
                    'price' => $element->price,
                    'unit' => $element->unit->short_name,
                    'percentage' => 0,
                ];
            }
        }
        
        // Force a full array update to ensure Livewire detects the change.
        $operations = $this->operations;
        $operations[$this->activeOperationId]['cartItems'][] = $newItem;
        $this->operations = $operations;

        $this->product_to_add = null;
        $this->calculateTotals();
        $this->dispatch('focus-on-weight-input', index: count($this->operations[$this->activeOperationId]['cartItems']) - 1);
    }

    public function removeCartItem($index)
    {
        if (!$this->activeOperationId) return;
        
        // Force a full array update to ensure Livewire detects the change.
        $operations = $this->operations;
        unset($operations[$this->activeOperationId]['cartItems'][$index]);
        $operations[$this->activeOperationId]['cartItems'] = array_values($operations[$this->activeOperationId]['cartItems']);
        $this->operations = $operations;

        $this->calculateTotals();
    }

    public function updated($name, $value)
    {
        // This regex will match properties like:
        // operations.OP-123.cartItems.0.weight
        if (preg_match('/operations\.([a-zA-Z0-9-]+)\.cartItems\.(\d+)\.(.+)/', $name, $matches)) {
            
            $propertyPath = $matches[3]; // e.g., 'price_per_unit' or 'elements.0.price'

            // Round price fields to 2 decimal places upon input
            if ($propertyPath === 'price_per_unit') {
                data_set($this, $name, round($value, 2));
            } elseif (preg_match('/elements\.(\d+)\.price$/', $propertyPath)) {
                data_set($this, $name, round($value, 2));
            }

            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        if (!$this->activeOperationId || !isset($this->operations[$this->activeOperationId])) {
            return;
        }

        $totalAmount = 0;
        foreach ($this->operations[$this->activeOperationId]['cartItems'] as &$item) {
             $weight = (float)($item['weight'] ?? 0);
            $clogging = (float)($item['clogging'] ?? 0);
            $item['price'] = 0;

            if ($item['type'] === 'simple') {
                $effectiveWeight = $weight - ($weight * $clogging / 100);
                $price_per_unit = (float)($item['price_per_unit'] ?? 0);
                $item['price'] = $effectiveWeight * $price_per_unit;
            } else { // Composite product
                $itemPrice = 0;
                if (is_array($item['elements'])) {
                    foreach ($item['elements'] as $element) {
                        $percentage = (float)($element['percentage'] ?? 0);
                        $elementPricePerPercent = (float)($element['price'] ?? 0);
                        // Стоимость элемента = цена за 1% * процентное содержание * вес продукта
                        $elementPrice = $elementPricePerPercent * $percentage * $weight;
                        $itemPrice += $elementPrice;
                    }
                }
                $item['price'] = $itemPrice;
            }
            $totalAmount += (float)($item['price'] ?? 0);
        }
        $this->operations[$this->activeOperationId]['totalAmount'] = $totalAmount;
    }
    
    public function store()
    {
        if (!$this->activeOperationId) return;

        $activeOp = $this->activeOperation;

        $this->validate([
            'operations.'.$this->activeOperationId.'.user_id' => 'required|exists:users,id',
            'operations.'.$this->activeOperationId.'.type' => 'required|in:purchase,sale',
            'operations.'.$this->activeOperationId.'.cartItems' => 'required|array|min:1',
        ]);

        $this->calculateTotals(); // Recalculate just in case
        
        // Проверяем, это редактирование или создание новой операции
        $isEditing = isset($activeOp['is_editing']) && $activeOp['is_editing'] === true;
        
        $operation = DB::transaction(function () use ($activeOp, $isEditing) {
            // Получаем активную кассу
            $cashRegister = CashRegister::where('is_active', true)->first();
            if (!$cashRegister) {
                throw new \Exception('Активная касса не найдена');
            }

            if ($isEditing) {
                // Редактирование существующей операции
                $operation = Operation::with(['items.product', 'items.elements'])->find($activeOp['original_operation_id']);
                if (!$operation) {
                    throw new \Exception('Оригинальная операция не найдена');
                }

                // Возвращаем товары на склад (обратная операция)
                $this->revertStockChanges($operation);

                // Возвращаем деньги в кассу (обратная операция)
                $this->revertCashChanges($operation);

                // Удаляем старые элементы операции
                foreach($operation->items as $item) {
                    $item->elements()->delete();
                }
                $operation->items()->delete();

                // Обновляем основные данные операции
                $operation->update([
                    'user_id' => $activeOp['user_id'],
                    'type' => $activeOp['type'],
                    'total_amount' => $this->operations[$this->activeOperationId]['totalAmount'],
                ]);
            } else {
                // Создание новой операции
                $operation = Operation::create([
                    'user_id' => $activeOp['user_id'],
                    'type' => $activeOp['type'],
                    'total_amount' => $this->operations[$this->activeOperationId]['totalAmount'],
                    'cash_register_id' => $cashRegister->id,
                ]);

                // Generate and save the human-readable operation number
                $prefix = $operation->type === 'purchase' ? 'PUR' : 'SAL';
                $operation->operation_number = sprintf('%s-%06d', $prefix, $operation->id);
                $operation->save();
            }

            // Создаем транзакцию в кассе
            $transactionDescription = $operation->type === 'purchase' 
                ? "Покупка металла (операция {$operation->operation_number})" 
                : "Продажа металла (операция {$operation->operation_number})";

            if ($isEditing) {
                $transactionDescription = $operation->type === 'purchase' 
                    ? "Редактирование покупки металла (операция {$operation->operation_number})" 
                    : "Редактирование продажи металла (операция {$operation->operation_number})";
            }

            $transaction = null;
            if ($operation->type === 'purchase') {
                // При покупке - снимаем деньги из кассы
                $transaction = $cashRegister->withdrawMoney(
                    $operation->total_amount,
                    $transactionDescription,
                    $operation->user_id
                );
            } else {
                // При продаже - добавляем деньги в кассу
                $transaction = $cashRegister->addMoney(
                    $operation->total_amount,
                    $transactionDescription,
                    $operation->user_id
                );
            }

            // Привязываем транзакцию к операции
            if ($transaction) {
                $transaction->update(['operation_id' => $operation->id]);
            }

            foreach ($activeOp['cartItems'] as $cartItem) {
                $operationItem = $operation->items()->create([
                    'product_id' => $cartItem['product_id'],
                    'weight' => $cartItem['weight'],
                    'clogging' => $cartItem['clogging'],
                    'price' => $cartItem['price'],
                ]);
                
                // === STOCK MANAGEMENT LOGIC START ===
                $product = Product::with('unit')->find($cartItem['product_id']);
                if (!$product) continue;

                $weight = (float)$cartItem['weight'];
                $multiplier = $operation->type === 'purchase' ? 1 : -1;

                // Validate and update product stock for BOTH simple and composite
                if ($operation->type === 'sale' && $product->stock < $weight) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cart' => "Недостаточно товара '{$product->name}' на складе. В наличии: {$product->stock} {$product->unit->short_name}.",
                    ]);
                }
                $product->increment('stock', $weight * $multiplier);

                // If composite, ALSO validate and update element stocks
                if ($product->type === 'composite' && !empty($cartItem['elements'])) {
                    foreach ($cartItem['elements'] as $elementData) {
                        $element = Element::with('unit')->find($elementData['element_id']);
                        if ($element && isset($elementData['percentage']) && $elementData['percentage'] > 0) {
                            $elementWeight = $weight * ((float)$elementData['percentage'] / 100);
                            if ($operation->type === 'sale' && $element->stock < $elementWeight) {
                                throw \Illuminate\Validation\ValidationException::withMessages([
                                    'cart' => "Недостаточно элемента '{$element->name}' для '{$product->name}'. В наличии: {$element->stock} {$element->unit->name}.",
                                ]);
                            }
                            $element->increment('stock', $elementWeight * $multiplier);
                        }
                    }
                }
                // === STOCK MANAGEMENT LOGIC END ===

                if ($cartItem['type'] === 'composite' && !empty($cartItem['elements'])) {
                    foreach ($cartItem['elements'] as $element) {
                        if(isset($element['percentage']) && $element['percentage'] > 0) {
                            $operationItem->elements()->create([
                                'element_id' => $element['element_id'],
                                'percentage' => $element['percentage'],
                            ]);
                        }
                    }
                }
            }
            
            return $operation;
        });
        
        $this->notification = $isEditing 
            ? 'Операция ' . $operation->operation_number . ' успешно обновлена!' 
            : 'Операция ' . $operation->operation_number . ' успешно сохранена!';
        
        // Удаляем текущую операцию из сессии после сохранения
        $currentOperationId = $this->activeOperationId;
        unset($this->operations[$currentOperationId]);
        
        // Проверяем, есть ли другие операции в сессии
        if (!empty($this->operations)) {
            // Если есть операции, переключаемся на первую доступную
            $this->activeOperationId = array_key_first($this->operations);
        } else {
            // Если операций нет, закрываем модальное окно и переходим к списку
            $this->activeOperationId = null;
            $this->isModal = false;
            $this->dispatch('operation-saved');
            return $this->redirect(route('admin.operations.index'), navigate: true);
        }
        
        $this->dispatch('operation-saved');
    }

    public function startNewOperation()
    {
        $this->notification = null;
        
        // Проверяем, есть ли существующие операции в сессии
        if (!empty($this->operations)) {
            // Если есть операции, переключаемся на первую доступную
            $this->activeOperationId = array_key_first($this->operations);
        } else {
            // Если операций нет, создаем новую
            $this->addNewOperation('purchase');
        }
    }

    // Helper methods (convertToGrams, convertPriceToPerGram) remain the same
    private function convertToGrams($weight, $unit)
    {
        $unit_clean = mb_strtolower(trim($unit));
        if (str_contains($unit_clean, 'кг') || str_contains($unit_clean, 'kg')) {
            return $weight * 1000;
        }
        return $weight;
    }

    private function convertPriceToPerGram($price, $unit)
    {
        $unit_clean = mb_strtolower(trim($unit));
        if (str_contains($unit_clean, 'кг') || str_contains($unit_clean, 'kg')) {
            return $price / 1000;
        }
        return $price;
    }

    /**
     * Восстанавливает изменения в остатках товаров на складе
     */
    private function revertStockChanges(Operation $operation)
    {
        $multiplier = $operation->type === 'purchase' ? -1 : 1; // Reverse the operation

        foreach ($operation->items as $item) {
            $product = $item->product;
            if (!$product) continue;

            $weight = (float)$item->weight;

            // Revert product stock for BOTH simple and composite
            $product->increment('stock', $weight * $multiplier);

            // If composite, ALSO revert element stocks
            if ($product->type === 'composite') {
                foreach ($item->elements as $operationItemElement) {
                    $element = Element::find($operationItemElement->element_id);
                    if ($element) {
                        $elementWeight = $weight * ((float)$operationItemElement->percentage / 100);
                        $element->increment('stock', $elementWeight * $multiplier);
                    }
                }
            }
        }
    }

    /**
     * Восстанавливает изменения в кассе
     */
    private function revertCashChanges(Operation $operation)
    {
        if (!$operation->cashRegister) return;

        $reverseDescription = $operation->type === 'purchase' 
            ? "Возврат средств при редактировании покупки (операция {$operation->operation_number})" 
            : "Списание средств при редактировании продажи (операция {$operation->operation_number})";

        if ($operation->type === 'purchase') {
            // При отмене покупки - возвращаем деньги в кассу
            $operation->cashRegister->addMoney(
                $operation->total_amount,
                $reverseDescription,
                auth()->id()
            );
        } else {
            // При отмене продажи - снимаем деньги из кассы
            try {
                $operation->cashRegister->withdrawMoney(
                    $operation->total_amount,
                    $reverseDescription,
                    auth()->id()
                );
            } catch (\Exception $e) {
                // Если недостаточно денег в кассе, все равно продолжаем редактирование
                // но можно добавить предупреждение
            }
        }

        // Удаляем старые транзакции, связанные с этой операцией
        $operation->transactions()->delete();
    }

    public function edit($id)
    {
        $operation = Operation::with(['items.product.unit', 'items.elements.element.unit'])->find($id);
        
        if (!$operation) {
            session()->flash('error', 'Операция не найдена.');
            return;
        }

        // Создаем новую вкладку для редактирования на основе существующей операции
        $editId = $this->generateOperationId($operation->type) . '-EDIT';
        
        // Преобразуем существующую операцию в формат корзины
        $cartItems = [];
        foreach ($operation->items as $item) {
            $cartItem = [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'type' => $item->product->type,
                'unit' => $item->product->unit->short_name,
                'weight' => $item->weight,
                'clogging' => $item->product->type === 'simple' ? ($item->clogging ?? 0) : 0,
                'price_per_unit' => $item->product->type === 'simple' 
                    ? ($operation->type === 'purchase' ? $item->product->purchase_price : $item->product->selling_price)
                    : 0,
                'price' => $item->price,
                'elements' => [],
            ];

            // Для составных продуктов добавляем элементы
            if ($item->product->type === 'composite') {
                foreach ($item->product->elements as $productElement) {
                    $elementPercentage = 0;
                    
                    // Ищем процент для этого элемента в сохраненной операции
                    $savedElement = $item->elements->firstWhere('element_id', $productElement->id);
                    if ($savedElement) {
                        $elementPercentage = $savedElement->percentage;
                    }
                    
                    $cartItem['elements'][] = [
                        'element_id' => $productElement->id,
                        'name' => $productElement->name,
                        'price' => $productElement->price,
                        'unit' => $productElement->unit->short_name,
                        'percentage' => $elementPercentage,
                    ];
                }
            }
            
            $cartItems[] = $cartItem;
        }

        // Создаем новую операцию для редактирования
        $this->operations[$editId] = [
            'id' => $editId,
            'type' => $operation->type,
            'user_id' => $operation->user_id,
            'cartItems' => $cartItems,
            'totalAmount' => $operation->total_amount,
            'original_operation_id' => $operation->id, // Сохраняем ID оригинальной операции
            'is_editing' => true,
        ];
        
        $this->activeOperationId = $editId;
        $this->isModal = true;
        
        // Пересчитываем итоги для корректности
        $this->calculateTotals();
    }

    public function delete($id)
    {
        $operation = Operation::with('items.product', 'items.elements', 'cashRegister', 'transactions')->find($id);

        if (!$operation) {
            session()->flash('error', 'Операция не найдена.');
            return;
        }

        DB::transaction(function () use ($operation) {
            $multiplier = $operation->type === 'purchase' ? -1 : 1; // Reverse the operation

            foreach ($operation->items as $item) {
                $product = $item->product;
                if (!$product) continue;

                $weight = (float)$item->weight;

                // Revert product stock for BOTH simple and composite
                $product->increment('stock', $weight * $multiplier);

                // If composite, ALSO revert element stocks
                if ($product->type === 'composite') {
                    foreach ($item->elements as $operationItemElement) {
                        $element = Element::find($operationItemElement->element_id);
                        if ($element) {
                            $elementWeight = $weight * ((float)$operationItemElement->percentage / 100);
                            $element->increment('stock', $elementWeight * $multiplier);
                        }
                    }
                }
            }

            // Возвращаем деньги в кассу (делаем обратную операцию)
            if ($operation->cashRegister) {
                $reverseDescription = $operation->type === 'purchase' 
                    ? "Возврат средств при отмене покупки (операция {$operation->operation_number})" 
                    : "Списание средств при отмене продажи (операция {$operation->operation_number})";

                if ($operation->type === 'purchase') {
                    // При отмене покупки - возвращаем деньги в кассу
                    $operation->cashRegister->addMoney(
                        $operation->total_amount,
                        $reverseDescription,
                        auth()->id()
                    );
                } else {
                    // При отмене продажи - снимаем деньги из кассы
                    try {
                        $operation->cashRegister->withdrawMoney(
                            $operation->total_amount,
                            $reverseDescription,
                            auth()->id()
                        );
                    } catch (\Exception $e) {
                        // Если недостаточно денег в кассе, все равно удаляем операцию но уведомляем
                        session()->flash('warning', 'Недостаточно средств в кассе для полного возврата. Операция удалена, но остаток кассы может быть отрицательным.');
                    }
                }
            }
            
            // Manually delete related items to be safe
            foreach($operation->items as $item) {
                $item->elements()->delete();
            }
            $operation->items()->delete();
            
            // Удаляем связанные транзакции кассы
            $operation->transactions()->delete();
            
            $operation->delete();
        });

        session()->flash('message', 'Операция ' . $operation->operation_number . ' успешно удалена, остатки на складе и кассе восстановлены.');
    }

    public function clearAllOperations()
    {
        $this->operations = [];
        $this->activeOperationId = null;
        $this->isModal = false;
        return $this->redirect(route('admin.operations.index'), navigate: true);
    }

    public function openProductModal()
    {
        $this->showProductModal = true;
        $this->productSearch = '';
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->productSearch = '';
    }

    public function selectProductFromCard($productId)
    {
        $this->addProductToCart($productId);
        $this->closeProductModal();
    }

    public function showOperationDetails($operationId)
    {
        $this->selectedOperation = Operation::with(['user', 'items.product.unit', 'items.elements.element.unit'])->find($operationId);
        $this->showOperationDetailsModal = true;
    }

    public function closeOperationDetailsModal()
    {
        $this->showOperationDetailsModal = false;
        $this->selectedOperation = null;
    }
}
