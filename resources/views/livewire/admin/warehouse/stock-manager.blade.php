<div>
    <div class="space-y-6 p-6">
        <flux:header class="flex-wrap justify-between gap-4">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Склад') }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Остатки') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </flux:header>

        <!-- Вкладки -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    wire:click="setActiveTab('products')"
                    @class([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'products',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'products'
                    ])
                >
                    <i class="bi bi-box mr-2"></i>
                    {{ __('Товары') }}
                    <span @class([
                        'ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none rounded-full',
                        'text-blue-100 bg-blue-600' => $activeTab === 'products',
                        'text-gray-600 bg-gray-200 dark:text-gray-300 dark:bg-gray-600' => $activeTab !== 'products'
                    ])>
                        {{ $products->count() }}
                    </span>
                </button>
                
                <button 
                    wire:click="setActiveTab('elements')"
                    @class([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'elements',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'elements'
                    ])
                >
                    <i class="bi bi-puzzle mr-2"></i>
                    {{ __('Элементы') }}
                    <span @class([
                        'ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none rounded-full',
                        'text-blue-100 bg-blue-600' => $activeTab === 'elements',
                        'text-gray-600 bg-gray-200 dark:text-gray-300 dark:bg-gray-600' => $activeTab !== 'elements'
                    ])>
                        {{ $elements->count() }}
                    </span>
                </button>
                
                <button 
                    wire:click="setActiveTab('summary')"
                    @class([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'summary',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'summary'
                    ])
                >
                    <i class="bi bi-bar-chart mr-2"></i>
                    {{ __('Сводка') }}
                </button>
            </nav>
        </div>

        <!-- Контент вкладок -->
        @if($activeTab === 'products')
        <!-- Товары -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Остатки товаров') }}</h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Всего товаров:') }} {{ $products->count() }}
                </div>
            </div>
            
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Наименование') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Тип') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Остаток') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Единица измерения') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Средняя цена закупки') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Сумма') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($products as $product)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="product-{{ $product->id }}">
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $product->name }}</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $product->type === 'simple',
                                        'bg-blue-100 text-blue-800' => $product->type === 'composite',
                                    ])>
                                        {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                    <span @class([
                                        'font-semibold',
                                        'text-red-600' => $product->stock <= 10,
                                        'text-yellow-600' => $product->stock > 10 && $product->stock <= 50,
                                        'text-green-600' => $product->stock > 50,
                                    ])>
                                        {{ number_format($product->stock, 3) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $product->unit->name }}</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ number_format($product->average_purchase_price, 2) }} ₴</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm font-semibold">{{ number_format($product->stock * $product->average_purchase_price, 2) }} ₴</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="space-y-4">
                                        <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                            <i class="bi bi-box text-5xl"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold">{{ __('Товары не найдены') }}</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('В системе пока нет товаров.') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($products->count() > 0)
                        <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <td colspan="5" class="px-3.5 py-2.5 text-sm font-semibold text-right">{{ __('Общая сумма товаров:') }}</td>
                                <td class="px-3.5 py-2.5 text-sm font-bold">
                                    {{ number_format($products->sum(function($product) { return $product->stock * $product->average_purchase_price; }), 2) }} ₴
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($activeTab === 'elements')
        <!-- Элементы -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Остатки элементов') }}</h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Всего элементов:') }} {{ $elements->count() }}
                </div>
            </div>
            
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Наименование') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Остаток') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Единица измерения') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Цена') }}</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Сумма') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($elements as $element)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="element-{{ $element->id }}">
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $element->name }}</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                    <span @class([
                                        'font-semibold',
                                        'text-red-600' => $element->stock <= 10,
                                        'text-yellow-600' => $element->stock > 10 && $element->stock <= 50,
                                        'text-green-600' => $element->stock > 50,
                                    ])>
                                        {{ number_format($element->stock, 3) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $element->unit->name }}</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ number_format($element->average_purchase_price, 2) }} ₴</td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm font-semibold">{{ number_format($element->stock * $element->average_purchase_price, 2) }} ₴</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="space-y-4">
                                        <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                            <i class="bi bi-box text-5xl"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold">{{ __('Элементы не найдены') }}</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('В системе пока нет элементов.') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($elements->count() > 0)
                        <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <td colspan="4" class="px-3.5 py-2.5 text-sm font-semibold text-right">{{ __('Общая сумма элементов:') }}</td>
                                <td class="px-3.5 py-2.5 text-sm font-bold">
                                    {{ number_format($elements->sum(function($element) { return $element->stock * $element->average_purchase_price; }), 2) }} ₴
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($activeTab === 'summary')
        <!-- Сводка -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Сводка склада') }}</h2>
            
            <!-- Статистика карточки -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Товары -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-box text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Товары') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $products->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Элементы -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-puzzle text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Элементы') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $elements->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Стоимость товаров -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-currency-exchange text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Товары') }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($products->sum(function($product) { return $product->stock * $product->average_purchase_price; }), 2) }} ₴
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Стоимость элементов -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-currency-exchange text-2xl text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Элементы') }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($elements->sum(function($element) { return $element->stock * $element->average_purchase_price; }), 2) }} ₴
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Общий итог -->
            <div class="rounded-lg border-2 border-green-200 bg-green-50 p-6 dark:border-green-800 dark:bg-green-900/20">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">{{ __('Общая стоимость склада') }}</h3>
                        <p class="text-sm text-green-600 dark:text-green-300">{{ __('Сумма всех товаров и элементов') }}</p>
                    </div>
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format(
                            $products->sum(function($product) { return $product->stock * $product->average_purchase_price; }) +
                            $elements->sum(function($element) { return $element->stock * $element->average_purchase_price; }),
                            2
                        ) }} ₴
                    </span>
                </div>
            </div>

            <!-- Товары с низким остатком -->
            @php
                $lowStockProducts = $products->filter(function($product) { return $product->stock <= 10; });
                $lowStockElements = $elements->filter(function($element) { return $element->stock <= 10; });
            @endphp
            
            @if($lowStockProducts->count() > 0 || $lowStockElements->count() > 0)
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    {{ __('Предупреждения о низких остатках') }}
                </h3>
                
                @if($lowStockProducts->count() > 0)
                <div class="mb-3">
                    <h4 class="font-medium text-red-700 dark:text-red-300 mb-2">{{ __('Товары') }}:</h4>
                    <div class="space-y-1">
                        @foreach($lowStockProducts as $product)
                        <p class="text-sm text-red-600 dark:text-red-400">
                            • {{ $product->name }} - {{ number_format($product->stock, 3) }} {{ $product->unit->name }}
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($lowStockElements->count() > 0)
                <div>
                    <h4 class="font-medium text-red-700 dark:text-red-300 mb-2">{{ __('Элементы') }}:</h4>
                    <div class="space-y-1">
                        @foreach($lowStockElements as $element)
                        <p class="text-sm text-red-600 dark:text-red-400">
                            • {{ $element->name }} - {{ number_format($element->stock, 3) }} {{ $element->unit->name }}
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>
</div> 