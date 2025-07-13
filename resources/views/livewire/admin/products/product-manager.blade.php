<div>
    <div class="space-y-6 p-6">
        <flux:header class="flex-wrap justify-between gap-4">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Products') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <flux:button primary wire:click="openModal">
                <i class="bi bi-plus-lg -ml-1 mr-2"></i>
                {{ __('Create') }}
            </flux:button>
        </flux:header>

        {{-- Desktop view --}}
        <div class="hidden overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800 md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Наименование') }}</th>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Тип') }}</th>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Стоимость') }}</th>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Остаток') }}</th>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Единица измерения') }}</th>
                            <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Опубликован') }}</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">{{ __('Actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="{{ $product->id }}">
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
                                @if($product->type === 'simple')
                                    <div class="font-medium">{{ \App\Helpers\Settings::formatPrice($product->purchase_price ?? 0) }}</div>
                                    <div class="text-xs text-gray-500">закупка</div>
                                @else
                                    <div class="font-medium text-blue-600">{{ \App\Helpers\Settings::formatPrice($product->getCompositeProductPricePerKg()) }}/кг</div>
                                    <div class="text-xs text-gray-500">{{ $product->elements->count() }} элементов</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $product->stock }}</td>
                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $product->unit->name }}</td>
                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $product->is_published ? 'Да' : 'Нет' }}</td>
                            <td class="relative whitespace-nowrap py-2.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                    <flux:button flat wire:click="edit({{ $product->id }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </flux:button>
                                    <flux:button flat color="danger" wire:click="delete({{ $product->id }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="space-y-4">
                                    <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                        <i class="bi bi-search text-5xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-lg font-semibold">{{ __('No products found') }}</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Please create a new product to get started.') }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile view --}}
        <div class="md:hidden space-y-4">
            @forelse($products as $product)
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm" wire:key="mobile-{{ $product->id }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $product->name }}</h3>
                        <div class="flex items-center space-x-2 mt-1">
                            <span @class([
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-800' => $product->type === 'simple',
                                'bg-blue-100 text-blue-800' => $product->type === 'composite',
                            ])>
                                {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                            </span>
                            <span @class([
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                'bg-green-100 text-green-800' => $product->is_published,
                                'bg-red-100 text-red-800' => !$product->is_published,
                            ])>
                                {{ $product->is_published ? 'Опубликован' : 'Не опубликован' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Стоимость</div>
                        @if($product->type === 'simple')
                            <div class="font-medium text-gray-900 dark:text-white">{{ \App\Helpers\Settings::formatPrice($product->purchase_price ?? 0) }}</div>
                            <div class="text-xs text-gray-500">закупка</div>
                        @else
                            <div class="font-medium text-blue-600">{{ \App\Helpers\Settings::formatPrice($product->getCompositeProductPricePerKg()) }}/кг</div>
                            <div class="text-xs text-gray-500">{{ $product->elements->count() }} элементов</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Остаток</div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ number_format($product->stock, 3) }} {{ $product->unit->name }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100 dark:border-gray-600">
                    <flux:button flat wire:click="edit({{ $product->id }})">
                        <i class="bi bi-pencil-fill"></i>
                        Редактировать
                    </flux:button>
                    <flux:button flat color="danger" wire:click="delete({{ $product->id }})">
                        <i class="bi bi-trash-fill"></i>
                        Удалить
                    </flux:button>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                <div class="space-y-4">
                    <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                        <i class="bi bi-search text-5xl"></i>
                    </div>
                    <div class="space-y-1">
                        <p class="text-lg font-semibold">{{ __('No products found') }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Please create a new product to get started.') }}</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <flux:modal wire:model="isModal" class="!max-w-none w-[90vw] lg:w-[75vw] max-h-[90vh] overflow-auto">
        <!-- Компактная шапка формы -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sm:px-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-xl font-bold text-white sm:text-2xl">
                        @if($product_id)
                            Редактирование продукта
                        @else
                            Создание продукта
                        @endif
                    </h1>
                    @if($product_id && $name)
                        <p class="mt-1 text-blue-100 text-sm">{{ $name }}</p>
                    @endif
                </div>
                <button 
                    type="button" 
                    wire:click="closeModal"
                    class="rounded-lg bg-white/10 p-2 text-white hover:bg-white/20 transition-colors duration-200"
                    title="Закрыть"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Контент формы -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Первые две колонки для полей формы -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Основные поля -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <flux:input :label="__('Наименование')" wire:model="name" />
                        </div>
                        
                        <flux:select :label="__('Тип')" wire:model.live="type">
                            <flux:select.option value="simple">Простой</flux:select.option>
                            <flux:select.option value="composite">Составной</flux:select.option>
                        </flux:select>
                        
                        <flux:select :label="__('Единица измерения')" wire:model="unit_id">
                            <flux:select.option value="">-- Выберите единицу измерения --</flux:select.option>
                            @foreach($units as $unit)
                                <flux:select.option value="{{ $unit->id }}">{{ $unit->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        
                        @if($type === 'simple')
                            <flux:input :label="__('Закупочная стоимость')" wire:model="purchase_price" type="number" step="0.01" />
                            <flux:input :label="__('Засор, %')" wire:model="clogging" type="number" step="0.01" />
                        @endif
                        
                        <flux:input :label="__('Стоимость продажи')" wire:model="selling_price" type="number" step="0.01" />
                        <flux:input :label="__('Начальный остаток')" wire:model="stock" type="number" step="0.001" />
                        
                        <div class="sm:col-span-2">
                            <flux:switch :label="__('Опубликован')" wire:model="is_published" />
                        </div>
                    </div>

                    @if ($type === 'composite')
                        <!-- Состав продукта -->
                        <div>
                            <div class="border-b border-gray-200 pb-3 mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100">
                                        <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Состав продукта</h3>
                                        <p class="text-sm text-gray-500">Укажите элементы и их процентное содержание</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @foreach($selectedElements as $elementId => $data)
                                    <div class="flex items-center space-x-2" wire:key="element-{{ $elementId }}">
                                        <div class="flex-1">
                                            <div class="font-medium">{{ $elements->find($elementId)->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \App\Helpers\Settings::formatPrice($elements->find($elementId)->price) }} за 1% 
                                                ({{ $elements->find($elementId)->unit->name }})
                                            </div>
                                        </div>
                                        <div class="w-1/4">
                                            <input type="number" 
                                                wire:model.live.debounce.500ms="selectedElements.{{ $elementId }}.percentage" 
                                                placeholder="%" 
                                                step="0.01"
                                                class="w-full border rounded-lg py-2 px-3 text-sm">
                                        </div>
                                        <div class="w-1/4 text-sm text-gray-600">
                                            = {{ \App\Helpers\Settings::formatPrice(round($elements->find($elementId)->price * (is_numeric($data['percentage']) ? (float)$data['percentage'] : 0), 2)) }}/кг
                                        </div>
                                        <flux:button flat color="danger" wire:click="removeElement({{ $elementId }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </flux:button>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(!empty($selectedElements))
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Общий процент содержания:</span>
                                        <span class="font-bold {{ $this->getTotalElementsPercentage() > 100 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $this->getTotalElementsPercentage() }}%
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="font-medium">Расчетная стоимость за 1 кг:</span>
                                        <span class="font-bold text-blue-600">{{ \App\Helpers\Settings::formatPrice($this->getCalculatedCompositePrice()) }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            @error('selectedElements') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <div class="mt-4 flex items-center space-x-2">
                                <div class="flex-1">
                                    <flux:select wire:model.live="element_id_to_add">
                                        <option value="">-- Выберите элемент --</option>
                                        @foreach($elements as $element)
                                            @if(!isset($selectedElements[$element->id]))
                                                <flux:select.option value="{{ $element->id }}">{{ $element->name }}</flux:select.option>
                                            @endif
                                        @endforeach
                                    </flux:select>
                                    @error('element_id_to_add') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                @if($element_id_to_add)
                                    <div class="w-1/4">
                                        <input type="number" 
                                            wire:model.live.debounce.500ms="element_percentage_to_add" 
                                            placeholder="%" 
                                            step="0.01"
                                            class="w-full border rounded-lg py-2 px-3 text-sm">
                                        @error('element_percentage_to_add') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                <flux:button wire:click="addElement" :disabled="!$element_id_to_add || !$element_percentage_to_add">{{ __('Добавить элемент') }}</flux:button>
                            </div>
                        </div>
                    @endif

                    <!-- Ценовые шкалы -->
                    <div>
                        <div class="border-b border-gray-200 pb-3 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Ценовые шкалы</h3>
                                    <p class="text-sm text-gray-500">Настройте цены в зависимости от объема заказа</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @foreach($priceScales as $index => $scale)
                                <div class="flex items-center space-x-2" wire:key="scale-{{$index}}">
                                    <flux:input type="number" wire:model="priceScales.{{ $index }}.threshold_kg" placeholder="От (кг)" />
                                    <flux:input type="number" wire:model="priceScales.{{ $index }}.price" placeholder="Цена" />
                                    <flux:button flat color="danger" wire:click="removePriceScale({{ $index }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </flux:button>
                                </div>
                            @endforeach
                        </div>
                        <flux:button class="mt-2" wire:click="addPriceScale">{{ __('Добавить шкалу') }}</flux:button>
                    </div>
                    
                    <!-- Секция комментариев -->
                    @can('comments.create')
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1.1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Комментарий</h3>
                                    <p class="text-sm text-gray-500">Добавьте дополнительную информацию о продукте</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <textarea 
                                wire:model="user_comment" 
                                rows="4" 
                                placeholder="Укажите особенности, примечания или дополнительную информацию о продукте..." 
                                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5 resize-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            ></textarea>
                        </div>
                    </div>
                    @endcan
                </div>
                
                <!-- Третья колонка исключительно для изображения -->
                <div class="lg:col-span-1">
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Изображение продукта</label>
                    
                    <!-- Зона перетаскивания файлов -->
                    <div class="relative" 
                         x-data="fileUploader" 
                         x-init="init()">
                        <div 
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-all duration-200 cursor-pointer min-h-80"
                            :class="{ 
                                'border-blue-500 bg-blue-50': isDragging,
                                'border-green-500 bg-green-50': isUploading
                            }"
                            @dragenter.prevent="isDragging = true"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                            @click="$refs.fileInput.click()"
                        >
                            @if ($photo)
                                <div class="relative h-full flex flex-col justify-center">
                                    <img src="{{ $photo->temporaryUrl() }}" class="max-w-full max-h-60 mx-auto rounded-lg shadow-md object-cover">
                                    <button 
                                        type="button" 
                                        wire:click="$set('photo', null)"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                    >
                                        ×
                                    </button>
                                    <div class="mt-3 text-sm text-gray-600">
                                        Нажмите для замены изображения
                                    </div>
                                </div>
                            @elseif ($image)
                                <div class="relative h-full flex flex-col justify-center">
                                    <img src="{{ asset('storage/' . $image) }}" class="max-w-full max-h-60 mx-auto rounded-lg shadow-md object-cover">
                                    <button 
                                        type="button" 
                                        wire:click="$set('image', null)"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                    >
                                        ×
                                    </button>
                                    <div class="mt-3 text-sm text-gray-600">
                                        Нажмите для замены изображения
                                    </div>
                                </div>
                            @else
                                <div class="space-y-4 h-full flex flex-col justify-center py-8" x-show="!isUploading">
                                    <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-600 mb-2">
                                            <span class="font-medium text-blue-600 hover:text-blue-700 cursor-pointer">
                                                Нажмите для выбора файла
                                            </span>
                                        </p>
                                        <p class="text-gray-500 mb-2">или перетащите изображение сюда</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, GIF до 1MB</p>
                                    </div>
                                </div>

                                <div x-show="isUploading" class="space-y-4 text-center h-full flex flex-col justify-center py-8">
                                    <div class="mx-auto w-10 h-10">
                                        <svg class="animate-spin w-full h-full text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-blue-600">Загрузка изображения...</p>
                                </div>
                            @endif
                        </div>
                        
                        <input 
                            type="file" 
                            x-ref="fileInput"
                            class="hidden" 
                            accept="image/*"
                            wire:model="photo"
                            @change="handleFileSelect($event)"
                        >
                    </div>
                    
                    <!-- Альтернативная кнопка для отладки -->
                    <div class="mt-3">
                        <button 
                            type="button"
                            @click="$refs.fileInput.click()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Загрузить изображение
                        </button>
                    </div>
                    
                    @error('photo') 
                        <div class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Компактный footer с кнопками -->
        <div class="bg-white px-6 py-3 border-t border-gray-200 flex items-center justify-end space-x-3">
            <button 
                type="button"
                wire:click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Отменить
            </button>
            <button 
                type="button"
                wire:click="store"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                @if($product_id)
                    Сохранить
                @else
                    Создать
                @endif
            </button>
        </div>
    </flux:modal>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUploader', () => ({
            isDragging: false,
            isUploading: false,
            
            init() {
                console.log('File uploader initialized');
            },
            
            handleDrop(e) {
                console.log('File dropped');
                this.isDragging = false;
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleFile(files[0]);
                }
            },
            
            handleFileSelect(e) {
                console.log('File selected');
                const files = e.target.files;
                if (files.length > 0) {
                    this.handleFile(files[0]);
                }
            },
            
            handleFile(file) {
                console.log('Processing file:', file.name);
                
                // Проверка типа файла
                if (!file.type.startsWith('image/')) {
                    alert('Пожалуйста, выберите файл изображения');
                    return;
                }
                
                // Проверка размера файла (1MB)
                if (file.size > 1024 * 1024) {
                    alert('Размер файла не должен превышать 1MB');
                    return;
                }
                
                this.isUploading = true;
                
                // Используем Livewire для загрузки файла
                this.$wire.upload('photo', file, 
                    (uploadedFilename) => {
                        this.isUploading = false;
                        console.log('File uploaded successfully');
                    },
                    (error) => {
                        this.isUploading = false;
                        console.error('Upload error:', error);
                        alert('Ошибка загрузки файла');
                    },
                    (event) => {
                        // Прогресс загрузки
                        console.log('Upload progress:', event.detail.progress);
                    }
                );
            }
        }))
    });
    </script>
</div>
