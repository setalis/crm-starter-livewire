<?php

namespace App\Livewire\Admin\Shipments;

use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Livewire\Component;

class ShipmentManager extends Component
{
    public $car_number;
    public $driver_name;
    public $company;
    public $comment;
    public $user_comment = '';
    public $shipmentItems = [];
    public $product_id;
    public $weight;
    public $writeoff_type = 'partial';
    public $stock_after;
    public $products;
    public $shipments;
    public $isModal = false;
    public $isConfirmModal = false;
    public $confirmShipmentId;
    public $confirmShipment;
    public $confirmItems = [];
    public $filterStatus = '';
    public $filterCompany = '';
    public $isDetailsModal = false;
    public $detailsShipment;
    public $editMode = false;
    public $editShipmentId;
    public $shipping_cost = 0;
    public $editingItemIndex = null;

    public function mount()
    {
        $this->products = Product::all();
        $this->shipments = Shipment::with('items')->orderByDesc('created_at')->get();
    }

    public function addShipmentItem()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'weight' => 'required|numeric|min:0.01',
            'writeoff_type' => 'required|in:partial,full',
            'stock_after' => 'nullable|numeric|min:0',
        ]);
        
        $this->shipmentItems[] = [
            'product_id' => $this->product_id,
            'weight' => $this->weight,
            'writeoff_type' => $this->writeoff_type,
            'stock_after' => $this->stock_after,
        ];
        
        // Сбрасываем форму и режим редактирования
        $this->cancelEditItem();
    }

    public function removeShipmentItem($index)
    {
        unset($this->shipmentItems[$index]);
        $this->shipmentItems = array_values($this->shipmentItems);
    }

    public function editShipmentItem($index)
    {
        // Сбрасываем все поля перед заполнением
        $this->cancelEditItem();
        
        $this->editingItemIndex = $index;
        $item = $this->shipmentItems[$index];
        
        $this->product_id = $item['product_id'];
        $this->weight = $item['weight'];
        $this->writeoff_type = $item['writeoff_type'];
        $this->stock_after = $item['stock_after'];
        
        // Принудительно обновляем компонент
        $this->dispatch('refresh');
    }

    public function updateShipmentItem()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'weight' => 'required|numeric|min:0.01',
            'writeoff_type' => 'required|in:partial,full',
            'stock_after' => 'nullable|numeric|min:0',
        ]);

        if ($this->editingItemIndex !== null) {
            $this->shipmentItems[$this->editingItemIndex] = [
                'id' => $this->shipmentItems[$this->editingItemIndex]['id'] ?? null,
                'product_id' => $this->product_id,
                'weight' => $this->weight,
                'writeoff_type' => $this->writeoff_type,
                'stock_after' => $this->stock_after,
                'expected_stock_before' => $this->shipmentItems[$this->editingItemIndex]['expected_stock_before'] ?? null,
                'actual_stock_before' => $this->shipmentItems[$this->editingItemIndex]['actual_stock_before'] ?? null,
                'stock_discrepancy' => $this->shipmentItems[$this->editingItemIndex]['stock_discrepancy'] ?? null,
            ];
            $this->cancelEditItem();
        }
    }

    public function cancelEditItem()
    {
        $this->editingItemIndex = null;
        $this->product_id = null;
        $this->weight = null;
        $this->writeoff_type = 'partial';
        $this->stock_after = null;
    }

    public function openModal($shipmentId = null)
    {
        // Проверяем права доступа
        if ($shipmentId && !auth()->user()->can('shipments.edit')) {
            session()->flash('error', 'У вас нет прав для редактирования отгрузок.');
            return;
        }
        
        if (!$shipmentId && !auth()->user()->can('shipments.create')) {
            session()->flash('error', 'У вас нет прав для создания отгрузок.');
            return;
        }
        
        $this->isModal = true;
        $this->editMode = false;
        if ($shipmentId) {
            $shipment = Shipment::with('items')->findOrFail($shipmentId);
            $this->editMode = true;
            $this->editShipmentId = $shipmentId;
            $this->car_number = $shipment->car_number;
            $this->driver_name = $shipment->driver_name;
            $this->company = $shipment->company;
            $this->comment = $shipment->comment;
            $this->user_comment = '';
            $this->shipping_cost = $shipment->shipping_cost ?? 0;
            $this->shipmentItems = [];
            foreach ($shipment->items as $item) {
                $this->shipmentItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'weight' => $item->weight,
                    'writeoff_type' => $item->writeoff_type,
                    'stock_after' => $item->stock_after,
                    'expected_stock_before' => $item->expected_stock_before,
                    'actual_stock_before' => $item->actual_stock_before,
                    'stock_discrepancy' => $item->stock_discrepancy,
                ];
            }
        } else {
            $this->resetForm();
        }
    }

    public function closeModal()
    {
        $this->isModal = false;
        $this->resetForm();
    }

    public function saveShipment()
    {
        // Проверяем права доступа
        if ($this->editMode && !auth()->user()->can('shipments.edit')) {
            session()->flash('error', 'У вас нет прав для редактирования отгрузок.');
            return;
        }
        
        if (!$this->editMode && !auth()->user()->can('shipments.create')) {
            session()->flash('error', 'У вас нет прав для создания отгрузок.');
            return;
        }
        
        $this->validate([
            'car_number' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'company' => 'nullable|string',
            'shipmentItems' => 'required|array|min:1',
            'shipmentItems.*.product_id' => 'required|exists:products,id',
            'shipmentItems.*.weight' => 'required|numeric|min:0.01',
            'shipmentItems.*.writeoff_type' => 'required|in:partial,full',
            'shipmentItems.*.stock_after' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        if ($this->editMode && $this->editShipmentId) {
            $shipment = Shipment::findOrFail($this->editShipmentId);
            
            // ВАЖНО: Сначала возвращаем товары на склад от предыдущих позиций
            foreach ($shipment->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    // Возвращаем вес товара обратно на склад
                    $product->stock += $oldItem->weight;
                    // Восстанавливаем склад до того состояния, которое было до предыдущего списания
                    $expectedBefore = $oldItem->expected_stock_before ?? 0;
                    $actualBefore = $oldItem->actual_stock_before ?? 0;
                    $discrepancy = $oldItem->stock_discrepancy ?? 0;
                    
                    // Восстанавливаем к исходному состоянию
                    $product->stock = $expectedBefore;
                    $product->save();
                }
            }
            
            // Теперь проверяем остатки на складе для новых позиций
            foreach ($this->shipmentItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock < $item['weight']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'shipmentItems' => "Недостаточно товара '{$product->name}' на складе. Доступно: {$product->stock} кг, запрошено: {$item['weight']} кг",
                    ]);
                }
            }
            
            $shipment->update([
                'car_number' => $this->car_number,
                'driver_name' => $this->driver_name,
                'company' => $this->company,
                'comment' => $this->comment,
                'shipping_cost' => $this->shipping_cost ?? 0,
            ]);
            $shipment->items()->delete();
            
            // Добавляем новые позиции с учетом остатков
            foreach ($this->shipmentItems as $item) {
                $product = Product::find($item['product_id']);
                $expectedStockBefore = $product->stock;
                $actualStockBefore = $product->stock;
                
                // Списываем товар со склада
                $product->stock -= $item['weight'];
                
                // Устанавливаем новый остаток в зависимости от типа списания
                if ($item['writeoff_type'] === 'full') {
                    $newStock = 0;
                } else {
                    $newStock = $item['stock_after'] ?? $product->stock;
                }
                
                $stockDiscrepancy = $newStock - $product->stock;
                $product->stock = $newStock;
                $product->save();
                
                $item['expected_stock_before'] = $expectedStockBefore;
                $item['actual_stock_before'] = $actualStockBefore;
                $item['stock_discrepancy'] = $stockDiscrepancy;
                
                $shipment->items()->create($item);
            }
        } else {
            // Для новой отгрузки проверяем остатки на складе
            foreach ($this->shipmentItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock < $item['weight']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'shipmentItems' => "Недостаточно товара '{$product->name}' на складе. Доступно: {$product->stock} кг, запрошено: {$item['weight']} кг",
                    ]);
                }
            }
            
            $shipment = Shipment::create([
                'user_id' => auth()->id(),
                'car_number' => $this->car_number,
                'driver_name' => $this->driver_name,
                'company' => $this->company,
                'comment' => $this->comment,
                'shipping_cost' => $this->shipping_cost ?? 0,
                'stage' => 'draft',
            ]);
            
            // Добавляем позиции с учетом остатков
            foreach ($this->shipmentItems as $item) {
                $product = Product::find($item['product_id']);
                $expectedStockBefore = $product->stock;
                $actualStockBefore = $product->stock;
                
                // Списываем товар со склада
                $product->stock -= $item['weight'];
                
                // Устанавливаем новый остаток в зависимости от типа списания
                if ($item['writeoff_type'] === 'full') {
                    $newStock = 0;
                } else {
                    $newStock = $item['stock_after'] ?? $product->stock;
                }
                
                $stockDiscrepancy = $newStock - $product->stock;
                $product->stock = $newStock;
                $product->save();
                
                $item['expected_stock_before'] = $expectedStockBefore;
                $item['actual_stock_before'] = $actualStockBefore;
                $item['stock_discrepancy'] = $stockDiscrepancy;
                
                $shipment->items()->create($item);
            }

            // Добавляем системный комментарий
            if ($this->editMode) {
                $shipment->addSystemComment(
                    "Отгрузка отредактирована пользователем " . auth()->user()->name,
                    [
                        'action' => 'edit',
                        'items_count' => count($this->shipmentItems),
                        'company' => $this->company
                    ]
                );
            } else {
                $shipment->addSystemComment(
                    "Отгрузка создана пользователем " . auth()->user()->name,
                    [
                        'action' => 'create',
                        'items_count' => count($this->shipmentItems),
                        'company' => $this->company
                    ]
                );
            }

            // Добавляем пользовательский комментарий если есть
            if (!empty($this->user_comment)) {
                $shipment->addComment(
                    $this->user_comment,
                    'comment',
                    false
                );
                $this->dispatch('comment-added');
            }
        }
        session()->flash('message', $this->editMode ? 'Отгрузка успешно обновлена!' : 'Отгрузка успешно создана!');
        $this->closeModal();
        $this->applyFilters();
    }

    private function resetForm()
    {
        $this->car_number = null;
        $this->driver_name = null;
        $this->company = null;
        $this->comment = null;
        $this->user_comment = '';
        $this->shipmentItems = [];
        $this->product_id = null;
        $this->weight = null;
        $this->writeoff_type = 'partial';
        $this->stock_after = null;
        $this->shipping_cost = 0;
        $this->editingItemIndex = null;
        $this->editMode = false;
        $this->editShipmentId = null;
    }

    public function openConfirmModal($shipmentId)
    {
        // Проверяем права доступа
        if (!auth()->user()->can('shipments.confirm')) {
            session()->flash('error', 'У вас нет прав для подтверждения отгрузок.');
            return;
        }
        
        $this->confirmShipmentId = $shipmentId;
        $this->confirmShipment = Shipment::with('items')->findOrFail($shipmentId);
        $this->confirmItems = [];
        foreach ($this->confirmShipment->items as $item) {
            $this->confirmItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'weight' => $item->weight,
                'actual_weight' => $item->actual_weight,
                'actual_price' => $item->actual_price,
                'actual_clogging' => $item->actual_clogging,
                'expected_stock_before' => $item->expected_stock_before,
                'actual_stock_before' => $item->actual_stock_before,
                'stock_discrepancy' => $item->stock_discrepancy,
            ];
        }
        $this->isConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->isConfirmModal = false;
        $this->confirmShipmentId = null;
        $this->confirmShipment = null;
        $this->confirmItems = [];
    }

    public function saveConfirmation()
    {
        // Проверяем права доступа
        if (!auth()->user()->can('shipments.confirm')) {
            session()->flash('error', 'У вас нет прав для подтверждения отгрузок.');
            return;
        }
        
        foreach ($this->confirmItems as $itemData) {
            $item = ShipmentItem::find($itemData['id']);
            if ($item) {
                $item->actual_weight = $itemData['actual_weight'];
                $item->actual_price = $itemData['actual_price'];
                $item->actual_clogging = $itemData['actual_clogging'];
                $item->save();
            }
        }
        $shipment = Shipment::find($this->confirmShipmentId);
        $shipment->stage = 'confirmed';
        $shipment->save();
        $this->closeConfirmModal();
        $this->applyFilters();
        session()->flash('message', 'Фактические данные успешно сохранены и отгрузка подтверждена!');
    }

    public function updatedFilterStatus()
    {
        $this->applyFilters();
    }
    public function updatedFilterCompany()
    {
        $this->applyFilters();
    }
    public function applyFilters()
    {
        $query = Shipment::with('items');
        if ($this->filterStatus) {
            $query->where('stage', $this->filterStatus);
        }
        if ($this->filterCompany) {
            $query->where('company', 'like', '%'.$this->filterCompany.'%');
        }
        $this->shipments = $query->orderByDesc('created_at')->get();
    }

    public function openDetailsModal($shipmentId)
    {
        $this->detailsShipment = Shipment::with('items')->findOrFail($shipmentId);
        $this->isDetailsModal = true;
    }
    public function closeDetailsModal()
    {
        $this->isDetailsModal = false;
        $this->detailsShipment = null;
    }

    public function getShipmentRevenue($shipment)
    {
        $revenue = 0;
        foreach ($shipment->items as $item) {
            $actualWeight = $item->actual_weight ?? 0;
            $actualPrice = $item->actual_price ?? 0;
            $actualClogging = $item->actual_clogging ?? 0;
            
            // Вычисляем чистый вес без засора
            $cleanWeight = $actualWeight * (1 - ($actualClogging / 100));
            
            $revenue += $cleanWeight * $actualPrice;
        }
        return $revenue;
    }

    public function getShipmentProfit($shipment)
    {
        // Валовая выручка
        $revenue = $this->getShipmentRevenue($shipment);
        
        // Расчетные затраты на товары
        $costs = 0;
        foreach ($shipment->items as $item) {
            $purchase = $item->product->average_purchase_price ?? 0;
            $clogging = $item->product->clogging ?? 0;
            
            // Правильная формула: Чистый вес × Средняя цена
            $cleanWeight = $item->weight * (1 - ($clogging / 100));
            $cost = $cleanWeight * $purchase;
            
            $costs += $cost;
        }
        
        // Затраты на отгрузку
        $shippingCost = $shipment->shipping_cost ?? 0;
        
        // Чистая прибыль = Валовая выручка - Затраты на товары - Затраты на отгрузку
        return $revenue - $costs - $shippingCost;
    }

    public function getItemProfit($item)
    {
        $purchase = $item->product->average_purchase_price ?? 0;
        $clogging = $item->product->clogging ?? 0;
        
        // Правильный расчет затрат: Чистый вес × Средняя цена
        $cleanWeight = $item->weight * (1 - ($clogging / 100));
        $cost = $cleanWeight * $purchase;
        
        // Правильный расчет дохода с учетом фактического засора
        $actualWeight = $item->actual_weight ?? 0;
        $actualPrice = $item->actual_price ?? 0;
        $actualClogging = $item->actual_clogging ?? 0;
        
        // Чистый вес = фактический вес × (1 - засор%)
        $actualCleanWeight = $actualWeight * (1 - ($actualClogging / 100));
        $income = $actualCleanWeight * $actualPrice;
        
        return $income - $cost;
    }

    public function exportShipments()
    {
        // Проверяем права доступа
        if (!auth()->user()->can('shipments.export')) {
            session()->flash('error', 'У вас нет прав для экспорта отчетов по отгрузкам.');
            return;
        }
        
        $shipments = $this->shipments;
        
        $csvData = [];
        $csvData[] = [
            'ID отгрузки',
            'Дата',
            'Предприятие',
            'Номер авто',
            'Водитель',
            'Статус',
            'Список металлов',
            'Заявленный вес (кг)',
            'Фактический вес (кг)',
            'Расчетные затраты',
            'Валовая выручка',
            'Затраты на отгрузку',
            'Чистая прибыль',
            'Рентабельность (%)',
            'Общее расхождение склада (кг)',
            'Комментарий'
        ];
        
        foreach ($shipments as $shipment) {
            $metals = [];
            $declaredWeight = 0;
            $totalActualWeight = 0;
            $calculatedCosts = 0;
            $actualIncome = 0;
            $totalDiscrepancy = 0;
            
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Правильная формула затрат: Чистый вес × Средняя цена
                $cleanWeightCost = $item->weight * (1 - ($clogging / 100));
                $itemCost = $cleanWeightCost * $purchase;
                
                // Вычисляем валовую выручку с учетом засора
                $itemActualWeight = $item->actual_weight ?? 0;
                $actualPrice = $item->actual_price ?? 0;
                $actualClogging = $item->actual_clogging ?? 0;
                $cleanWeight = $itemActualWeight * (1 - ($actualClogging / 100));
                $itemIncome = $cleanWeight * $actualPrice;
                
                $metals[] = $product?->name . ' (' . $item->weight . ' кг)';
                $declaredWeight += $item->weight;
                $totalActualWeight += $itemActualWeight;
                $calculatedCosts += $itemCost;
                $actualIncome += $itemIncome;
                $totalDiscrepancy += $item->stock_discrepancy ?? 0;
            }
            
            $shippingCost = $shipment->shipping_cost ?? 0;
            $netProfit = $actualIncome - $calculatedCosts - $shippingCost;
            $profitability = $calculatedCosts > 0 ? ($netProfit / $calculatedCosts) * 100 : 0;
            
            $csvData[] = [
                $shipment->id,
                $shipment->created_at->format('d.m.Y H:i'),
                $shipment->company ?: 'Не указано',
                $shipment->car_number ?: 'Не указано',
                $shipment->driver_name ?: 'Не указано',
                $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено',
                implode('; ', $metals),
                number_format($declaredWeight, 2),
                number_format($totalActualWeight, 2),
                number_format($calculatedCosts, 2),
                number_format($actualIncome, 2),
                number_format($shippingCost, 2),
                number_format($netProfit, 2),
                number_format($profitability, 2),
                number_format($totalDiscrepancy, 2),
                $shipment->comment ?: '',
            ];
        }
        
        $filename = 'shipments_detailed_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Добавляем BOM для корректного отображения UTF-8 в Excel
        fwrite($handle, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row, ';');
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response()->streamDownload(function() use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportShipmentsExcel()
    {
        // Проверяем права доступа
        if (!auth()->user()->can('shipments.export')) {
            session()->flash('error', 'У вас нет прав для экспорта отчетов по отгрузкам.');
            return;
        }
        
        $shipments = $this->shipments;
        
        $data = [];
        $data[] = [
            'ID отгрузки',
            'Дата',
            'Предприятие',
            'Номер авто',
            'Водитель',
            'Статус',
            'Список металлов',
            'Заявленный вес (кг)',
            'Фактический вес (кг)',
            'Расчетные затраты',
            'Валовая выручка',
            'Затраты на отгрузку',
            'Чистая прибыль',
            'Рентабельность (%)',
            'Комментарий'
        ];
        
        foreach ($shipments as $shipment) {
            $metals = [];
            $declaredWeight = 0;
            $totalActualWeight = 0;
            $calculatedCosts = 0;
            $actualIncome = 0;
            
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Правильная формула затрат: Чистый вес × Средняя цена
                $cleanWeightCost = $item->weight * (1 - ($clogging / 100));
                $itemCost = $cleanWeightCost * $purchase;
                
                // Вычисляем валовую выручку с учетом засора
                $itemActualWeight = $item->actual_weight ?? 0;
                $actualPrice = $item->actual_price ?? 0;
                $actualClogging = $item->actual_clogging ?? 0;
                $cleanWeight = $itemActualWeight * (1 - ($actualClogging / 100));
                $itemIncome = $cleanWeight * $actualPrice;
                
                $metals[] = $product?->name . ' (' . $item->weight . ' кг)';
                $declaredWeight += $item->weight;
                $totalActualWeight += $itemActualWeight;
                $calculatedCosts += $itemCost;
                $actualIncome += $itemIncome;
            }
            
            $shippingCost = $shipment->shipping_cost ?? 0;
            $netProfit = $actualIncome - $calculatedCosts - $shippingCost;
            $profitability = $calculatedCosts > 0 ? ($netProfit / $calculatedCosts) * 100 : 0;
            
            $data[] = [
                $shipment->id,
                $shipment->created_at->format('d.m.Y H:i'),
                $shipment->company ?: 'Не указано',
                $shipment->car_number ?: 'Не указано',
                $shipment->driver_name ?: 'Не указано',
                $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено',
                implode('; ', $metals),
                number_format($declaredWeight, 2),
                number_format($totalActualWeight, 2),
                number_format($calculatedCosts, 2),
                number_format($actualIncome, 2),
                number_format($shippingCost, 2),
                number_format($netProfit, 2),
                number_format($profitability, 2),
                $shipment->comment ?: '',
            ];
        }
        
        $filename = 'shipments_detailed_' . date('Y-m-d_H-i-s') . '.xls';
        
        // Создаем HTML таблицу с улучшенным стилем
        $html = '<html><head><meta charset="UTF-8">';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
        $html .= 'th { background-color: #f2f2f2; font-weight: bold; border: 1px solid #ddd; padding: 8px; text-align: left; }';
        $html .= 'td { border: 1px solid #ddd; padding: 8px; }';
        $html .= '.number { text-align: right; }';
        $html .= '.profit-positive { color: green; font-weight: bold; }';
        $html .= '.profit-negative { color: red; font-weight: bold; }';
        $html .= '</style>';
        $html .= '</head><body>';
        $html .= '<h2>Отчет по отгрузкам - ' . date('d.m.Y H:i') . '</h2>';
        $html .= '<table>';
        
        $isHeader = true;
        foreach ($data as $row) {
            if ($isHeader) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<th>' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</th>';
                }
                $html .= '</tr>';
                $isHeader = false;
            } else {
                $html .= '<tr>';
                foreach ($row as $index => $cell) {
                    $class = '';
                    // Добавляем классы для числовых колонок
                    if (in_array($index, [7, 8, 9, 10, 11, 12, 13])) {
                        $class = 'number';
                        // Специальная обработка для прибыли
                        if ($index === 12) { // Чистая прибыль
                            $numericValue = (float) str_replace(',', '.', str_replace(' ', '', $cell));
                            $class .= $numericValue >= 0 ? ' profit-positive' : ' profit-negative';
                        }
                    }
                    $html .= '<td class="' . $class . '">' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</td>';
                }
                $html .= '</tr>';
            }
        }
        
        $html .= '</table></body></html>';
        
        return response()->streamDownload(function() use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportShipmentsDetailed()
    {
        // Проверяем права доступа
        if (!auth()->user()->can('shipments.export')) {
            session()->flash('error', 'У вас нет прав для экспорта отчетов по отгрузкам.');
            return;
        }
        
        $shipments = $this->shipments;
        
        $csvData = [];
        $csvData[] = [
            'ID отгрузки',
            'Дата отгрузки',
            'Предприятие',
            'Номер авто',
            'Водитель',
            'Статус отгрузки',
            'Металл',
            'Заявленный вес (кг)',
            'Фактический вес (кг)',
            'Средняя цена закупки',
            'Засор (%)',
            'Расчетные затраты на позицию',
            'Фактическая цена продажи',
            'Засор фактический (%)',
            'Чистый вес (кг)',
            'Валовая выручка с позиции',
            'Прибыль с позиции',
            'Рентабельность позиции (%)',
            'Тип списания',
            'Остаток после списания',
            'Ожидаемый остаток до списания',
            'Фактический остаток до списания',
            'Расхождение склада (кг)',
            'Комментарий к отгрузке'
        ];
        
        foreach ($shipments as $shipment) {
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Правильная формула затрат: Чистый вес × Средняя цена
                $cleanWeightCost = $item->weight * (1 - ($clogging / 100));
                $itemCost = $cleanWeightCost * $purchase;
                
                // Вычисляем валовую выручку с учетом засора
                $actualWeight = $item->actual_weight ?? 0;
                $actualPrice = $item->actual_price ?? 0;
                $actualClogging = $item->actual_clogging ?? 0;
                $cleanWeight = $actualWeight * (1 - ($actualClogging / 100));
                $itemIncome = $cleanWeight * $actualPrice;
                
                $itemProfit = $itemIncome - $itemCost;
                $itemProfitability = $itemCost > 0 ? ($itemProfit / $itemCost) * 100 : 0;
                
                $csvData[] = [
                    $shipment->id,
                    $shipment->created_at->format('d.m.Y H:i'),
                    $shipment->company ?: 'Не указано',
                    $shipment->car_number ?: 'Не указано',
                    $shipment->driver_name ?: 'Не указано',
                    $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено',
                    $product?->name ?: 'Неизвестно',
                    number_format($item->weight, 2),
                    number_format($item->actual_weight ?? 0, 2),
                    number_format($purchase, 2),
                    number_format($clogging, 2),
                    number_format($itemCost, 2),
                    number_format($item->actual_price ?? 0, 2),
                    number_format($actualClogging, 2),
                    number_format($cleanWeight, 2),
                    number_format($itemIncome, 2),
                    number_format($itemProfit, 2),
                    number_format($itemProfitability, 2),
                    $item->writeoff_type === 'full' ? 'В ноль' : 'С остатком',
                    number_format($item->stock_after ?? 0, 2),
                    number_format($item->expected_stock_before ?? 0, 2),
                    number_format($item->actual_stock_before ?? 0, 2),
                    number_format($item->stock_discrepancy ?? 0, 2),
                    $shipment->comment ?: '',
                ];
            }
        }
        
        $filename = 'shipments_items_detailed_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Добавляем BOM для корректного отображения UTF-8 в Excel
        fwrite($handle, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row, ';');
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response()->streamDownload(function() use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.shipments.shipment-manager');
    }
}
