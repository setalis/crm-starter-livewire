<div>
    <div class="space-y-6 p-6">
        <flux:header class="flex-wrap justify-between gap-4">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Elements') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <flux:button primary wire:click="create">
                <i class="bi bi-plus-lg -ml-1 mr-2"></i>
                {{ __('Create') }}
            </flux:button>
        </flux:header>

        <!-- Адаптивная таблица элементов -->
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <!-- Десктопная версия таблицы -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Наименование</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Единица измерения</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Стоимость за 1%</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Стоимость за ед.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Остаток</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($elements as $element)
                        <tr class="hover:bg-gray-50 transition-colors duration-150" wire:key="{{ $element->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $element->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $element->unit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \App\Helpers\Settings::formatPrice($element->price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \App\Helpers\Settings::formatPrice($element->unit_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $element->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($element->stock, 3) }} {{ $element->unit->short_name ?? $element->unit->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <flux:button size="sm" wire:click="edit({{ $element->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                        <flux:icon name="pencil" variant="outline" />
                                    </flux:button>
                                    <flux:button size="sm" wire:click="delete({{ $element->id }})" 
                                                wire:confirm="Вы уверены, что хотите удалить этот элемент?" 
                                                variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                        <flux:icon name="trash" variant="outline" />
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="space-y-4">
                                    <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 14.5M14.25 3.104c.251.023.501.05.75.082M19.8 14.5l-5.069 5.069A2.25 2.25 0 0113.5 20.25H6.75a2.25 2.25 0 01-2.25-2.25V11.25a2.25 2.25 0 012.25-2.25H10.5" />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-lg font-semibold">{{ __('No elements found') }}</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Please create a new element to get started.') }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Планшетная версия таблицы -->
            <div class="hidden md:block lg:hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <div class="grid grid-cols-4 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div>Элемент</div>
                        <div>Стоимость</div>
                        <div>Остаток</div>
                        <div class="text-center">Действия</div>
            </div>
        </div>
                <div class="divide-y divide-gray-200">
            @forelse($elements as $element)
                    <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150" wire:key="tablet-{{ $element->id }}">
                        <div class="grid grid-cols-4 gap-4 items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $element->name }}</div>
                                <div class="text-xs text-gray-500">{{ $element->unit->name }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-900">{{ \App\Helpers\Settings::formatPrice($element->price) }}/1%</div>
                                <div class="text-xs text-gray-500">{{ \App\Helpers\Settings::formatPrice($element->unit_price) }}/{{ $element->unit->short_name ?? 'ед.' }}</div>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $element->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($element->stock, 3) }} {{ $element->unit->short_name ?? $element->unit->name }}
                                </span>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <flux:button size="sm" wire:click="edit({{ $element->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                        <flux:icon name="pencil" variant="outline" />
                            </flux:button>
                                    <flux:button size="sm" wire:click="delete({{ $element->id }})" 
                                                wire:confirm="Вы уверены, что хотите удалить этот элемент?" 
                                                variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                        <flux:icon name="trash" variant="outline" />
                            </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-12 text-center">
                        <div class="space-y-4">
                            <div class="flex justify-center text-zinc-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 14.5M14.25 3.104c.251.023.501.05.75.082M19.8 14.5l-5.069 5.069A2.25 2.25 0 0113.5 20.25H6.75a2.25 2.25 0 01-2.25-2.25V11.25a2.25 2.25 0 012.25-2.25H10.5" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-lg font-semibold">{{ __('No elements found') }}</p>
                                <p class="text-sm text-zinc-500">{{ __('Please create a new element to get started.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Мобильная версия (карточки) -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200">
                    @forelse($elements as $element)
                    <div class="p-4 space-y-3" wire:key="mobile-{{ $element->id }}">
                        <!-- Заголовок карточки -->
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">{{ $element->name }}</div>
                            <div class="flex items-center space-x-2">
                                <flux:button size="sm" wire:click="edit({{ $element->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                    <flux:icon name="pencil" variant="outline" />
                                </flux:button>
                                <flux:button size="sm" wire:click="delete({{ $element->id }})" 
                                            wire:confirm="Вы уверены, что хотите удалить этот элемент?" 
                                            variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                    <flux:icon name="trash" variant="outline" />
                                </flux:button>
                            </div>
                        </div>

                        <!-- Детали элемента -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Единица измерения:</span>
                                <span class="font-medium text-gray-900">{{ $element->unit->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Стоимость за 1%:</span>
                                <span class="font-medium text-gray-900">{{ \App\Helpers\Settings::formatPrice($element->price) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Стоимость за ед.:</span>
                                <span class="font-medium text-gray-900">{{ \App\Helpers\Settings::formatPrice($element->unit_price) }}</span>
                            </div>
                        </div>

                        <!-- Остаток -->
                        <div class="pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Остаток:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $element->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($element->stock, 3) }} {{ $element->unit->short_name ?? $element->unit->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 py-12 text-center">
                        <div class="space-y-4">
                            <div class="flex justify-center text-zinc-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 14.5M14.25 3.104c.251.023.501.05.75.082M19.8 14.5l-5.069 5.069A2.25 2.25 0 0113.5 20.25H6.75a2.25 2.25 0 01-2.25-2.25V11.25a2.25 2.25 0 012.25-2.25H10.5" />
                                </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-lg font-semibold">{{ __('No elements found') }}</p>
                                <p class="text-sm text-zinc-500">{{ __('Please create a new element to get started.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

                <flux:modal wire:model="isModal" class="!max-w-none w-[90vw] lg:w-[60vw] max-h-[90vh] overflow-auto bg-white dark:bg-zinc-800 border border-transparent dark:border-zinc-700 shadow-lg rounded-xl" variant="bare">
            <!-- Компактная шапка формы -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sm:px-8 mt-4 mx-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-white sm:text-2xl">
                            @if($id)
                                Редактирование элемента
                            @else
                                Создание элемента
                            @endif
                        </h1>
                        @if($id && $name)
                            <p class="mt-1 text-blue-100 text-sm">{{ $name }}</p>
                        @endif
                    </div>
                    <button 
                        type="button" 
                        wire:click="$set('isModal', false)"
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
            <div class="mx-4 p-6 bg-white dark:bg-zinc-800">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left column -->
                    <div class="space-y-4">
                        <flux:input :label="__('Наименование')" wire:model="name" />
                        
                        <flux:select :label="__('Единица измерения')" wire:model="unit_id">
                            <flux:select.option value="">-- Выберите единицу измерения --</flux:select.option>
                            @foreach($units as $unit)
                                <flux:select.option value="{{ $unit->id }}">{{ $unit->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input 
                            :label="__('Начальный остаток')" 
                            wire:model="stock" 
                            type="number" 
                            step="0.001" 
                            min="0"
                            placeholder="0.000" />                       
                        
                    </div>
                    
                    <!-- Right column -->
                    <div class="space-y-4">

                        <flux:field>
                            <flux:label>{{ __('Стоимость за 1% содержания') }}</flux:label>
                            <input 
                                wire:model.live="price" 
                                type="number" 
                                step="0.01" 
                                min="0"
                                placeholder="0.00"
                                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5"
                                data-flux-control />
                        </flux:field>
                        <flux:input 
                            :label="__('Стоимость за единицу измерения')" 
                            wire:model="unit_price" 
                            type="number" 
                            step="0.01" 
                            min="0"
                            placeholder="0.00"
                            readonly
                            class="bg-gray-50" />
                        
                        
                        
                        <!-- Комментарий -->
                        @can('comments.create')
                        <flux:field>
                            <flux:label>{{ __('Комментарий') }}</flux:label>
                            <flux:textarea 
                                wire:model="user_comment" 
                                rows="3" 
                                placeholder="Добавьте комментарий к элементу..." />
                        </flux:field>
                        @endcan
                    </div>
                </div>
            </div>
            
            <!-- Футер с кнопками -->
            <div class="bg-white dark:bg-zinc-800 mx-4 mb-4 px-6 py-3 border-t border-gray-200 dark:border-zinc-700 rounded-b-xl flex items-center justify-end space-x-3">
                <button 
                    type="button"
                    wire:click="$set('isModal', false)"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    Отменить
                </button>
                <button 
                    type="button"
                    wire:click="save"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    @if($id)
                        Сохранить
                    @else
                        Создать
                    @endif
                </button>
            </div>
        </flux:modal>
    </div>
</div>
