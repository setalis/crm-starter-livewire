<div>
    <flux:heading size="xl">{{ __('Отчеты по движению') }}</flux:heading>

    <div class="mt-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <flux:field>
                        <flux:label>Дата начала</flux:label>
                        <flux:input type="date" wire:model="startDate" />
                    </flux:field>
                </div>
                <div>
                    <flux:field>
                        <flux:label>Дата окончания</flux:label>
                        <flux:input type="date" wire:model="endDate" />
                    </flux:field>
                </div>
                <div class="flex items-end space-x-2">
                    <flux:button variant="primary" wire:click="generateReport" icon="chart-bar">
                        Сформировать отчет
                    </flux:button>
                    @if($showReport)
                        <flux:button variant="outline" wire:click="exportToExcel" icon="document-arrow-down">
                            Экспорт в Excel
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($showReport && isset($reportData['dates']))
        <div class="mt-6">
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <!-- Заголовок с датами -->
                        <thead class="bg-blue-50 dark:bg-blue-900/20">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[150px] sticky left-0 bg-blue-50 dark:bg-blue-900/20">
                                    Позиция
                                </th>
                                @foreach($reportData['dates'] as $dateInfo)
                                    <th class="px-4 py-3 text-center text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[200px]">
                                        {{ $dateInfo['formatted'] }}
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[250px] bg-yellow-50 dark:bg-yellow-900/20">
                                    🏁 ИТОГО ЗА ПЕРИОД
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <!-- Строка по кассе -->
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-white dark:bg-zinc-900">
                                    Касса
                                </td>
                                @foreach($reportData['dates'] as $dateInfo)
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top">
                                        @php $cashData = $reportData['cash'][$dateInfo['date']]; @endphp
                                        @if($cashData['income'] > 0 || $cashData['expense'] > 0)
                                            <div class="space-y-1">
                                                @if($cashData['income'] > 0)
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <strong>Пополнение:</strong><br>
                                                        {{ number_format($cashData['income'], 2) }} {{ $this->currencySymbol }}
                                                    </div>
                                                @endif
                                                @if($cashData['expense'] > 0)
                                                    <div class="text-red-600 dark:text-red-400">
                                                        <strong>Снятие:</strong><br>
                                                        {{ number_format($cashData['expense'], 2) }} {{ $this->currencySymbol }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-zinc-400 text-center">-</div>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-50 dark:bg-yellow-900/20">
                                    @php $cashTotals = $reportData['cashTotals']; @endphp
                                    <div class="space-y-1">
                                        @if($cashTotals['income'] > 0)
                                            <div class="text-green-600 dark:text-green-400">
                                                <strong>Всего пополнений:</strong><br>
                                                {{ number_format($cashTotals['income'], 2) }} {{ $this->currencySymbol }}
                                            </div>
                                        @endif
                                        @if($cashTotals['expense'] > 0)
                                            <div class="text-red-600 dark:text-red-400">
                                                <strong>Всего снятий:</strong><br>
                                                {{ number_format($cashTotals['expense'], 2) }} {{ $this->currencySymbol }}
                                            </div>
                                        @endif
                                        <div class="border-t pt-1 mt-2 {{ ($cashTotals['income'] - $cashTotals['expense']) >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                            <strong>Баланс кассы:</strong><br>
                                            {{ ($cashTotals['income'] - $cashTotals['expense']) >= 0 ? '+' : '' }}{{ number_format($cashTotals['income'] - $cashTotals['expense'], 2) }} {{ $this->currencySymbol }}
                                        </div>
                                                                         </div>
                                 </td>
                                 <td class="px-4 py-4 text-center bg-blue-200 dark:bg-blue-900/40 border-r border-zinc-200 dark:border-zinc-700">
                                     <div class="text-blue-900 dark:text-blue-100 font-bold">
                                         <div class="text-lg">✅ ФИНАЛ</div>
                                         <div class="text-sm mt-1">Все данные<br>учтены</div>
                                     </div>
                                 </td>
                             </tr>

                            <!-- Строки по продуктам -->
                            @foreach($reportData['products'] as $productName => $productDates)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-white dark:bg-zinc-900">
                                        {{ $productName }}
                                    </td>
                                    @foreach($reportData['dates'] as $dateInfo)
                                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top">
                                            @php $productData = $productDates[$dateInfo['date']]; @endphp
                                            @if($productData['has_data'])
                                                <div class="space-y-1 text-xs">
                                                    <!-- Общий вес за день -->
                                                    @if($productData['total_weight'] > 0)
                                                        <div class="font-bold text-gray-900 dark:text-gray-100">
                                                            <strong>Общий вес:</strong> {{ number_format($productData['total_weight'], 2) }} кг
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Средние цены -->
                                                    @if($productData['purchase_avg_price'] > 0)
                                                        <div class="text-blue-600 dark:text-blue-400">
                                                            <strong>Ср. цена покупки:</strong> {{ number_format($productData['purchase_avg_price'], 0) }} {{ $this->currencySymbol }}/кг
                                                        </div>
                                                    @endif
                                                    @if($productData['sale_avg_price'] > 0)
                                                        <div class="text-green-600 dark:text-green-400">
                                                            <strong>Ср. цена продажи:</strong> {{ number_format($productData['sale_avg_price'], 0) }} {{ $this->currencySymbol }}/кг
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Средний засор -->
                                                    @if($productData['avg_contamination'] > 0)
                                                        <div class="text-orange-600 dark:text-orange-400">
                                                            <strong>Ср. засор:</strong> {{ number_format($productData['avg_contamination'], 1) }}%
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Операции -->
                                                    @if($productData['purchase_weight'] > 0)
                                                        <div class="text-red-600 dark:text-red-400">
                                                            Покупка: {{ number_format($productData['purchase_weight'], 2) }} кг
                                                        </div>
                                                    @endif
                                                    @if($productData['sale_weight'] > 0)
                                                        <div class="text-green-600 dark:text-green-400">
                                                            Продажа: {{ number_format($productData['sale_weight'], 2) }} кг
                                                        </div>
                                                    @endif
                                                    @if($productData['shipment_weight'] > 0)
                                                        <div class="text-purple-600 dark:text-purple-400">
                                                            Отгрузка: {{ number_format($productData['shipment_weight'], 2) }} кг
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-zinc-400 text-center">-</div>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-50 dark:bg-yellow-900/20">
                                        @php $productTotal = $reportData['productTotals'][$productName]; @endphp
                                        @if($productTotal['has_data'])
                                            <div class="space-y-1 text-xs">
                                                <!-- Общий вес за период -->
                                                @if($productTotal['total_weight'] > 0)
                                                    <div class="font-bold text-gray-900 dark:text-gray-100">
                                                        <strong>Общий вес:</strong> {{ number_format($productTotal['total_weight'], 2) }} кг
                                                    </div>
                                                @endif
                                                
                                                <!-- Средние цены за период -->
                                                @if($productTotal['purchase_avg_price'] > 0)
                                                    <div class="text-blue-600 dark:text-blue-400">
                                                        <strong>Ср. цена покупки:</strong> {{ number_format($productTotal['purchase_avg_price'], 0) }} {{ $this->currencySymbol }}/кг
                                                    </div>
                                                @endif
                                                @if($productTotal['sale_avg_price'] > 0)
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <strong>Ср. цена продажи:</strong> {{ number_format($productTotal['sale_avg_price'], 0) }} {{ $this->currencySymbol }}/кг
                                                    </div>
                                                @endif
                                                
                                                <!-- Средний засор за период -->
                                                @if($productTotal['avg_contamination'] > 0)
                                                    <div class="text-orange-600 dark:text-orange-400">
                                                        <strong>Ср. засор:</strong> {{ number_format($productTotal['avg_contamination'], 1) }}%
                                                    </div>
                                                @endif
                                                
                                                <!-- Итоги операций -->
                                                @if($productTotal['purchase_weight'] > 0)
                                                    <div class="text-red-600 dark:text-red-400">
                                                        Всего покупок: {{ number_format($productTotal['purchase_weight'], 2) }} кг
                                                    </div>
                                                @endif
                                                @if($productTotal['sale_weight'] > 0)
                                                    <div class="text-green-600 dark:text-green-400">
                                                        Всего продаж: {{ number_format($productTotal['sale_weight'], 2) }} кг
                                                    </div>
                                                @endif
                                                @if($productTotal['shipment_weight'] > 0)
                                                    <div class="text-purple-600 dark:text-purple-400">
                                                        Всего отгрузок: {{ number_format($productTotal['shipment_weight'], 2) }} кг
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-zinc-400 text-center">-</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Итоговая строка по дням -->
                            <tr class="bg-gray-100 dark:bg-gray-700 font-bold border-t-2 border-gray-300 dark:border-gray-600">
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-gray-100 dark:bg-gray-700">
                                    💰 ИТОГО ЗА ДЕНЬ
                                </td>
                                @foreach($reportData['dates'] as $dateInfo)
                                    <td class="px-4 py-3 text-sm border-r border-zinc-200 dark:border-zinc-700 align-top">
                                                                                 @php $dayTotal = $reportData['dailyTotals'][$dateInfo['date']]; @endphp
                                         <div class="space-y-1 text-center">
                                             @if($dayTotal['expenses'] > 0)
                                                 <div class="text-red-600 dark:text-red-400">
                                                     <strong>Расход:</strong><br>
                                                     <small>(покупка металла)</small><br>
                                                     {{ number_format($dayTotal['expenses'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                                 </div>
                                             @endif
                                             @if($dayTotal['income'] > 0)
                                                 <div class="text-green-600 dark:text-green-400">
                                                     <strong>Приход:</strong><br>
                                                     <small>(продажа металла)</small><br>
                                                     {{ number_format($dayTotal['income'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                                 </div>
                                             @endif
                                             @if($dayTotal['cash_income'] > 0)
                                                 <div class="text-blue-600 dark:text-blue-400">
                                                     <strong>Пополнение:</strong><br>
                                                     {{ number_format($dayTotal['cash_income'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                                 </div>
                                             @endif
                                             @if($dayTotal['cash_expense'] > 0)
                                                 <div class="text-orange-600 dark:text-orange-400">
                                                     <strong>Снятие:</strong><br>
                                                     {{ number_format($dayTotal['cash_expense'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                                 </div>
                                             @endif
                                            <div class="border-t pt-1 mt-2 {{ $dayTotal['day_result'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                                <strong>РЕЗУЛЬТАТ:</strong><br>
                                                                                                 {{ $dayTotal['day_result'] >= 0 ? '+' : '' }}{{ number_format($dayTotal['day_result'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                            </div>
                                        </div>
                                                                         </td>
                                 @endforeach
                                 <td class="px-4 py-3 text-sm border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-100 dark:bg-yellow-900/30">
                                     @php $periodTotal = $reportData['periodTotal']; @endphp
                                     <div class="space-y-1 text-center">
                                         <div class="text-red-600 dark:text-red-400">
                                             <strong>Общий расход:</strong><br>
                                             {{ number_format($periodTotal['expenses'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                         </div>
                                         <div class="text-green-600 dark:text-green-400">
                                             <strong>Общий приход:</strong><br>
                                             {{ number_format($periodTotal['income'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                         </div>
                                         <div class="border-t pt-1 mt-2 {{ $periodTotal['period_result'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                             <strong>ИТОГО:</strong><br>
                                             {{ $periodTotal['period_result'] >= 0 ? '+' : '' }}{{ number_format($periodTotal['period_result'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                         </div>
                                     </div>
                                 </td>
                             </tr>
                            
                            <!-- Итог за весь период -->
                            <tr class="bg-blue-100 dark:bg-blue-900/30 font-bold border-t-4 border-blue-400 dark:border-blue-600">
                                <td class="px-4 py-4 text-sm font-bold text-blue-900 dark:text-blue-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-blue-100 dark:bg-blue-900/30">
                                    🏆 ИТОГО ЗА ПЕРИОД
                                </td>
                                <td colspan="{{ count($reportData['dates']) }}" class="px-4 py-4 text-center">
                                                                         @php $periodTotal = $reportData['periodTotal']; @endphp
                                     <div class="flex justify-center space-x-8 text-sm">
                                         @if($periodTotal['expenses'] > 0)
                                             <div class="text-red-600 dark:text-red-400">
                                                 <strong>Общие расходы:</strong><br>
                                                 <small>(покупка металла)</small><br>
                                                 {{ number_format($periodTotal['expenses'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                             </div>
                                         @endif
                                         @if($periodTotal['income'] > 0)
                                             <div class="text-green-600 dark:text-green-400">
                                                 <strong>Общий приход:</strong><br>
                                                 <small>(продажа металла)</small><br>
                                                 {{ number_format($periodTotal['income'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                             </div>
                                         @endif
                                        @if($periodTotal['cash_income'] > 0)
                                            <div class="text-blue-600 dark:text-blue-400">
                                                <strong>Пополнения:</strong><br>
                                                                                                 {{ number_format($periodTotal['cash_income'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                            </div>
                                        @endif
                                        @if($periodTotal['cash_expense'] > 0)
                                            <div class="text-orange-600 dark:text-orange-400">
                                                <strong>Снятия:</strong><br>
                                                                                                 {{ number_format($periodTotal['cash_expense'], 0, ',', ' ') }} {{ $this->currencySymbol }}
                                            </div>
                                        @endif
                                        <div class="border-l-2 pl-4 {{ $periodTotal['period_result'] >= 0 ? 'text-green-700 dark:text-green-300 border-green-400' : 'text-red-700 dark:text-red-300 border-red-400' }}">
                                            <strong>ФИНАЛЬНЫЙ РЕЗУЛЬТАТ:</strong><br>
                                                                                         <span class="text-lg">{{ $periodTotal['period_result'] >= 0 ? '+' : '' }}{{ number_format($periodTotal['period_result'], 0, ',', ' ') }} {{ $this->currencySymbol }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($showReport)
        <div class="mt-6">
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-8 text-center">
                <div class="text-zinc-500 dark:text-zinc-400">
                    Нет данных за выбранный период
                </div>
            </div>
        </div>
    @endif
</div> 
