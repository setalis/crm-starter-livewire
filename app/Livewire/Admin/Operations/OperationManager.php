<?php

namespace App\Livewire\Admin\Operations;

use App\Models\Element;
use App\Models\Operation;
use App\Models\Product;
use App\Models\User;
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
    
    // Product selection
    public ?int $product_to_add = null;

    public function mount(?string $type = null)
    {
        if ($type) {
            // This is for /operations/create/{type} routes
            $this->addNewOperation($type);
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
        $products = Product::where('is_published', true)->orderBy('name')->get();
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
        $this->isModal = false;
        // Данные сессии не трогаем, чтобы можно было вернуться к работе
    }

    public function removeOperation($operationId)
    {
        if (isset($this->operations[$operationId])) {
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
        }
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
            'clogging' => $product->clogging,
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
                    $product_weight_in_grams = $this->convertToGrams($weight, $item['unit']);
                    
                    foreach ($item['elements'] as $element) {
                        $percentage = (float)($element['percentage'] ?? 0);
                        $element_weight_in_grams = $product_weight_in_grams * ($percentage / 100);
                        $element_price_per_gram = $this->convertPriceToPerGram((float)($element['price'] ?? 0), $element['unit']);
                        $itemPrice += $element_weight_in_grams * $element_price_per_gram;
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
        
        $operation = DB::transaction(function () use ($activeOp) {
            $operation = Operation::create([
                'user_id' => $activeOp['user_id'],
                'type' => $activeOp['type'],
                'total_amount' => $this->operations[$this->activeOperationId]['totalAmount'],
            ]);

            // Generate and save the human-readable operation number
            $prefix = $operation->type === 'purchase' ? 'PUR' : 'SAL';
            $operation->operation_number = sprintf('%s-%06d', $prefix, $operation->id);
            $operation->save();

            foreach ($activeOp['cartItems'] as $cartItem) {
                $operationItem = $operation->items()->create([
                    'product_id' => $cartItem['product_id'],
                    'weight' => $cartItem['weight'],
                    'clogging' => $cartItem['clogging'],
                    'price' => $cartItem['price'],
                ]);

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
        
        $this->notification = 'Операция ' . $operation->operation_number . ' успешно сохранена!';
        
        // Clear the cart for the current operation tab
        $this->operations[$this->activeOperationId]['cartItems'] = [];
        $this->operations[$this->activeOperationId]['totalAmount'] = 0;
        
        $this->dispatch('operation-saved');
    }

    public function startNewOperation()
    {
        $this->notification = null;
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

    // Delete and Edit need to be refactored to work with the new structure
    // For now, these are placeholders or might need to be removed from the view
    public function edit($id)
    {
        // This needs a complete rethink. How do you edit a saved operation
        // in this new multi-tab interface? Does it create a new tab?
    }

    public function delete($id)
    {
        Operation::find($id)->delete();
    }

    public function clearAllOperations()
    {
        $this->operations = [];
        $this->activeOperationId = null;
        $this->isModal = false;
        return $this->redirect(route('admin.operations.index'), navigate: true);
    }
}
