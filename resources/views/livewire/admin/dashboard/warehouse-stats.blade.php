<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-boxes text-base text-purple-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Склад</h3>
        </div>
        <a href="{{ route('admin.warehouse.stock.index') }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            Подробнее →
        </a>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 p-2">
        <!-- Основные показатели -->
        <div class="grid grid-cols-3 gap-2 mb-2">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-2 rounded">
                <div class="text-xs text-blue-600 dark:text-blue-400">Товары</div>
                <div class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $totalProducts }}</div>
                @if($lowStockProducts > 0)
                <div class="text-xs text-red-600 dark:text-red-400">{{ $lowStockProducts }} на исходе</div>
                @endif
            </div>
            
            <div class="bg-purple-50 dark:bg-purple-900/20 p-2 rounded">
                <div class="text-xs text-purple-600 dark:text-purple-400">Элементы</div>
                <div class="text-sm font-bold text-purple-700 dark:text-purple-300">{{ $totalElements }}</div>
                @if($lowStockElements > 0)
                <div class="text-xs text-red-600 dark:text-red-400">{{ $lowStockElements }} на исходе</div>
                @endif
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded">
                <div class="text-xs text-green-600 dark:text-green-400">Стоимость</div>
                <div class="text-sm font-bold text-green-700 dark:text-green-300">
                    {{ \App\Helpers\Settings::formatPrice($warehouseValue + $elementsValue) }}
                </div>
            </div>
        </div>

        <!-- Критические остатки -->
        @if(count($criticalProducts) > 0)
        <div class="mb-2">
            <div class="flex items-center space-x-1 mb-1">
                <i class="bi bi-exclamation-triangle text-red-500 text-xs"></i>
                <h4 class="text-xs font-medium text-red-700 dark:text-red-300">Критические остатки</h4>
            </div>
            <div class="space-y-1 max-h-16 overflow-hidden">
                @foreach(array_slice($criticalProducts, 0, 2) as $product)
                <div class="flex items-center justify-between text-xs bg-red-50 dark:bg-red-900/20 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate">{{ $product['name'] }}</span>
                    <span class="text-red-600 dark:text-red-400 font-medium">
                        {{ number_format($product['stock'], 1) }}
                    </span>
                </div>
                @endforeach
                @if(count($criticalProducts) > 2)
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    +{{ count($criticalProducts) - 2 }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Топ товары -->
        @if(count($topProducts) > 0)
        <div>
            <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Топ по стоимости</h4>
            <div class="space-y-1 max-h-16 overflow-hidden">
                @foreach(array_slice($topProducts, 0, 2) as $product)
                <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-gray-700 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate">{{ $product['name'] }}</span>
                    <span class="text-green-600 dark:text-green-400 font-medium">
                        {{ \App\Helpers\Settings::formatPrice($product['value']) }}
                    </span>
                </div>
                @endforeach
                @if(count($topProducts) > 2)
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    +{{ count($topProducts) - 2 }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div> 