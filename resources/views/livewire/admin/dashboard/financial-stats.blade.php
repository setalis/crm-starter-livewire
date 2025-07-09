<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-currency-dollar text-base text-green-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Финансы</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ ($periodData = $this->getPeriodDates()) ? $periodData['label'] : 'Период не задан' }}
        </div>
    </div>



    <!-- Основные показатели -->
    <div class="flex-1 p-2">
        <div class="grid grid-cols-2 gap-2 h-full">
            <!-- Левая колонка -->
            <div class="space-y-2">
                <!-- Общий оборот -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-2 rounded">
                    <div class="text-xs text-blue-600 dark:text-blue-400">Оборот</div>
                    <div class="text-sm font-bold text-blue-700 dark:text-blue-300">
                        {{ \App\Helpers\Settings::formatPrice($totalRevenue) }}
                    </div>
                    <div @class([
                        'text-xs',
                        'text-green-600 dark:text-green-400' => $revenueChange >= 0,
                        'text-red-600 dark:text-red-400' => $revenueChange < 0,
                    ])>
                        @if($revenueChange >= 0)
                            +{{ number_format(abs($revenueChange), 1) }}%
                        @else
                            -{{ number_format(abs($revenueChange), 1) }}%
                        @endif
                    </div>
                </div>

                <!-- Продажи -->
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-2 rounded">
                    <div class="text-xs text-emerald-600 dark:text-emerald-400">Продажи</div>
                    <div class="text-sm font-bold text-emerald-700 dark:text-emerald-300">
                        {{ \App\Helpers\Settings::formatPrice($salesRevenue) }}
                    </div>
                </div>

                <!-- Средний чек -->
                <div class="bg-orange-50 dark:bg-orange-900/20 p-2 rounded">
                    <div class="text-xs text-orange-600 dark:text-orange-400">Средний чек</div>
                    <div class="text-sm font-bold text-orange-700 dark:text-orange-300">
                        {{ \App\Helpers\Settings::formatPrice($averageCheck) }}
                    </div>
                </div>
            </div>

            <!-- Правая колонка -->
            <div class="space-y-2">
                <!-- Прибыль -->
                <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded">
                    <div class="text-xs text-green-600 dark:text-green-400">Прибыль</div>
                    <div class="text-sm font-bold text-green-700 dark:text-green-300">
                        {{ \App\Helpers\Settings::formatPrice($totalProfit) }}
                    </div>
                    <div @class([
                        'text-xs',
                        'text-green-600 dark:text-green-400' => $profitChange >= 0,
                        'text-red-600 dark:text-red-400' => $profitChange < 0,
                    ])>
                        @if($profitChange >= 0)
                            +{{ number_format(abs($profitChange), 1) }}%
                        @else
                            -{{ number_format(abs($profitChange), 1) }}%
                        @endif
                    </div>
                </div>

                <!-- Покупки -->
                <div class="bg-red-50 dark:bg-red-900/20 p-2 rounded">
                    <div class="text-xs text-red-600 dark:text-red-400">Покупки</div>
                    <div class="text-sm font-bold text-red-700 dark:text-red-300">
                        {{ \App\Helpers\Settings::formatPrice($purchasesCost) }}
                    </div>
                </div>

                <!-- Маржа -->
                <div class="bg-purple-50 dark:bg-purple-900/20 p-2 rounded">
                    <div class="text-xs text-purple-600 dark:text-purple-400">Маржа</div>
                    <div class="text-sm font-bold text-purple-700 dark:text-purple-300">
                        {{ number_format($profitMargin, 1) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 