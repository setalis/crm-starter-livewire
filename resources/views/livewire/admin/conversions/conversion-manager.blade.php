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
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">№ операции</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Исходный продукт</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Количество</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Целевой продукт</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Количество</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Тип</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Элементы</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Заметки</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($conversions as $operation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $operation->operation_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $operation->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->sourceProduct->name }}</div>
                            <div class="text-sm text-gray-500">
                                Остаток: {{ $operation->conversion->sourceProduct->stock }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $operation->conversion->source_quantity }} {{ $operation->conversion->sourceProduct->unit->name ?? 'кг' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $operation->conversion->targetProduct->name }}</div>
                            <div class="text-sm text-gray-500">
                                Остаток: {{ $operation->conversion->targetProduct->stock }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $operation->conversion->target_quantity }} {{ $operation->conversion->targetProduct->unit->name ?? 'кг' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $operation->conversion->conversion_type === 'equal' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $operation->conversion->conversion_type === 'equal' ? 'Равнозначная' : 'Неравнозначная' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($operation->conversion->conversion_type === 'unequal' && $operation->conversion->elements->count() > 0)
                                <div class="text-sm">
                                    @foreach($operation->conversion->elements as $element)
                                        <div>{{ $element->element->name }}: {{ $element->quantity }} {{ $element->element->unit->name ?? 'кг' }}</div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="text-sm">{{ $operation->conversion->notes ?: '—' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out"
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
    </div>

    <!-- Модальное окно для создания конвертации -->
    <flux:modal wire:model.self="isModal" class="md:w-4xl">
        <form wire:submit.prevent="store">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Новая конвертация продукта</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Исходный продукт -->
                    <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Исходный продукт</label>
                    <select wire:model.live="source_product_id" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Выберите продукт</option>
                        @foreach($sourceProducts as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->stock }} {{ $product->unit->name ?? 'кг' }})
                            </option>
                        @endforeach
                    </select>
                        @error('source_product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Целевой продукт -->
                    <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Целевой продукт</label>
                    <select wire:model.live="target_product_id" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Выберите продукт</option>
                        @foreach($allProducts as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (остаток: {{ $product->stock }} {{ $product->unit->name ?? 'кг' }})
                            </option>
                        @endforeach
                    </select>
                        @error('target_product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Количество исходного продукта -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Количество для конвертации</label>
                        <input type="number" step="0.001" wire:model="source_quantity" placeholder="0.000"
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('source_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($source_product_id)
                            @php $sourceProduct = $sourceProducts->find($source_product_id) @endphp
                            @if($sourceProduct)
                                <p class="mt-1 text-sm text-gray-500">
                                    Доступно: {{ $sourceProduct->stock }} {{ $sourceProduct->unit->name ?? 'кг' }}
                                </p>
                            @endif
                        @endif
                    </div>

                    <!-- Количество целевого продукта -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Количество результата</label>
                        <input type="number" step="0.001" wire:model="target_quantity" placeholder="0.000"
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('target_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Информация о типе конвертации -->
                @if($target_product_id)
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        @php $targetProduct = $allProducts->find($target_product_id) @endphp
                        @if($targetProduct)
                            <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">
                                Тип конвертации: 
                                @if($targetProduct->type === 'composite')
                                    <span class="text-amber-600 dark:text-amber-400">В составной продукт</span>
                                @else
                                    <span class="text-green-600 dark:text-green-400">В простой продукт</span>
                                @endif
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                @if($targetProduct->type === 'composite')
                                    При конвертации в составной продукт автоматически добавляются элементы согласно рецептуре.
                                @else
                                    Простая замена запасов одного продукта на другой.
                                @endif
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Элементы для составного продукта (автоматический расчет) -->
                @if($target_product_id && !empty($calculatedElements))
                    <div class="border rounded-lg p-4 bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800">
                        <h4 class="font-medium mb-4 text-amber-900 dark:text-amber-100">
                            Элементы составного продукта (автоматический расчет)
                        </h4>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                            Следующие элементы будут добавлены в запасы согласно составу продукта:
                        </p>
                        
                        <div class="space-y-2">
                            @foreach($calculatedElements as $elementId => $data)
                                <div class="flex items-center justify-between bg-white dark:bg-gray-700 p-3 rounded border dark:border-gray-600">
                                    <div>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $data['name'] }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ $data['percentage'] }}% состава)</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ round($data['quantity'], 3) }} {{ $data['unit'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Заметки -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заметки</label>
                    <textarea wire:model="notes" rows="3" placeholder="Дополнительная информация о конвертации..."
                              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Отмена
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Выполнить конвертацию
                    </button>
                </div>
            </div>
        </form>
    </flux:modal>
</div> 