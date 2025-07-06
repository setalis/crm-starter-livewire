<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Element;
use App\Models\Operation;
use App\Models\OperationItem;
use App\Models\CashTransaction;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\OperationItemElement;
use App\Models\Product;
use App\Exports\ReportsExport;
use App\Helpers\Settings;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportManager extends Component
{
    public $startDate;
    public $endDate;
    public $reportData = [];
    public $selectedProducts = [];
    public $showReport = false;
    
    public function getCurrencySymbolProperty()
    {
        return Settings::currencySymbol();
    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $this->reportData = $this->buildReportData($startDate, $endDate);
        $this->showReport = true;
    }

    public function exportToExcel()
    {
        if (!$this->showReport || empty($this->reportData)) {
            $this->generateReport();
        }

        $fileName = 'otchet_po_dvizheniyu_' . 
                   Carbon::parse($this->startDate)->format('d_m_Y') . '_' . 
                   Carbon::parse($this->endDate)->format('d_m_Y') . '.xlsx';

        return Excel::download(
            new ReportsExport($this->startDate, $this->endDate, $this->reportData), 
            $fileName
        );
    }

    private function buildReportData($startDate, $endDate)
    {
        // Получаем все продукты
        $products = Product::all();
        
        // Сначала собираем все данные и выясняем, какие дни имеют операции
        $allData = [];
        $activeDates = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            
            // Данные по продуктам за день
            $dayProductData = [];
            $dayHasData = false;
            
            foreach ($products as $product) {
                $productData = $this->getProductDataForDate($product, $current);
                $dayProductData[$product->name] = $productData;
                if ($productData['has_data']) {
                    $dayHasData = true;
                }
            }
            
            // Данные по кассе за день - только независимые транзакции (не связанные с операциями)
            $cashData = $this->getCashMovements($current);
            if ($cashData['income'] > 0 || $cashData['expense'] > 0) {
                $dayHasData = true;
            }
            
            // Если есть данные за день - добавляем в активные дни
            if ($dayHasData) {
                $activeDates[] = [
                    'date' => $dateStr,
                    'formatted' => $current->format('d.m.Y')
                ];
                
                $allData[$dateStr] = [
                    'products' => $dayProductData,
                    'cash' => $cashData
                ];
            }
            
            $current->addDay();
        }
        
        // Перестраиваем структуру для удобного отображения и считаем итоги по продуктам
        $productData = [];
        $productTotals = [];
        
        foreach ($products as $product) {
            $productData[$product->name] = [];
            
            // Инициализируем итоги для продукта
            $totalPurchaseWeight = 0;
            $totalPurchaseCleanWeight = 0; // Чистый вес для правильного расчета средней цены
            $totalPurchaseAmount = 0;
            $totalSaleWeight = 0;
            $totalSaleCleanWeight = 0; // Чистый вес для правильного расчета средней цены
            $totalSaleAmount = 0;
            $totalShipmentWeight = 0;
            $totalClogging = 0;
            $totalCloggingWeight = 0;
            
            foreach ($activeDates as $dateInfo) {
                $dayData = $allData[$dateInfo['date']]['products'][$product->name];
                $productData[$product->name][$dateInfo['date']] = $dayData;
                
                // Суммируем для итогов
                $totalPurchaseWeight += $dayData['purchase_weight'];
                $totalPurchaseAmount += $dayData['purchase_amount'];
                $totalSaleWeight += $dayData['sale_weight'];
                $totalSaleAmount += $dayData['sale_amount'];
                $totalShipmentWeight += $dayData['shipment_weight'];
                
                // Для средневзвешенного засора
                if ($dayData['avg_contamination'] > 0) {
                    $totalClogging += $dayData['avg_contamination'] * $dayData['purchase_weight'];
                    $totalCloggingWeight += $dayData['purchase_weight'];
                }
                
                // ИСПРАВЛЕНО: рассчитываем чистый вес для правильной средней цены
                if ($dayData['avg_contamination'] > 0) {
                    $purchaseCleanWeight = $dayData['purchase_weight'] * (1 - $dayData['avg_contamination'] / 100);
                    $saleCleanWeight = $dayData['sale_weight'] * (1 - $dayData['avg_contamination'] / 100);
                } else {
                    $purchaseCleanWeight = $dayData['purchase_weight'];
                    $saleCleanWeight = $dayData['sale_weight'];
                }
                $totalPurchaseCleanWeight += $purchaseCleanWeight;
                $totalSaleCleanWeight += $saleCleanWeight;
            }
            
            // ИСПРАВЛЕНО: рассчитываем итоги за период для продукта на основе чистого веса
            $avgPurchasePrice = $totalPurchaseCleanWeight > 0 ? $totalPurchaseAmount / $totalPurchaseCleanWeight : 0;
            $avgSalePrice = $totalSaleCleanWeight > 0 ? $totalSaleAmount / $totalSaleCleanWeight : 0;
            $avgContamination = $totalCloggingWeight > 0 ? $totalClogging / $totalCloggingWeight : 0;
            $totalWeight = $totalPurchaseWeight + $totalSaleWeight;
            
            $productTotals[$product->name] = [
                'total_weight' => $totalWeight,
                'purchase_weight' => $totalPurchaseWeight,
                'purchase_amount' => $totalPurchaseAmount,
                'purchase_avg_price' => $avgPurchasePrice,
                'sale_weight' => $totalSaleWeight,
                'sale_amount' => $totalSaleAmount,
                'sale_avg_price' => $avgSalePrice,
                'shipment_weight' => $totalShipmentWeight,
                'avg_contamination' => $avgContamination,
                'has_data' => $totalWeight > 0 || $totalShipmentWeight > 0
            ];
        }
        
        $cashData = [];
        $dailyTotals = [];
        $totalCashIncome = 0;
        $totalCashExpense = 0;
        
        foreach ($activeDates as $dateInfo) {
            $cashData[$dateInfo['date']] = $allData[$dateInfo['date']]['cash'];
            
            // Суммируем движения по кассе за период - только независимые транзакции
            $totalCashIncome += $allData[$dateInfo['date']]['cash']['income'];
            $totalCashExpense += $allData[$dateInfo['date']]['cash']['expense'];
            
            // Считаем итоги по дню
            $dayTotal = $this->calculateDayTotal($allData[$dateInfo['date']]);
            $dailyTotals[$dateInfo['date']] = $dayTotal;
        }

        // Считаем общий итог за период
        $periodTotal = $this->calculatePeriodTotal($dailyTotals);

        return [
            'dates' => $activeDates,
            'products' => $productData,
            'productTotals' => $productTotals,
            'cash' => $cashData,
            'cashTotals' => [
                'income' => $totalCashIncome,
                'expense' => $totalCashExpense
            ],
            'dailyTotals' => $dailyTotals,
            'periodTotal' => $periodTotal
        ];
    }

    private function getCompactElementMovements($date)
    {
        $elements = Element::all();
        $elementData = [];

        foreach ($elements as $element) {
            // Получаем операции покупки за день
            $purchases = OperationItemElement::whereHas('item.operation', function ($query) use ($date) {
                $query->where('type', 'purchase')
                      ->whereDate('created_at', $date);
            })
            ->where('element_id', $element->id)
            ->with(['item.operation', 'item.product'])
            ->get();

            // Получаем операции продажи за день (включая отгрузки)
            $sales = OperationItemElement::whereHas('item.operation', function ($query) use ($date) {
                $query->where('type', 'sale')
                      ->whereDate('created_at', $date);
            })
            ->where('element_id', $element->id)
            ->with(['item.operation', 'item.product'])
            ->get();

            // Получаем отгрузки за день для этого элемента
            $shipments = Shipment::whereDate('created_at', $date)
                ->with(['items.product.elements'])
                ->get();

            $purchaseWeight = 0;
            $purchaseAmount = 0;
            $saleWeight = 0;
            $saleAmount = 0;
            $shipmentWeight = 0;

            foreach ($purchases as $purchase) {
                $elementWeight = $purchase->item->weight * ($purchase->percentage / 100);
                $purchaseWeight += $elementWeight;
                
                // Для расчета стоимости элемента нужно учитывать тип продукта
                if ($purchase->item->product->type === 'composite') {
                    // Для составного продукта берем цену элемента за 1% и умножаем на процент и вес
                    $elementPrice = ($element->price ?? 0) * ($purchase->percentage / 100) * $purchase->item->weight;
                } else {
                    // Для простого продукта распределяем общую стоимость пропорционально
                    $totalItemCost = $purchase->item->price * $purchase->item->weight;
                    $elementPrice = $totalItemCost * ($purchase->percentage / 100);
                }
                
                $purchaseAmount += $elementPrice;
            }

            foreach ($sales as $sale) {
                $elementWeight = $sale->item->weight * ($sale->percentage / 100);
                $saleWeight += $elementWeight;
                
                // Аналогично для продаж
                if ($sale->item->product->type === 'composite') {
                    $elementPrice = ($element->price ?? 0) * ($sale->percentage / 100) * $sale->item->weight;
                } else {
                    $totalItemCost = $sale->item->price * $sale->item->weight;
                    $elementPrice = $totalItemCost * ($sale->percentage / 100);
                }
                
                $saleAmount += $elementPrice;
            }

            // Подсчет отгрузок
            foreach ($shipments as $shipment) {
                foreach ($shipment->items as $item) {
                    foreach ($item->product->elements as $productElement) {
                        if ($productElement->id === $element->id) {
                            $weight = $item->actual_weight ?? $item->weight;
                            $elementWeight = $weight * ($productElement->pivot->percentage / 100);
                            $shipmentWeight += $elementWeight;
                        }
                    }
                }
            }

            if ($purchaseWeight > 0 || $saleWeight > 0 || $shipmentWeight > 0) {
                $elementData[$element->name] = [
                    'purchase_weight' => $purchaseWeight,
                    'purchase_amount' => $purchaseAmount,
                    'purchase_avg_price' => $purchaseWeight > 0 ? $purchaseAmount / $purchaseWeight : 0,
                    'sale_weight' => $saleWeight,
                    'sale_amount' => $saleAmount,
                    'sale_avg_price' => $saleWeight > 0 ? $saleAmount / $saleWeight : 0,
                    'shipment_weight' => $shipmentWeight,
                ];
            }
        }

        return $elementData;
    }

    private function getProductDataForDate($product, $date)
    {
        // Получаем операции покупки за день
        $purchaseOperations = Operation::where('type', 'purchase')
            ->whereDate('created_at', $date)
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with(['items' => function($query) use ($product) {
                $query->where('product_id', $product->id);
            }])
            ->get();

        // Получаем операции продажи за день
        $saleOperations = Operation::where('type', 'sale')
            ->whereDate('created_at', $date)
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with(['items' => function($query) use ($product) {
                $query->where('product_id', $product->id);
            }])
            ->get();

        // Получаем отгрузки за день для этого продукта с подробной информацией
        $shipments = ShipmentItem::whereHas('shipment', function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        })
        ->where('product_id', $product->id)
        ->with(['shipment', 'product'])
        ->get();

        $purchaseWeight = 0;
        $purchaseCleanWeight = 0; // Чистый вес для правильного расчета цены
        $purchaseAmount = 0;
        $saleWeight = 0;
        $saleCleanWeight = 0; // Чистый вес для правильного расчета цены
        $saleAmount = 0;
        $shipmentWeight = 0;
        $shipmentAmount = 0;
        $shipmentsDetails = [];
        $totalClogging = 0;
        $totalCloggingWeight = 0;

        // Подсчет покупок - используем данные из операций
        foreach ($purchaseOperations as $operation) {
            foreach ($operation->items as $item) {
                $purchaseWeight += $item->weight;
                $purchaseAmount += $item->price;
                
                // ИСПРАВЛЕНО: учитываем чистый вес для правильного расчета средней цены
                $cleanWeight = $item->weight * (1 - $item->clogging / 100);
                $purchaseCleanWeight += $cleanWeight;
                
                // Засор
                if ($item->clogging > 0) {
                    $totalClogging += $item->clogging * $item->weight;
                    $totalCloggingWeight += $item->weight;
                }
            }
        }

        // Подсчет продаж
        foreach ($saleOperations as $operation) {
            foreach ($operation->items as $item) {
                $saleWeight += $item->weight;
                $saleAmount += $item->price;
                
                // ИСПРАВЛЕНО: учитываем чистый вес для правильного расчета средней цены
                $cleanWeight = $item->weight * (1 - $item->clogging / 100);
                $saleCleanWeight += $cleanWeight;
            }
        }

        // Подсчет отгрузок с детальной информацией
        foreach ($shipments as $shipmentItem) {
            $weight = $shipmentItem->actual_weight ?? $shipmentItem->weight;
            $pricePerKg = $shipmentItem->actual_price ?? 0;
            $clogging = $shipmentItem->actual_clogging ?? 0;
            $shipment = $shipmentItem->shipment;
            
            // ИСПРАВЛЕНО: рассчитываем чистый вес и общую сумму
            $cleanWeight = $weight * (1 - $clogging / 100);
            $totalAmount = $cleanWeight * $pricePerKg; // Сумма = чистый вес × цена за кг
            
            $shipmentWeight += $weight;
            $shipmentAmount += $totalAmount; // Теперь это правильная сумма
            
            // Собираем детальную информацию об отгрузке
            $shipmentDetail = [
                'weight' => $weight,
                'clean_weight' => $cleanWeight,
                'price_per_kg' => $pricePerKg,
                'total_amount' => $totalAmount,
                'company' => $shipment->company ?? 'Не указано',
                'car_number' => $shipment->car_number ?? '',
                'driver_name' => $shipment->driver_name ?? '',
                'shipping_cost' => $shipment->shipping_cost ?? 0,
                'actual_clogging' => $clogging,
                'created_at' => $shipment->created_at->format('H:i')
            ];
            
            $shipmentsDetails[] = $shipmentDetail;
        }

        // ИСПРАВЛЕНО: рассчитываем средние цены на основе чистого веса
        $avgPurchasePrice = $purchaseCleanWeight > 0 ? $purchaseAmount / $purchaseCleanWeight : 0;
        $avgSalePrice = $saleCleanWeight > 0 ? $saleAmount / $saleCleanWeight : 0;
        
        // Общий вес за день = покупки + продажи (отгрузки не считаем, так как это уже купленный металл)
        $totalWeight = $purchaseWeight + $saleWeight;
        
        // Средний засор
        $avgClogging = $totalCloggingWeight > 0 ? $totalClogging / $totalCloggingWeight : 0;

        return [
            'purchase_weight' => $purchaseWeight,
            'purchase_amount' => $purchaseAmount,
            'purchase_avg_price' => $avgPurchasePrice,
            'sale_weight' => $saleWeight,
            'sale_amount' => $saleAmount,
            'sale_avg_price' => $avgSalePrice,
            'shipment_weight' => $shipmentWeight,
            'shipment_amount' => $shipmentAmount,
            'shipments_details' => $shipmentsDetails,
            'total_weight' => $totalWeight,
            'avg_contamination' => $avgClogging,
            'has_data' => $purchaseWeight > 0 || $saleWeight > 0 || $shipmentWeight > 0
        ];
    }

    private function getElementDataForDate($element, $date)
    {
        // Получаем операции покупки за день
        $purchases = OperationItemElement::whereHas('item.operation', function ($query) use ($date) {
            $query->where('type', 'purchase')
                  ->whereDate('created_at', $date);
        })
        ->where('element_id', $element->id)
        ->with(['item.operation', 'item.product'])
        ->get();

        // Получаем операции продажи за день
        $sales = OperationItemElement::whereHas('item.operation', function ($query) use ($date) {
            $query->where('type', 'sale')
                  ->whereDate('created_at', $date);
        })
        ->where('element_id', $element->id)
        ->with(['item.operation', 'item.product'])
        ->get();

        // Получаем отгрузки за день для этого элемента
        $shipments = Shipment::whereDate('created_at', $date)
            ->with(['items.product.elements'])
            ->get();

        $purchaseWeight = 0;
        $purchaseAmount = 0;
        $saleWeight = 0;
        $saleAmount = 0;
        $shipmentWeight = 0;

        // Подсчет покупок
        foreach ($purchases as $purchase) {
            $elementWeight = $purchase->item->weight * ($purchase->percentage / 100);
            $purchaseWeight += $elementWeight;
            
            // Для расчета стоимости элемента нужно учитывать тип продукта
            if ($purchase->item->product->type === 'composite') {
                // Для составного продукта берем цену элемента за 1% и умножаем на процент и вес
                $elementPrice = ($element->price ?? 0) * ($purchase->percentage / 100) * $purchase->item->weight;
            } else {
                // Для простого продукта распределяем общую стоимость пропорционально
                $totalItemCost = $purchase->item->price * $purchase->item->weight;
                $elementPrice = $totalItemCost * ($purchase->percentage / 100);
            }
            
            $purchaseAmount += $elementPrice;
        }

        // Подсчет продаж
        foreach ($sales as $sale) {
            $elementWeight = $sale->item->weight * ($sale->percentage / 100);
            $saleWeight += $elementWeight;
            
            // Аналогично для продаж
            if ($sale->item->product->type === 'composite') {
                $elementPrice = ($element->price ?? 0) * ($sale->percentage / 100) * $sale->item->weight;
            } else {
                $totalItemCost = $sale->item->price * $sale->item->weight;
                $elementPrice = $totalItemCost * ($sale->percentage / 100);
            }
            
            $saleAmount += $elementPrice;
        }

        // Подсчет отгрузок
        foreach ($shipments as $shipment) {
            foreach ($shipment->items as $item) {
                foreach ($item->product->elements as $productElement) {
                    if ($productElement->id === $element->id) {
                        $weight = $item->actual_weight ?? $item->weight;
                        $elementWeight = $weight * ($productElement->pivot->percentage / 100);
                        $shipmentWeight += $elementWeight;
                    }
                }
            }
        }

        return [
            'purchase_weight' => $purchaseWeight,
            'purchase_amount' => $purchaseAmount,
            'sale_weight' => $saleWeight,
            'sale_amount' => $saleAmount,
            'shipment_weight' => $shipmentWeight,
            'has_data' => $purchaseWeight > 0 || $saleWeight > 0 || $shipmentWeight > 0
        ];
    }

    private function calculateDayTotal($dayData)
    {
        $totalPurchases = 0;
        $totalSales = 0;
        $cashIncome = $dayData['cash']['income'];
        $cashExpense = $dayData['cash']['expense'];
        
        // Суммируем все покупки и продажи по продуктам
        foreach ($dayData['products'] as $productData) {
            $totalPurchases += $productData['purchase_amount'];
            $totalSales += $productData['sale_amount'];
        }
        
        // Общий результат дня = продажи + пополнения кассы - покупки - снятия с кассы
        $dayResult = $totalSales + $cashIncome - $totalPurchases - $cashExpense;
        
        return [
            'expenses' => $totalPurchases,  // Расход = покупка металла
            'income' => $totalSales,        // Приход = продажа металла
            'cash_income' => $cashIncome,   // Пополнение кассы (независимые транзакции)
            'cash_expense' => $cashExpense, // Снятие с кассы (независимые транзакции)
            'day_result' => $dayResult
        ];
    }

    private function calculatePeriodTotal($dailyTotals)
    {
        $totalPurchases = 0;
        $totalSales = 0;
        $totalCashIncome = 0;
        $totalCashExpense = 0;
        
        foreach ($dailyTotals as $dayTotal) {
            $totalPurchases += $dayTotal['expenses'];  // Изменено с purchases на expenses
            $totalSales += $dayTotal['income'];        // Изменено с sales на income
            $totalCashIncome += $dayTotal['cash_income'];
            $totalCashExpense += $dayTotal['cash_expense'];
        }
        
        $periodResult = $totalSales + $totalCashIncome - $totalPurchases - $totalCashExpense;
        
        return [
            'expenses' => $totalPurchases,   // Общий расход = покупка металла
            'income' => $totalSales,         // Общий приход = продажа металла
            'cash_income' => $totalCashIncome,   // Пополнения кассы
            'cash_expense' => $totalCashExpense, // Снятия с кассы
            'period_result' => $periodResult
        ];
    }

    private function getCashMovements($date)
    {
        // ИСПРАВЛЕНО: берем только независимые транзакции кассы (не связанные с операциями)
        $transactions = CashTransaction::whereDate('created_at', $date)
            ->whereNull('operation_id') // Только независимые транзакции, не связанные с операциями
            ->with(['cashRegister'])
            ->orderBy('created_at')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        return [
            'transactions' => $transactions,
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
        ];
    }

    private function getShipments($date)
    {
        return Shipment::whereDate('created_at', $date)
            ->with(['items.product.elements'])
            ->get()
            ->map(function ($shipment) {
                $elements = collect();
                
                foreach ($shipment->items as $item) {
                    foreach ($item->product->elements as $element) {
                        $weight = $item->actual_weight ?? $item->weight;
                        $elementWeight = $weight * ($element->pivot->percentage / 100);
                        
                        $existing = $elements->firstWhere('id', $element->id);
                        if ($existing) {
                            $existing['weight'] += $elementWeight;
                        } else {
                            $elements->push([
                                'id' => $element->id,
                                'name' => $element->name,
                                'weight' => $elementWeight,
                            ]);
                        }
                    }
                }

                return [
                    'shipment' => $shipment,
                    'elements' => $elements,
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.reports.report-manager')
            ->layout('components.layouts.app');
    }
} 