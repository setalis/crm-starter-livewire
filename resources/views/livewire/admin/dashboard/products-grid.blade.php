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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200 overflow-hidden" wire:key="product-{{ $product->id }}">
                    <!-- Изображение товара -->
                    <div class="h-32 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-800 dark:to-blue-900 flex items-center justify-center cursor-pointer" 
                         wire:click="goToPurchase({{ $product->id }})"
                         title="Нажмите для быстрой покупки">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <i class="bi bi-box text-3xl text-blue-600 dark:text-blue-300 mb-2"></i>
                                <div class="text-xs text-blue-600 dark:text-blue-300">Быстрая покупка</div>
                            </div>
                        @endif
                    </div>

                    <!-- Информация о товаре -->
                    <div class="p-3">
                        <!-- Название и тип -->
                        <div class="mb-2">
                            <h4 class="font-medium text-gray-900 dark:text-white text-sm truncate" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h4>
                            <div class="flex items-center justify-between mt-1">
                                <span @class([
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800' => $product->type === 'simple',
                                    'bg-blue-100 text-blue-800' => $product->type === 'composite',
                                ])>
                                    {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($product->stock, 2) }} {{ $product->unit->short_name }}
                                </span>
                            </div>
                        </div>

                        <!-- Цены -->
                        @if($editingProduct === $product->id)
                            <!-- Режим редактирования -->
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Цена покупки</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        wire:model="editingPurchasePrice"
                                        wire:keydown.enter="savePrices"
                                        wire:keydown.escape="cancelEditing"
                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                        autofocus
                                    >
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Цена продажи</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        wire:model="editingSellingPrice"
                                        wire:keydown.enter="savePrices"
                                        wire:keydown.escape="cancelEditing"
                                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    >
                                </div>
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="savePrices"
                                        class="flex-1 px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                    >
                                        <i class="bi bi-check"></i> Сохранить
                                    </button>
                                    <button 
                                        wire:click="cancelEditing"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700"
                                    >
                                        <i class="bi bi-x"></i> Отмена
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Обычный режим -->
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Покупка:</span>
                                    <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                        {{ number_format($product->purchase_price, 2) }} ₴
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Продажа:</span>
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        {{ number_format($product->selling_price, 2) }} ₴
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <button 
                                        wire:click="startEditing({{ $product->id }})"
                                        class="text-xs text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                        title="Редактировать цены"
                                    >
                                        <i class="bi bi-pencil"></i> Изменить
                                    </button>
                                    <button 
                                        wire:click="goToPurchase({{ $product->id }})"
                                        class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700"
                                        title="Быстрая покупка"
                                    >
                                        <i class="bi bi-plus"></i> Купить
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
