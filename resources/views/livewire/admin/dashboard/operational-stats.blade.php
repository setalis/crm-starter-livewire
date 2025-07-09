<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-activity text-base text-orange-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Операции</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ ($periodData = $this->getPeriodDates()) ? $periodData['label'] : 'Период не задан' }}
        </div>
    </div>



    <!-- Содержимое -->
    <div class="flex-1 p-2">
        <!-- Основные показатели -->
        <div class="grid grid-cols-3 gap-2 mb-2">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-2 rounded text-center">
                <div class="text-xs text-blue-600 dark:text-blue-400">Всего</div>
                <div class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $totalOperations }}</div>
                <div @class([
                    'text-xs',
                    'text-green-600 dark:text-green-400' => $operationsChange >= 0,
                    'text-red-600 dark:text-red-400' => $operationsChange < 0,
                ])>
                    @if($operationsChange >= 0)
                        +{{ number_format(abs($operationsChange), 1) }}%
                    @else
                        -{{ number_format(abs($operationsChange), 1) }}%
                    @endif
                </div>
            </div>
            
            <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded text-center">
                <div class="text-xs text-green-600 dark:text-green-400">Продажи</div>
                <div class="text-sm font-bold text-green-700 dark:text-green-300">{{ $salesCount }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ \App\Helpers\Settings::formatPrice($averageSaleCheck) }}
                </div>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 p-2 rounded text-center">
                <div class="text-xs text-red-600 dark:text-red-400">Покупки</div>
                <div class="text-sm font-bold text-red-700 dark:text-red-300">{{ $purchasesCount }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ \App\Helpers\Settings::formatPrice($averagePurchaseCheck) }}
                </div>
            </div>
        </div>

        <!-- Средний чек -->
        <div class="bg-purple-50 dark:bg-purple-900/20 p-2 rounded mb-2">
            <div class="text-xs text-purple-600 dark:text-purple-400 text-center">Средний чек</div>
            <div class="text-sm font-bold text-purple-700 dark:text-purple-300 text-center">
                {{ \App\Helpers\Settings::formatPrice($averageCheck) }}
            </div>
        </div>

        <!-- Топ пользователи -->
        @if(count($topUsers) > 0)
        <div>
            <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Топ пользователи</h4>
            <div class="space-y-1 max-h-20 overflow-hidden">
                @foreach(array_slice($topUsers, 0, 3) as $user)
                <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-gray-700 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate">{{ $user['name'] }}</span>
                    <div class="text-right">
                        <div class="text-orange-600 dark:text-orange-400 font-medium">{{ $user['operations_count'] }}</div>
                    </div>
                </div>
                @endforeach
                @if(count($topUsers) > 3)
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    +{{ count($topUsers) - 3 }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div> 