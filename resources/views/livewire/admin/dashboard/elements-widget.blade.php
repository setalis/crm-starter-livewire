<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-puzzle text-lg text-purple-600"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Элементы</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                {{ $elements->count() }}
            </span>
        </div>
        <a href="{{ route('admin.elements.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            Все элементы →
        </a>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 overflow-y-auto p-4">
        @if($elements->count() > 0)
            <div class="space-y-3">
                @foreach($elements as $element)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $element->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $element->unit->short_name }}</span>
                        </div>
                        <div class="flex items-center space-x-4 mt-1">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Остаток: <span class="font-medium">{{ number_format($element->stock, 3) }}</span>
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                За 1%: <span class="font-medium">{{ \App\Helpers\Settings::formatPrice($element->price) }}</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($editingElement === $element->id)
                            <!-- Режим редактирования -->
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    wire:model="editingUnitPrice"
                                    wire:keydown.enter="saveUnitPrice"
                                    wire:keydown.escape="cancelEditing"
                                    class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    placeholder="0.00"
                                    autofocus
                                >
                                <button 
                                    wire:click="saveUnitPrice"
                                    class="p-1 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                    title="Сохранить"
                                >
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button 
                                    wire:click="cancelEditing"
                                    class="p-1 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                    title="Отмена"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @else
                            <!-- Обычный режим -->
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \App\Helpers\Settings::formatPrice($element->unit_price) }}/{{ $element->unit->short_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Сумма: {{ \App\Helpers\Settings::formatPrice($element->stock * $element->unit_price) }}
                                </div>
                            </div>
                            <button 
                                wire:click="startEditing({{ $element->id }})"
                                class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                title="Редактировать цену"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Итого -->
            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Общая стоимость:</span>
                    <span class="text-lg font-bold text-purple-600 dark:text-purple-400">
                        {{ \App\Helpers\Settings::formatPrice($elements->sum(function($element) { return $element->stock * $element->unit_price; })) }}
                    </span>
                </div>
            </div>
        @else
            <!-- Пустое состояние -->
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <i class="bi bi-puzzle text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Нет элементов</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Создайте первый элемент для начала работы</p>
                <a href="{{ route('admin.elements.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="bi bi-plus mr-2"></i>
                    Создать элемент
                </a>
            </div>
        @endif
    </div>
</div>

@script
<script>
    $wire.on('element-updated', (event) => {
        // Показываем уведомление об успешном обновлении
        if (event.message) {
            // Можно добавить toast уведомление
            console.log(event.message);
        }
    });
</script>
@endscript
