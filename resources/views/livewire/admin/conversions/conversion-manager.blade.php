<div>
    <div class="flex items-center justify-between py-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Конвертация продуктов</h1>
        <button type="button" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                wire:click="openModal">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Новая конвертация
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <!-- Адаптивная таблица конвертаций -->
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <!-- Десктопная версия таблицы -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">№ операции</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Исходный продукт</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Кол-во</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Целевой продукт</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Кол-во</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Тип</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Элементы</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Заметки</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($conversions as $operation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->operation_number }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \App\Helpers\Settings::formatDateTime($operation->created_at) }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->sourceProduct->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Остаток: {{ $operation->conversion->sourceProduct->stock }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $operation->conversion->source_quantity }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->targetProduct->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Остаток: {{ $operation->conversion->targetProduct->stock }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $operation->conversion->target_quantity }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $operation->conversion->conversion_type === 'equal',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $operation->conversion->conversion_type === 'unequal'
                                ])>
                                    {{ $operation->conversion->conversion_type === 'equal' ? 'Равнозначная' : 'Неравнозначная' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                @if($operation->conversion->conversion_type === 'unequal' && $operation->conversion->elements->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($operation->conversion->elements as $element)
                                            <div class="text-xs">{{ $element->element->name }}: {{ $element->quantity }} {{ $element->element->unit->name ?? 'кг' }}</div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                                <div class="text-xs truncate" title="{{ $operation->conversion->notes }}">{{ $operation->conversion->notes ?: '—' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out"
                                        wire:click="reverseConversion({{ $operation->id }})" 
                                        wire:confirm="Вы уверены, что хотите отменить эту конвертацию?">
                                    Отменить
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Конвертации не найдены
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Планшетная версия таблицы -->
            <div class="hidden md:block lg:hidden">
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-6 gap-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <div>Операция</div>
                        <div>Исходный</div>
                        <div>Целевой</div>
                        <div>Тип</div>
                        <div>Элементы</div>
                        <div class="text-center">Действия</div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($conversions as $operation)
                    <div class="px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <div class="grid grid-cols-6 gap-4 items-start">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->operation_number }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ \App\Helpers\Settings::formatDate($operation->created_at) }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $operation->conversion->sourceProduct->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $operation->conversion->source_quantity }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $operation->conversion->targetProduct->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $operation->conversion->target_quantity }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}</div>
                            </div>
                            <div>
                                <span @class([
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $operation->conversion->conversion_type === 'equal',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $operation->conversion->conversion_type === 'unequal'
                                ])>
                                    {{ $operation->conversion->conversion_type === 'equal' ? 'Равная' : 'Неравная' }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @if($operation->conversion->conversion_type === 'unequal' && $operation->conversion->elements->count() > 0)
                                    {{ $operation->conversion->elements->count() }} эл.
                                @else
                                    —
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="button" 
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out"
                                        wire:click="reverseConversion({{ $operation->id }})" 
                                        wire:confirm="Вы уверены, что хотите отменить эту конвертацию?">
                                    Отменить
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        Конвертации не найдены
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Мобильная версия (карточки) -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($conversions as $operation)
                    <div class="p-4 space-y-3">
                        <!-- Заголовок карточки -->
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->operation_number }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ \App\Helpers\Settings::formatDateTime($operation->created_at) }}</div>
                            </div>
                            <span @class([
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $operation->conversion->conversion_type === 'equal',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $operation->conversion->conversion_type === 'unequal'
                            ])>
                                {{ $operation->conversion->conversion_type === 'equal' ? 'Равнозначная' : 'Неравнозначная' }}
                            </span>
                        </div>

                        <!-- Детали конвертации -->
                        <div class="space-y-3">
                            <!-- Исходный продукт -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Исходный продукт</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->sourceProduct->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $operation->conversion->source_quantity }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Остаток: {{ $operation->conversion->sourceProduct->stock }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}
                                </div>
                            </div>

                            <!-- Стрелка конвертации -->
                            <div class="flex justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </div>

                            <!-- Целевой продукт -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <div class="text-xs text-blue-600 dark:text-blue-400 mb-1">Целевой продукт</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->targetProduct->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $operation->conversion->target_quantity }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Остаток: {{ $operation->conversion->targetProduct->stock }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}
                                </div>
                            </div>

                            <!-- Элементы (если есть) -->
                            @if($operation->conversion->conversion_type === 'unequal' && $operation->conversion->elements->count() > 0)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                                <div class="text-xs text-yellow-600 dark:text-yellow-400 mb-2">Добавленные элементы</div>
                                <div class="space-y-1">
                                    @foreach($operation->conversion->elements as $element)
                                        <div class="text-xs text-gray-600 dark:text-gray-300">
                                            {{ $element->element->name }}: {{ $element->quantity }} {{ $element->element->unit->name ?? 'кг' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Заметки (если есть) -->
                            @if($operation->conversion->notes)
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-medium">Заметки:</span> {{ $operation->conversion->notes }}
                            </div>
                            @endif
                        </div>

                        <!-- Действия -->
                        <div class="flex justify-center pt-2 border-t border-gray-100 dark:border-gray-600">
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out"
                                    wire:click="reverseConversion({{ $operation->id }})" 
                                    wire:confirm="Вы уверены, что хотите отменить эту конвертацию?">
                                Отменить конвертацию
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Конвертации не найдены
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для создания конвертации -->
    <flux:modal wire:model.self="isModal" class="md:w-4xl">
        <form wire:submit.prevent="store">
            <div class="p-6">
                <!-- Заголовок -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Новая конвертация продукта</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Преобразование одного продукта в другой с автоматическим управлением запасами</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Секция выбора продуктов -->
                    <div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                                    <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Выбор продуктов</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Укажите исходный и целевой продукт для конвертации</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Исходный продукт -->
                            <div>
                                <flux:field>
                                    <flux:label>Исходный продукт</flux:label>
                                    <flux:select wire:model="source_product_id" wire:change="sourceProductChanged" placeholder="Выберите продукт для конвертации">
                                        @foreach($sourceProducts as $product)
                                            <flux:select.option value="{{ $product->id }}">
                                                {{ $product->name }} ({{ $product->stock }} {{ $product->unit->name ?? 'кг' }})
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="source_product_id" />
                                </flux:field>
                            </div>

                            <!-- Целевой продукт -->
                            <div>
                                <flux:field>
                                    <flux:label>Целевой продукт</flux:label>
                                    <flux:select wire:model="target_product_id" wire:change="targetProductChanged" placeholder="Выберите результирующий продукт">
                                        @foreach($allProducts as $product)
                                            <flux:select.option value="{{ $product->id }}">
                                                {{ $product->name }} (остаток: {{ $product->stock }} {{ $product->unit->name ?? 'кг' }})
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="target_product_id" />
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Секция количества -->
                    <div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                                    <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Количества</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Количество исходного продукта и результата конвертации</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Количество исходного продукта -->
                            <div>
                                <flux:field>
                                    <flux:label>Количество для конвертации</flux:label>
                                    <flux:input type="number" step="0.001" wire:model="source_quantity" wire:blur="sourceQuantityChanged" placeholder="0.000" />
                                    <flux:error name="source_quantity" />
                                    @if($source_product_id)
                                        @php $sourceProduct = $sourceProducts->find($source_product_id) @endphp
                                        @if($sourceProduct)
                                            <flux:description>
                                                Доступно: {{ $sourceProduct->stock }} {{ $sourceProduct->unit->name ?? 'кг' }}
                                            </flux:description>
                                        @endif
                                    @endif
                                </flux:field>
                            </div>

                            <!-- Количество целевого продукта -->
                            <div>
                                <flux:field>
                                    <flux:label>Количество результата</flux:label>
                                    <div class="flex gap-2">
                                        <flux:input type="number" step="0.001" wire:model="target_quantity" wire:blur="targetQuantityChanged" placeholder="0.000" class="flex-1" />
                                        <flux:button size="sm" variant="ghost" wire:click="refreshCalculatedElements" title="Обновить расчеты">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </flux:button>
                                    </div>
                                    <flux:error name="target_quantity" />
                                    <flux:description>
                                        Автоматически заполняется как исходное количество, можно изменить при необходимости
                                    </flux:description>
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о типе конвертации -->
                    @if($target_product_id)
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                            @php $targetProduct = $allProducts->find($target_product_id) @endphp
                            @if($targetProduct)
                                <div class="flex items-start space-x-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $targetProduct->type === 'composite' ? 'bg-amber-100 dark:bg-amber-900' : 'bg-green-100 dark:bg-green-900' }}">
                                        @if($targetProduct->type === 'composite')
                                            <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-blue-900 dark:text-blue-100">
                                            Тип конвертации: 
                                            @if($targetProduct->type === 'composite')
                                                <span class="text-amber-600 dark:text-amber-400">В составной продукт</span>
                                            @else
                                                <span class="text-green-600 dark:text-green-400">В простой продукт</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                            @if($targetProduct->type === 'composite')
                                                При конвертации в составной продукт автоматически добавляются элементы согласно рецептуре.
                                            @else
                                                Простая замена запасов одного продукта на другой.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Элементы для составного продукта (автоматический расчет) -->
                    @if($target_product_id && !empty($calculatedElements))
                        <div>
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Элементы составного продукта</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Автоматически рассчитанные элементы, которые будут добавлены в запасы</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($calculatedElements as $elementId => $data)
                                    <div class="flex items-center justify-between bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-500 text-white text-sm font-medium">
                                                {{ $data['percentage'] }}%
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $data['name'] }}</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $data['percentage'] }}% от состава продукта</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-semibold text-purple-600 dark:text-purple-400 text-lg">
                                                {{ round($data['quantity'], 3) }}
                                            </span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $data['unit'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Секция заметок и комментариев -->
                    <div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-900">
                                    <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Дополнительная информация</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Заметки и комментарии к конвертации</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <!-- Заметки -->
                            <div>
                                <flux:field>
                                    <flux:label>Заметки</flux:label>
                                    <flux:textarea wire:model.defer="notes" rows="3" placeholder="Дополнительная информация о конвертации..." />
                                    <flux:error name="notes" />
                                </flux:field>
                            </div>

                            <!-- Комментарий -->
                            @can('comments.create')
                            <div>
                                <flux:field>
                                    <flux:label>Комментарий</flux:label>
                                    <flux:textarea wire:model.defer="user_comment" rows="2" placeholder="Ваш комментарий к конвертации..." />
                                    <flux:description>Комментарий будет сохранен в истории операции</flux:description>
                                </flux:field>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                
                <!-- Footer с кнопками -->
                <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3 -m-6 mt-6">
                    <flux:button variant="ghost" wire:click="closeModal">
                        Отмена
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Выполнить конвертацию
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div> 