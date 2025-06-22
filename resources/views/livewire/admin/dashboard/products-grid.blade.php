<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок с поиском -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-grid-3x3-gap text-lg text-blue-600"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Товары</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                {{ $products->count() }}
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <input 
                wire:model.live.debounce.300ms="searchTerm"
                type="text" 
                placeholder="Поиск товаров..." 
                class="px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
            >
            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                Все →
            </a>
        </div>
    </div>

    <!-- Сетка товаров -->
    <div class="flex-1 overflow-y-auto p-4">
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-2">
                @foreach($products as $product)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200 overflow-hidden text-xs" wire:key="product-{{ $product->id }}">
                    <!-- Изображение товара -->
                    <div class="h-16 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-800 dark:to-blue-900 flex items-center justify-center cursor-pointer" 
                         wire:click="goToPurchase({{ $product->id }})"
                         title="Нажмите для быстрой покупки">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="text-center">
                                <i class="bi bi-box text-lg text-blue-600 dark:text-blue-300"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Информация о товаре -->
                    <div class="p-2">
                        <!-- Название и тип -->
                        <div class="mb-1">
                            <h4 class="font-medium text-gray-900 dark:text-white text-xs truncate" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h4>
                            <div class="flex items-center justify-between mt-0.5">
                                <span @class([
                                    'inline-flex items-center px-1 py-0.5 rounded text-xs font-medium',
                                    'bg-green-100 text-green-700' => $product->type === 'simple',
                                    'bg-blue-100 text-blue-700' => $product->type === 'composite',
                                ])>
                                    {{ $product->type === 'simple' ? 'Простой' : 'Сложный' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ number_format($product->stock, 1) }}
                                    {{ $product->unit->short_name }}
                                </span>
                            </div>
                        </div>

                        <!-- Цены -->
                        @if($editingProduct === $product->id)
                            <!-- Режим редактирования -->
                            <div class="space-y-1">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    wire:model="editingPurchasePrice"
                                    wire:keydown.enter="savePrices"
                                    wire:keydown.escape="cancelEditing"
                                    placeholder="Покупка"
                                    class="w-full px-1 py-0.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    autofocus
                                >
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    wire:model="editingSellingPrice"
                                    wire:keydown.enter="savePrices"
                                    wire:keydown.escape="cancelEditing"
                                    placeholder="Продажа"
                                    class="w-full px-1 py-0.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                >
                                <div class="flex space-x-1">
                                    <button 
                                        wire:click="savePrices"
                                        class="flex-1 px-1 py-0.5 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                    >
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button 
                                        wire:click="cancelEditing"
                                        class="flex-1 px-1 py-0.5 text-xs bg-gray-600 text-white rounded hover:bg-gray-700"
                                    >
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Обычный режим -->
                            <div class="space-y-0.5">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Покупка:</span>
                                    <span class="text-xs font-medium text-green-600 dark:text-green-400">
                                        {{ \App\Helpers\Settings::formatPrice($product->purchase_price ?? 0) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Продажа:</span>
                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                        {{ \App\Helpers\Settings::formatPrice($product->selling_price ?? 0) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-0.5">
                                    <button 
                                        wire:click="startEditing({{ $product->id }})"
                                        class="text-xs text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                        title="Редактировать цены"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button 
                                        wire:click="goToPurchase({{ $product->id }})"
                                        class="text-xs bg-blue-600 text-white px-1 py-0.5 rounded hover:bg-blue-700"
                                        title="Быстрая покупка"
                                    >
                                        <i class="bi bi-plus">Покупка</i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Пустое состояние -->
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <i class="bi bi-search text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    @if($searchTerm)
                        Товары не найдены
                    @else
                        Нет товаров
                    @endif
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    @if($searchTerm)
                        По запросу "{{ $searchTerm }}" ничего не найдено
                    @else
                        Создайте первый товар для начала работы
                    @endif
                </p>
                @if(!$searchTerm)
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-plus mr-2"></i>
                    Создать товар
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

@script
<script>
    $wire.on('product-updated', (event) => {
        if (event.message) {
            console.log(event.message);
            // Здесь можно добавить toast уведомление
        }
    });
</script>
@endscript
