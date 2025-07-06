<?php

namespace App\Exports;

use App\Models\Element;
use App\Models\CashTransaction;
use App\Models\Shipment;
use App\Models\OperationItemElement;
use App\Helpers\Settings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ReportsExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    private $startDate;
    private $endDate;
    private $reportData;

    public function __construct($startDate, $endDate, $reportData)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->reportData = $reportData;
    }

    private function getCurrencySymbol()
    {
        return Settings::currencySymbol();
    }

    public function array(): array
    {
        $data = [];
        $currency = $this->getCurrencySymbol();
        
        // Основной заголовок
        $data[] = ['Отчет по движению металлов с ' . Carbon::parse($this->startDate)->format('d.m.Y') . ' по ' . Carbon::parse($this->endDate)->format('d.m.Y')];
        $data[] = []; // Пустая строка
        
        // Заголовки колонок
        $headers = ['📋 Позиция'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $headers[] = '📅 ' . $dateInfo['formatted'];
        }
        $headers[] = '🏁 ИТОГО ЗА ПЕРИОД';
        $data[] = $headers;
        
        // СЕКЦИЯ КАССЫ
        $data[] = []; // Пустая строка для разделения
        $data[] = ['💰 ДВИЖЕНИЕ КАССЫ'];
        
        // Заголовки для кассы
        $cashHeaders = ['Операция'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $cashHeaders[] = $dateInfo['formatted'];
        }
        $cashHeaders[] = 'Итого';
        $data[] = $cashHeaders;
        
        // Строка пополнений кассы
        $incomeRow = ['💵 Пополнения'];
        $totalIncome = 0;
        foreach ($this->reportData['dates'] as $dateInfo) {
            $cashData = $this->reportData['cash'][$dateInfo['date']];
            $incomeRow[] = $cashData['income'] > 0 ? number_format($cashData['income'], 0, ',', ' ') . ' ' . $currency : '-';
            $totalIncome += $cashData['income'];
        }
        $incomeRow[] = $totalIncome > 0 ? number_format($totalIncome, 0, ',', ' ') . ' ' . $currency : '-';
        $data[] = $incomeRow;
        
        // Строка снятий кассы
        $expenseRow = ['💸 Снятия'];
        $totalExpense = 0;
        foreach ($this->reportData['dates'] as $dateInfo) {
            $cashData = $this->reportData['cash'][$dateInfo['date']];
            $expenseRow[] = $cashData['expense'] > 0 ? number_format($cashData['expense'], 0, ',', ' ') . ' ' . $currency : '-';
            $totalExpense += $cashData['expense'];
        }
        $expenseRow[] = $totalExpense > 0 ? number_format($totalExpense, 0, ',', ' ') . ' ' . $currency : '-';
        $data[] = $expenseRow;
        
        // Баланс кассы
        $balanceRow = ['📊 Баланс кассы'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $cashData = $this->reportData['cash'][$dateInfo['date']];
            $dayBalance = $cashData['income'] - $cashData['expense'];
            $balanceRow[] = $dayBalance != 0 ? 
                ($dayBalance > 0 ? '+' : '') . number_format($dayBalance, 0, ',', ' ') . ' ' . $currency : '-';
        }
        $finalBalance = $totalIncome - $totalExpense;
        $balanceRow[] = ($finalBalance > 0 ? '+' : '') . number_format($finalBalance, 0, ',', ' ') . ' ' . $currency;
        $data[] = $balanceRow;
        
        // СЕКЦИЯ МЕТАЛЛОВ
        $data[] = []; // Пустая строка
        $data[] = ['🔩 ДВИЖЕНИЕ МЕТАЛЛОВ'];
        
        // Заголовки для металлов
        $metalHeaders = ['Металл / Показатель'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $metalHeaders[] = $dateInfo['formatted'];
        }
        $metalHeaders[] = 'Итого за период';
        $data[] = $metalHeaders;
        
        // Данные по каждому металлу
        foreach ($this->reportData['products'] as $productName => $productDates) {
            $productTotal = $this->reportData['productTotals'][$productName];
            if (!$productTotal['has_data']) continue;
            
            // Заголовок металла
            $data[] = ['🔸 ' . strtoupper($productName)];
            
            // Общий вес
            $weightRow = ['  📏 Общий вес (кг)'];
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                $weightRow[] = $productData['total_weight'] > 0 ? number_format($productData['total_weight'], 2) : '-';
            }
            $weightRow[] = $productTotal['total_weight'] > 0 ? number_format($productTotal['total_weight'], 2) : '-';
            $data[] = $weightRow;
            
            // Потрачено (покупка)
            $purchaseRow = ['  💸 Потрачено (' . $currency . ')'];
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                $purchaseRow[] = $productData['purchase_amount'] > 0 ? number_format($productData['purchase_amount'], 0, ',', ' ') : '-';
            }
            $purchaseRow[] = $productTotal['purchase_amount'] > 0 ? number_format($productTotal['purchase_amount'], 0, ',', ' ') : '-';
            $data[] = $purchaseRow;
            
            // Получено (продажа)
            $saleRow = ['  💰 Получено (' . $currency . ')'];
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                $saleRow[] = $productData['sale_amount'] > 0 ? number_format($productData['sale_amount'], 0, ',', ' ') : '-';
            }
            $saleRow[] = $productTotal['sale_amount'] > 0 ? number_format($productTotal['sale_amount'], 0, ',', ' ') : '-';
            $data[] = $saleRow;
            
            // Результат по металлу
            $resultRow = ['  📊 Результат (' . $currency . ')'];
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                $result = $productData['sale_amount'] - $productData['purchase_amount'];
                $resultRow[] = $result != 0 ? ($result > 0 ? '+' : '') . number_format($result, 0, ',', ' ') : '-';
            }
            $totalResult = $productTotal['sale_amount'] - $productTotal['purchase_amount'];
            $resultRow[] = $totalResult != 0 ? ($totalResult > 0 ? '+' : '') . number_format($totalResult, 0, ',', ' ') : '-';
            $data[] = $resultRow;
            
            // Средняя цена покупки
            if ($productTotal['purchase_avg_price'] > 0) {
                $avgPurchaseRow = ['  💵 Ср. цена покупки (' . $currency . '/кг)'];
                foreach ($this->reportData['dates'] as $dateInfo) {
                    $productData = $productDates[$dateInfo['date']];
                    $avgPurchaseRow[] = $productData['purchase_avg_price'] > 0 ? number_format($productData['purchase_avg_price'], 0) : '-';
                }
                $avgPurchaseRow[] = number_format($productTotal['purchase_avg_price'], 0);
                $data[] = $avgPurchaseRow;
            }
            
            // Средняя цена продажи
            if ($productTotal['sale_avg_price'] > 0) {
                $avgSaleRow = ['  💶 Ср. цена продажи (' . $currency . '/кг)'];
                foreach ($this->reportData['dates'] as $dateInfo) {
                    $productData = $productDates[$dateInfo['date']];
                    $avgSaleRow[] = $productData['sale_avg_price'] > 0 ? number_format($productData['sale_avg_price'], 0) : '-';
                }
                $avgSaleRow[] = number_format($productTotal['sale_avg_price'], 0);
                $data[] = $avgSaleRow;
            }
            
            // Средний засор
            if ($productTotal['avg_contamination'] > 0) {
                $contaminationRow = ['  🧹 Ср. засор (%)'];
                foreach ($this->reportData['dates'] as $dateInfo) {
                    $productData = $productDates[$dateInfo['date']];
                    $contaminationRow[] = $productData['avg_contamination'] > 0 ? number_format($productData['avg_contamination'], 1) : '-';
                }
                $contaminationRow[] = number_format($productTotal['avg_contamination'], 1);
                $data[] = $contaminationRow;
            }
            
            // Детальная информация об отгрузках
            $hasShipmentDetails = false;
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                if (!empty($productData['shipments_details'])) {
                    $hasShipmentDetails = true;
                    break;
                }
            }
            
            if ($hasShipmentDetails) {
                $data[] = ['  🚚 ДЕТАЛИ ОТГРУЗОК:']; // Заголовок секции отгрузок
                
                foreach ($this->reportData['dates'] as $dateInfo) {
                    $productData = $productDates[$dateInfo['date']];
                    
                    if (!empty($productData['shipments_details'])) {
                        foreach ($productData['shipments_details'] as $index => $shipmentDetail) {
                            $shipmentRow = [
                                '    📦 Отгрузка ' . ($index + 1) . ' (' . $dateInfo['formatted'] . ')'
                            ];
                            
                            // Добавляем детали отгрузки в одну ячейку
                            $details = [];
                            $details[] = '🏢 ' . $shipmentDetail['company'];
                            if ($shipmentDetail['car_number']) {
                                $details[] = '🚗 ' . $shipmentDetail['car_number'];
                            }
                            if ($shipmentDetail['driver_name']) {
                                $details[] = '👤 ' . $shipmentDetail['driver_name'];
                            }
                                                         $details[] = '⚖️ Брутто: ' . number_format($shipmentDetail['weight'], 2) . ' кг';
                             $details[] = '🧽 Чистый: ' . number_format($shipmentDetail['clean_weight'], 2) . ' кг';
                             if ($shipmentDetail['price_per_kg'] > 0) {
                                 $details[] = '💵 Цена: ' . number_format($shipmentDetail['price_per_kg'], 0) . ' ' . $currency . '/кг';
                             }
                             if ($shipmentDetail['total_amount'] > 0) {
                                 $details[] = '💰 СУММА: ' . number_format($shipmentDetail['total_amount'], 0, ',', ' ') . ' ' . $currency;
                             }
                            if ($shipmentDetail['shipping_cost'] > 0) {
                                $details[] = '🚛 Доставка: ' . number_format($shipmentDetail['shipping_cost'], 0, ',', ' ') . ' ' . $currency;
                            }
                            if ($shipmentDetail['actual_clogging'] > 0) {
                                $details[] = '🧹 Засор: ' . number_format($shipmentDetail['actual_clogging'], 1) . '%';
                            }
                            $details[] = '🕐 ' . $shipmentDetail['created_at'];
                            
                            // Заполняем остальные колонки пустыми значениями
                            for ($i = 1; $i < count($this->reportData['dates']) + 2; $i++) {
                                $shipmentRow[] = $i == 1 ? implode(', ', $details) : '';
                            }
                            
                            $data[] = $shipmentRow;
                        }
                    }
                }
            }
            
            $data[] = []; // Пустая строка между металлами
        }
        
        // ОБЩИЕ ИТОГИ
        $data[] = ['🏆 ОБЩИЕ ИТОГИ ЗА ПЕРИОД'];
        $data[] = []; // Пустая строка
        
        // Итоги по дням
        $dayTotalsRow = ['Показатель'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $dayTotalsRow[] = $dateInfo['formatted'];
        }
        $dayTotalsRow[] = 'Итого';
        $data[] = $dayTotalsRow;
        
        // Общие расходы (покупка металла)
        $expensesRow = ['💸 Расходы (покупка металла)'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $dayTotal = $this->reportData['dailyTotals'][$dateInfo['date']];
            $expensesRow[] = $dayTotal['expenses'] > 0 ? number_format($dayTotal['expenses'], 0, ',', ' ') . ' ' . $currency : '-';
        }
        $expensesRow[] = number_format($this->reportData['periodTotal']['expenses'], 0, ',', ' ') . ' ' . $currency;
        $data[] = $expensesRow;
        
        // Общие доходы (продажа металла)
        $incomeMetalRow = ['💰 Доходы (продажа металла)'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $dayTotal = $this->reportData['dailyTotals'][$dateInfo['date']];
            $incomeMetalRow[] = $dayTotal['income'] > 0 ? number_format($dayTotal['income'], 0, ',', ' ') . ' ' . $currency : '-';
        }
        $incomeMetalRow[] = number_format($this->reportData['periodTotal']['income'], 0, ',', ' ') . ' ' . $currency;
        $data[] = $incomeMetalRow;
        
        // Финальный результат
        $finalResultRow = ['🎯 ФИНАЛЬНЫЙ РЕЗУЛЬТАТ'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $dayTotal = $this->reportData['dailyTotals'][$dateInfo['date']];
            $result = $dayTotal['day_result'];
            $finalResultRow[] = ($result > 0 ? '+' : '') . number_format($result, 0, ',', ' ') . ' ' . $currency;
        }
        $periodResult = $this->reportData['periodTotal']['period_result'];
        $finalResultRow[] = ($periodResult > 0 ? '+' : '') . number_format($periodResult, 0, ',', ' ') . ' ' . $currency;
        $data[] = $finalResultRow;
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 35]; // Первая колонка шире для названий
        
        // Остальные колонки
        $totalColumns = count($this->reportData['dates']) + 2;
        for ($i = 1; $i < $totalColumns; $i++) {
            $col = chr(65 + $i);
            $widths[$col] = 20;
        }
        
        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        $totalColumns = count($this->reportData['dates']) + 2;
        $lastColumn = chr(65 + $totalColumns - 1);
        $totalRows = count($this->array());
        
        // Основной заголовок (строка 1)
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1565C0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E3F2FD']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1976D2']
                ]
            ]
        ]);
        
        // Объединяем ячейки заголовка
        $sheet->mergeCells('A1:' . $lastColumn . '1');
        
        // Цвета для чередования блоков металлов
        $metalColors = [
            ['bg' => 'E8F5E8', 'border' => '4CAF50', 'header' => '2E7D32'], // Зеленые тона
            ['bg' => 'E3F2FD', 'border' => '2196F3', 'header' => '1565C0'], // Синие тона
            ['bg' => 'FFF3E0', 'border' => 'FF9800', 'header' => 'E65100'], // Оранжевые тона
            ['bg' => 'F3E5F5', 'border' => '9C27B0', 'header' => '4A148C'], // Фиолетовые тона
            ['bg' => 'FFEBEE', 'border' => 'F44336', 'header' => 'B71C1C'], // Красные тона
        ];
        
        $metalIndex = 0;
        $metalStartRows = [];
        $metalEndRows = [];
        $currentMetalStart = null;
        
        // Найдем строки с заголовками металлов и их окончания
        for ($row = 1; $row <= $totalRows; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            
            // Заголовки секций (ДВИЖЕНИЕ КАССЫ, ДВИЖЕНИЕ МЕТАЛЛОВ, etc.)
            if (strpos($cellValue, '💰 ДВИЖЕНИЕ КАССЫ') !== false || 
                strpos($cellValue, '🔩 ДВИЖЕНИЕ МЕТАЛЛОВ') !== false || 
                strpos($cellValue, '🏆 ОБЩИЕ ИТОГИ') !== false) {
                
                $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1976D2']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '0D47A1']
                        ]
                    ]
                ]);
                $sheet->mergeCells('A' . $row . ':' . $lastColumn . $row);
            }
            
            // Заголовки металлов
            if (strpos($cellValue, '🔸') !== false) {
                // Закрываем предыдущий блок металла если есть
                if ($currentMetalStart !== null) {
                    $metalEndRows[] = $row - 2; // -2 потому что перед каждым металлом пустая строка
                }
                
                $currentMetalStart = $row;
                $metalStartRows[] = $row;
                
                $colorSet = $metalColors[$metalIndex % count($metalColors)];
                
                $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorSet['header']]
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => $colorSet['border']]
                        ]
                    ]
                ]);
                $sheet->mergeCells('A' . $row . ':' . $lastColumn . $row);
                
                $metalIndex++;
            }
            
            // Если это строка "ОБЩИЕ ИТОГИ", закрываем последний блок металла
            if (strpos($cellValue, '🏆 ОБЩИЕ ИТОГИ') !== false && $currentMetalStart !== null) {
                $metalEndRows[] = $row - 2;
                $currentMetalStart = null;
            }
        }
        
        // Если последний металл не был закрыт, закрываем его
        if ($currentMetalStart !== null) {
            $metalEndRows[] = $totalRows;
        }
        
        // Применяем цветовую схему к блокам металлов
        for ($i = 0; $i < count($metalStartRows); $i++) {
            if (!isset($metalEndRows[$i])) continue;
            
            $startRow = $metalStartRows[$i] + 1; // Начинаем после заголовка металла
            $endRow = $metalEndRows[$i];
            $colorSet = $metalColors[$i % count($metalColors)];
            
            // Применяем фон к блоку металла
            for ($row = $startRow; $row <= $endRow; $row++) {
                $cellValue = $sheet->getCell('A' . $row)->getValue();
                
                // Не красим пустые строки
                if (trim($cellValue) == '') continue;
                
                $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $colorSet['bg']]
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $colorSet['border']]
                        ]
                    ]
                ]);
                
                // Результаты делаем более яркими
                if (strpos($cellValue, '📊 Результат') !== false) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $colorSet['header']]
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => $colorSet['border']]
                            ]
                        ]
                    ]);
                }
                
                // Заголовки секций отгрузок
                if (strpos($cellValue, '🚚 ДЕТАЛИ ОТГРУЗОК') !== false) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 11,
                            'color' => ['rgb' => 'FFFFFF']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '673AB7'] // Фиолетовый для отгрузок
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => '512DA8']
                            ]
                        ]
                    ]);
                    $sheet->mergeCells('A' . $row . ':' . $lastColumn . $row);
                }
                
                // Строки детальных отгрузок
                if (strpos($cellValue, '📦 Отгрузка') !== false) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => ['rgb' => '4527A0']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'EDE7F6'] // Очень светло-фиолетовый
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '9C27B0']
                            ]
                        ]
                    ]);
                }
            }
            
            // Добавляем толстую нижнюю границу для разделения блоков
            if ($endRow < $totalRows) {
                $sheet->getStyle('A' . $endRow . ':' . $lastColumn . $endRow)->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => $colorSet['border']]
                        ]
                    ]
                ]);
            }
        }
        
        // Финальный результат
        for ($row = 1; $row <= $totalRows; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if (strpos($cellValue, '🎯 ФИНАЛЬНЫЙ РЕЗУЛЬТАТ') !== false) {
                $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1B5E20'] // Темно-зеленый
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '2E7D32']
                        ]
                    ]
                ]);
            }
            
            // Доходы - светло-зеленый фон только если не входят в блок металла
            if (strpos($cellValue, '💰') !== false || strpos($cellValue, '💵') !== false) {
                // Проверяем, не входит ли эта строка в блок металла
                $inMetalBlock = false;
                for ($i = 0; $i < count($metalStartRows); $i++) {
                    if (isset($metalEndRows[$i]) && $row >= $metalStartRows[$i] && $row <= $metalEndRows[$i]) {
                        $inMetalBlock = true;
                        break;
                    }
                }
                
                if (!$inMetalBlock) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F5E8']
                        ]
                    ]);
                }
            }
            
            // Расходы - светло-красный фон только если не входят в блок металла
            if (strpos($cellValue, '💸') !== false) {
                $inMetalBlock = false;
                for ($i = 0; $i < count($metalStartRows); $i++) {
                    if (isset($metalEndRows[$i]) && $row >= $metalStartRows[$i] && $row <= $metalEndRows[$i]) {
                        $inMetalBlock = true;
                        break;
                    }
                }
                
                if (!$inMetalBlock) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFEBEE']
                        ]
                    ]);
                }
            }
        }
        
        // Общие стили для всей таблицы
        $sheet->getStyle('A1:' . $lastColumn . $totalRows)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);
        
        // Высота строк
        for ($row = 1; $row <= $totalRows; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(28);
        }
        
        return [];
    }

    public function title(): string
    {
        return 'Отчет движения ' . Carbon::parse($this->startDate)->format('d.m') . '-' . Carbon::parse($this->endDate)->format('d.m');
    }
}
