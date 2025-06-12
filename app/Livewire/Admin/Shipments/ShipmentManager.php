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
        $this->product_id = null;
        $this->weight = null;
        $this->writeoff_type = 'partial';
        $this->stock_after = null;
    }

    public function removeShipmentItem($index)
    {
        unset($this->shipmentItems[$index]);
        $this->shipmentItems = array_values($this->shipmentItems);
    }

    public function openModal($shipmentId = null)
    {
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
            $this->shipping_cost = $shipment->shipping_cost ?? 0;
            $this->shipmentItems = [];
            foreach ($shipment->items as $item) {
                $this->shipmentItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'weight' => $item->weight,
                    'writeoff_type' => $item->writeoff_type,
                    'stock_after' => $item->stock_after,
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

        // Проверяем остатки на складе
        foreach ($this->shipmentItems as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->stock < $item['weight']) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'shipmentItems' => "Недостаточно товара '{$product->name}' на складе. Доступно: {$product->stock} кг, запрошено: {$item['weight']} кг",
                ]);
            }
        }

        if ($this->editMode && $this->editShipmentId) {
            $shipment = Shipment::findOrFail($this->editShipmentId);
            $shipment->update([
                'car_number' => $this->car_number,
                'driver_name' => $this->driver_name,
                'company' => $this->company,
                'comment' => $this->comment,
                'shipping_cost' => $this->shipping_cost ?? 0,
            ]);
            $shipment->items()->delete();
            foreach ($this->shipmentItems as $item) {
                $shipment->items()->create($item);
            }
        } else {
            $shipment = Shipment::create([
                'car_number' => $this->car_number,
                'driver_name' => $this->driver_name,
                'company' => $this->company,
                'comment' => $this->comment,
                'shipping_cost' => $this->shipping_cost ?? 0,
                'stage' => 'draft',
            ]);
            foreach ($this->shipmentItems as $item) {
                $shipment->items()->create($item);
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
        $this->shipmentItems = [];
        $this->product_id = null;
        $this->weight = null;
        $this->writeoff_type = 'partial';
        $this->stock_after = null;
        $this->shipping_cost = 0;
    }

    public function openConfirmModal($shipmentId)
    {
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

    public function getShipmentProfit($shipment)
    {
        $profit = 0;
        foreach ($shipment->items as $item) {
            $purchase = $item->product->average_purchase_price ?? 0;
            $clogging = $item->product->clogging ?? 0;
            
            // Защита от деления на ноль при 100% засоре
            if ($clogging >= 100) {
                $cost = $item->weight * $purchase * 10; // Условно высокая стоимость при 100% засоре
            } else {
                $cost = $item->weight * ($purchase / (1 - ($clogging / 100)));
            }
            
            $income = ($item->actual_weight ?? 0) * ($item->actual_price ?? 0);
            
            $profit += $income - $cost;
        }
        return $profit;
    }

    public function getItemProfit($item)
    {
        $purchase = $item->product->average_purchase_price ?? 0;
        $clogging = $item->product->clogging ?? 0;
        
        if ($clogging >= 100) {
            $cost = $item->weight * $purchase * 10;
        } else {
            $cost = $item->weight * ($purchase / (1 - ($clogging / 100)));
        }
        
        $income = ($item->actual_weight ?? 0) * ($item->actual_price ?? 0);
        
        return $income - $cost;
    }

    public function exportShipments()
    {
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
            'Фактический доход',
            'Затраты на отгрузку',
            'Чистая прибыль',
            'Рентабельность (%)',
            'Комментарий'
        ];
        
        foreach ($shipments as $shipment) {
            $metals = [];
            $declaredWeight = 0;
            $actualWeight = 0;
            $calculatedCosts = 0;
            $actualIncome = 0;
            
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Расчет затрат с учетом засора
                if ($clogging >= 100) {
                    $itemCost = $item->weight * $purchase * 10;
                } else {
                    $itemCost = $item->weight * ($purchase / (1 - ($clogging / 100)));
                }
                
                $itemIncome = ($item->actual_weight ?? 0) * ($item->actual_price ?? 0);
                
                $metals[] = $product?->name . ' (' . $item->weight . ' кг)';
                $declaredWeight += $item->weight;
                $actualWeight += $item->actual_weight ?? 0;
                $calculatedCosts += $itemCost;
                $actualIncome += $itemIncome;
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
                number_format($actualWeight, 2),
                number_format($calculatedCosts, 2),
                number_format($actualIncome, 2),
                number_format($shippingCost, 2),
                number_format($netProfit, 2),
                number_format($profitability, 2),
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
            'Фактический доход',
            'Затраты на отгрузку',
            'Чистая прибыль',
            'Рентабельность (%)',
            'Комментарий'
        ];
        
        foreach ($shipments as $shipment) {
            $metals = [];
            $declaredWeight = 0;
            $actualWeight = 0;
            $calculatedCosts = 0;
            $actualIncome = 0;
            
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Расчет затрат с учетом засора
                if ($clogging >= 100) {
                    $itemCost = $item->weight * $purchase * 10;
                } else {
                    $itemCost = $item->weight * ($purchase / (1 - ($clogging / 100)));
                }
                
                $itemIncome = ($item->actual_weight ?? 0) * ($item->actual_price ?? 0);
                
                $metals[] = $product?->name . ' (' . $item->weight . ' кг)';
                $declaredWeight += $item->weight;
                $actualWeight += $item->actual_weight ?? 0;
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
                number_format($actualWeight, 2),
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
            'Фактический доход с позиции',
            'Прибыль с позиции',
            'Рентабельность позиции (%)',
            'Тип списания',
            'Остаток после списания',
            'Комментарий к отгрузке'
        ];
        
        foreach ($shipments as $shipment) {
            foreach ($shipment->items as $item) {
                $product = $this->products->find($item->product_id);
                $purchase = $product?->average_purchase_price ?? 0;
                $clogging = $product?->clogging ?? 0;
                
                // Расчет затрат с учетом засора
                if ($clogging >= 100) {
                    $itemCost = $item->weight * $purchase * 10;
                } else {
                    $itemCost = $item->weight * ($purchase / (1 - ($clogging / 100)));
                }
                
                $itemIncome = ($item->actual_weight ?? 0) * ($item->actual_price ?? 0);
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
                    number_format($itemIncome, 2),
                    number_format($itemProfit, 2),
                    number_format($itemProfitability, 2),
                    $item->writeoff_type === 'full' ? 'В ноль' : 'С остатком',
                    number_format($item->stock_after ?? 0, 2),
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
