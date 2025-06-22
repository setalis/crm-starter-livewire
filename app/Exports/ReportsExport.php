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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportsExport implements FromArray, WithHeadings, WithStyles, WithTitle
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
        
        // Заголовки таблицы
        $headers = ['Позиция'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $headers[] = $dateInfo['formatted'];
        }
        $headers[] = 'ИТОГО ЗА ПЕРИОД';
        $data[] = $headers;
        
        // Строка по кассе
        $cashRow = ['Касса'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $cashData = $this->reportData['cash'][$dateInfo['date']];
            $cashText = '';
            if ($cashData['income'] > 0) {
                $cashText .= 'Пополнение: ' . number_format($cashData['income'], 2) . ' ' . $currency . "\n";
            }
            if ($cashData['expense'] > 0) {
                $cashText .= 'Снятие: ' . number_format($cashData['expense'], 2) . ' ' . $currency;
            }
            $cashRow[] = trim($cashText);
        }
        
        // Итоги кассы
        $cashTotals = $this->reportData['cashTotals'];
        $cashTotalText = '';
        if ($cashTotals['income'] > 0) {
            $cashTotalText .= 'Всего пополнений: ' . number_format($cashTotals['income'], 2) . ' ' . $currency . "\n";
        }
        if ($cashTotals['expense'] > 0) {
            $cashTotalText .= 'Всего снятий: ' . number_format($cashTotals['expense'], 2) . ' ' . $currency . "\n";
        }
        $balance = $cashTotals['income'] - $cashTotals['expense'];
        $cashTotalText .= 'Баланс кассы: ' . number_format($balance, 2) . ' ' . $currency;
        $cashRow[] = trim($cashTotalText);
        
        $data[] = $cashRow;
        
        // Строки по продуктам
        foreach ($this->reportData['products'] as $productName => $productDates) {
            $productRow = [$productName];
            
            foreach ($this->reportData['dates'] as $dateInfo) {
                $productData = $productDates[$dateInfo['date']];
                
                if ($productData['has_data']) {
                    $productText = '';
                    
                    if ($productData['total_weight'] > 0) {
                        $productText .= 'Общий вес: ' . number_format($productData['total_weight'], 2) . ' кг' . "\n";
                    }
                    
                    if ($productData['purchase_avg_price'] > 0) {
                        $productText .= 'Ср. цена покупки: ' . number_format($productData['purchase_avg_price'], 0) . ' ' . $currency . '/кг' . "\n";
                    }
                    
                    if ($productData['sale_avg_price'] > 0) {
                        $productText .= 'Ср. цена продажи: ' . number_format($productData['sale_avg_price'], 0) . ' ' . $currency . '/кг' . "\n";
                    }
                    
                    if ($productData['avg_contamination'] > 0) {
                        $productText .= 'Ср. засор: ' . number_format($productData['avg_contamination'], 1) . '%' . "\n";
                    }
                    
                    if ($productData['purchase_weight'] > 0) {
                        $productText .= 'Покупка: ' . number_format($productData['purchase_weight'], 2) . ' кг' . "\n";
                    }
                    
                    if ($productData['sale_weight'] > 0) {
                        $productText .= 'Продажа: ' . number_format($productData['sale_weight'], 2) . ' кг' . "\n";
                    }
                    
                    if ($productData['shipment_weight'] > 0) {
                        $productText .= 'Отгрузка: ' . number_format($productData['shipment_weight'], 2) . ' кг';
                    }
                    
                    $productRow[] = trim($productText);
                } else {
                    $productRow[] = '-';
                }
            }
            
            // Итоги за период для продукта
            $productTotal = $this->reportData['productTotals'][$productName];
            if ($productTotal['has_data']) {
                $totalText = '';
                
                if ($productTotal['total_weight'] > 0) {
                    $totalText .= 'Общий вес: ' . number_format($productTotal['total_weight'], 2) . ' кг' . "\n";
                }
                
                if ($productTotal['purchase_avg_price'] > 0) {
                    $totalText .= 'Ср. цена покупки: ' . number_format($productTotal['purchase_avg_price'], 0) . ' ' . $currency . '/кг' . "\n";
                }
                
                if ($productTotal['sale_avg_price'] > 0) {
                    $totalText .= 'Ср. цена продажи: ' . number_format($productTotal['sale_avg_price'], 0) . ' ' . $currency . '/кг' . "\n";
                }
                
                if ($productTotal['avg_contamination'] > 0) {
                    $totalText .= 'Ср. засор: ' . number_format($productTotal['avg_contamination'], 1) . '%' . "\n";
                }
                
                if ($productTotal['purchase_weight'] > 0) {
                    $totalText .= 'Всего покупок: ' . number_format($productTotal['purchase_weight'], 2) . ' кг' . "\n";
                }
                
                if ($productTotal['sale_weight'] > 0) {
                    $totalText .= 'Всего продаж: ' . number_format($productTotal['sale_weight'], 2) . ' кг' . "\n";
                }
                
                if ($productTotal['shipment_weight'] > 0) {
                    $totalText .= 'Всего отгрузок: ' . number_format($productTotal['shipment_weight'], 2) . ' кг';
                }
                
                $productRow[] = trim($totalText);
            } else {
                $productRow[] = '-';
            }
            
            $data[] = $productRow;
        }
        
        // Строка итогов за день
        $dailyTotalsRow = ['ИТОГО ЗА ДЕНЬ'];
        foreach ($this->reportData['dates'] as $dateInfo) {
            $dayTotal = $this->reportData['dailyTotals'][$dateInfo['date']];
            
            $dayText = '';
            if ($dayTotal['expenses'] > 0) {
                $dayText .= 'Расход (покупка металла): ' . number_format($dayTotal['expenses'], 0) . ' ' . $currency . "\n";
            }
            if ($dayTotal['income'] > 0) {
                $dayText .= 'Приход (продажа металла): ' . number_format($dayTotal['income'], 0) . ' ' . $currency . "\n";
            }
            if ($dayTotal['cash_income'] > 0) {
                $dayText .= 'Пополнение: ' . number_format($dayTotal['cash_income'], 0) . ' ' . $currency . "\n";
            }
            if ($dayTotal['cash_expense'] > 0) {
                $dayText .= 'Снятие: ' . number_format($dayTotal['cash_expense'], 0) . ' ' . $currency . "\n";
            }
            $dayText .= 'РЕЗУЛЬТАТ: ' . number_format($dayTotal['day_result'], 0) . ' ' . $currency;
            
            $dailyTotalsRow[] = trim($dayText);
        }
        
        // Общий итог за период
        $periodTotal = $this->reportData['periodTotal'];
        $periodText = 'Общий расход: ' . number_format($periodTotal['expenses'], 0) . ' ' . $currency . "\n";
        $periodText .= 'Общий приход: ' . number_format($periodTotal['income'], 0) . ' ' . $currency . "\n";
        $periodText .= 'ИТОГО: ' . number_format($periodTotal['period_result'], 0) . ' ' . $currency;
        $dailyTotalsRow[] = trim($periodText);
        
        $data[] = $dailyTotalsRow;
        
        return $data;
    }

    public function headings(): array
    {
        // Создаем заголовок, который растягивается на все колонки
        $heading = ['Отчет по движению с ' . Carbon::parse($this->startDate)->format('d.m.Y') . ' по ' . Carbon::parse($this->endDate)->format('d.m.Y')];
        
        // Добавляем пустые ячейки для остальных колонок
        $totalColumns = count($this->reportData['dates']) + 2;
        for ($i = 1; $i < $totalColumns; $i++) {
            $heading[] = '';
        }
        
        return $heading;
    }

    public function styles(Worksheet $sheet)
    {
        // Определяем количество колонок (даты + позиция + итого за период)
        $totalColumns = count($this->reportData['dates']) + 2;
        $lastColumn = chr(65 + $totalColumns - 1); // A=65
        
        // Стилизуем заголовок
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E3F2FD',
                ],
            ],
        ]);

        // Автоширина столбцов
        for ($i = 0; $i < $totalColumns; $i++) {
            $col = chr(65 + $i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            // Увеличиваем ширину для колонок с данными
            if ($i > 0) {
                $sheet->getColumnDimension($col)->setWidth(25);
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Отчет по движению';
    }
}
