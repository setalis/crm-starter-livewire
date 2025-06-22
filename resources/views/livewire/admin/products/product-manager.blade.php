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
                <!-- Заголовок карточки -->
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

                <!-- Детали товара -->
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

                <!-- Действия -->
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

    <flux:modal wire:model="isModal" max-width="2xl">
        <div class="p-4 sm:p-6">
            <flux:heading class="mb-6">{{ $product_id ? __('Edit product') : __('Create product') }}</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:input :label="__('Наименование')" wire:model="name" class="sm:col-span-2" />
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
                <div class="sm:col-span-2">
                    <label for="photo" class="block text-sm font-medium text-gray-700">Изображение</label>
                    <input type="file" wire:model="photo">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover">
                    @elseif ($image)
                        <img src="{{ asset('storage/' . $image) }}" class="mt-2 h-20 w-20 object-cover">
                    @endif
                </div>

                                 @if ($type === 'composite')
                     <div class="sm:col-span-2">
                         <h3 class="text-lg font-medium leading-6 text-gray-900">Элементы</h3>
                         <div class="mt-4 space-y-4">
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
                                             data-flux-control
                                             data-flux-group-target
                                             class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
                                     </div>
                                     <div class="w-1/4 text-sm text-gray-600">
                                         = {{ \App\Helpers\Settings::formatPrice(round($elements->find($elementId)->price * $data['percentage'], 2)) }}/кг
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
                                 
                                 @if($this->getCalculatedCompositePrice() > 0)
                                     <div class="mt-3 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                                         <h4 class="font-medium text-blue-800 mb-2">Пример расчета для заказа:</h4>
                                         <div class="text-sm text-blue-700 space-y-1">
                                             <div>• При весе 5 кг: {{ \App\Helpers\Settings::formatPrice($this->getCalculatedCompositePrice()) }} × 5 = <strong>{{ \App\Helpers\Settings::formatPrice($this->getCalculatedCompositePrice() * 5) }}</strong></div>
                                             <div>• При весе 10 кг: {{ \App\Helpers\Settings::formatPrice($this->getCalculatedCompositePrice()) }} × 10 = <strong>{{ \App\Helpers\Settings::formatPrice($this->getCalculatedCompositePrice() * 10) }}</strong></div>
                                         </div>
                                     </div>
                                 @endif
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
                                        data-flux-control
                                        data-flux-group-target
                                        class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
                                    @error('element_percentage_to_add') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <flux:button wire:click="addElement" :disabled="!$element_id_to_add || !$element_percentage_to_add">{{ __('Добавить элемент') }}</flux:button>
                        </div>
                    </div>
                @endif

                 <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Ценовые шкалы</h3>
                    <div class="mt-4 space-y-4">
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
                
                <!-- Комментарий -->
                @can('comments.create')
                <div class="sm:col-span-2 mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
                    <textarea wire:model="user_comment" rows="2" placeholder="Добавьте комментарий к продукту..." 
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                @endcan
            </div>
        </div>
        <div class="flex justify-end gap-x-4 bg-zinc-50 px-4 py-3 dark:bg-zinc-800 sm:px-6">
            <flux:button flat x-on:click="$wire.isModal = false">{{ __('Cancel') }}</flux:button>
            <flux:button primary wire:click="store">{{ __('Save') }}</flux:button>
        </div>
    </flux:modal>
</div>
