<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-2 sm:px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-currency-dollar text-sm sm:text-base text-green-600"></i>
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">Финансы</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">
            {{ $periodLabel }}
        </div>
    </div>

    <!-- Основные показатели -->
    <div class="flex-1 p-1 sm:p-2">
        <div class="grid grid-cols-2 gap-1 sm:gap-2 h-full">
            <!-- Левая колонка -->
            <div class="space-y-1 sm:space-y-2">
                <!-- Общий оборот -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-blue-600 dark:text-blue-400">Оборот</div>
                    <div class="text-xs sm:text-sm font-bold text-blue-700 dark:text-blue-300">
                        {{ \App\Helpers\Settings::formatPrice($totalRevenue) }}
                    </div>
                </div>

                <!-- Прибыль -->
                <div class="bg-green-50 dark:bg-green-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-green-600 dark:text-green-400">Прибыль</div>
                    <div class="text-xs sm:text-sm font-bold text-green-700 dark:text-green-300">
                        {{ \App\Helpers\Settings::formatPrice($totalProfit) }}
                    </div>
                </div>

                <!-- Маржа -->
                <div class="bg-purple-50 dark:bg-purple-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-purple-600 dark:text-purple-400">Маржа</div>
                    <div class="text-xs sm:text-sm font-bold text-purple-700 dark:text-purple-300">
                        {{ number_format($profitMargin, 1) }}%
                    </div>
                </div>
            </div>

            <!-- Правая колонка -->
            <div class="space-y-1 sm:space-y-2">
                <!-- Продажи -->
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-emerald-600 dark:text-emerald-400">Продажи</div>
                    <div class="text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-300">
                        {{ \App\Helpers\Settings::formatPrice($salesRevenue) }}
                    </div>
                </div>

                <!-- Покупки -->
                <div class="bg-red-50 dark:bg-red-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-red-600 dark:text-red-400">Покупки</div>
                    <div class="text-xs sm:text-sm font-bold text-red-700 dark:text-red-300">
                        {{ \App\Helpers\Settings::formatPrice($purchasesCost) }}
                    </div>
                </div>

                <!-- Средний чек -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-1.5 sm:p-2 rounded">
                    <div class="text-xs text-yellow-600 dark:text-yellow-400">Ср. чек</div>
                    <div class="text-xs sm:text-sm font-bold text-yellow-700 dark:text-yellow-300">
                        {{ \App\Helpers\Settings::formatPrice($averageCheck) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Касса -->
        <div class="mt-1 sm:mt-2 pt-1 sm:pt-2 border-t border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500 dark:text-gray-400">Баланс кассы:</span>
                <span class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white">
                    {{ \App\Helpers\Settings::formatPrice($cashBalance) }}
                </span>
            </div>
        </div>
    </div>
</div> 