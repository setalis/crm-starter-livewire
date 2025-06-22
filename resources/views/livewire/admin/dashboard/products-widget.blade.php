<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-box text-lg text-blue-600"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Товары</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                {{ $products->count() }}
            </span>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            Все товары →
        </a>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 overflow-y-auto p-4">
        @if($products->count() > 0)
            <!-- Статистика -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                    <div class="text-sm text-green-600 dark:text-green-400">Простые</div>
                    <div class="text-xl font-bold text-green-700 dark:text-green-300">
                        {{ $products->where('type', 'simple')->count() }}
                    </div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                    <div class="text-sm text-blue-600 dark:text-blue-400">Составные</div>
                    <div class="text-xl font-bold text-blue-700 dark:text-blue-300">
                        {{ $products->where('type', 'composite')->count() }}
                    </div>
                </div>
            </div>

            <!-- Товары с низким остатком -->
            @if($lowStockProducts->count() > 0)
                <div class="mb-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="bi bi-exclamation-triangle text-red-500"></i>
                        <h4 class="font-medium text-red-700 dark:text-red-300">Низкие остатки</h4>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            {{ $lowStockProducts->count() }}
                        </span>
                    </div>
                    <div class="space-y-2 max-h-32 overflow-y-auto">
                        @foreach($lowStockProducts->take(5) as $product)
                        <div class="flex items-center justify-between p-2 bg-red-50 dark:bg-red-900/20 rounded">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-red-600 dark:text-red-400">
                                    {{ number_format($product->stock, 3) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product->unit->short_name }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if($lowStockProducts->count() > 5)
                        <div class="text-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                и еще {{ $lowStockProducts->count() - 5 }} товаров...
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Общая стоимость -->
            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Общая стоимость:</span>
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                        {{ \App\Helpers\Settings::formatPrice($products->sum(function($product) { return $product->stock * ($product->average_purchase_price ?? 0); })) }}
                    </span>
                </div>
            </div>
        @else
            <!-- Пустое состояние -->
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <i class="bi bi-box text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Нет товаров</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Создайте первый товар для начала работы</p>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-plus mr-2"></i>
                    Создать товар
                </a>
            </div>
        @endif
    </div>
</div>
