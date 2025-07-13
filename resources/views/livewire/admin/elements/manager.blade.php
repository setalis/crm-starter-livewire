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

        <flux:modal wire:model="isModal" max-width="lg">
            <div class="p-4 sm:p-6">
                <flux:heading class="mb-6">{{ $id ? __('Edit element') : __('Create element') }}</flux:heading>
                <div class="grid grid-cols-1 gap-4">
                    <flux:input :label="__('Наименование')" wire:model="name" />
                    <flux:select :label="__('Единица измерения')" wire:model="unit_id">
                        @foreach($units as $unit)
                            <flux:select.option value="{{ $unit->id }}">{{ $unit->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:input :label="__('Стоимость за 1% содержания')" wire:model="price" type="number" step="0.01" />
                    <flux:input :label="__('Стоимость за единицу измерения')" wire:model="unit_price" type="number" step="0.01" />
                    <flux:input :label="__('Начальный остаток')" wire:model="stock" type="number" step="0.001" />
                    
                    <!-- Комментарий -->
                    @can('comments.create')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
                        <textarea wire:model="user_comment" rows="2" placeholder="Добавьте комментарий к элементу..." 
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="flex justify-end gap-x-4 bg-zinc-50 px-4 py-3 dark:bg-zinc-800 sm:px-6">
                <flux:button flat x-on:click="$wire.isModal = false">{{ __('Cancel') }}</flux:button>
                <flux:button primary wire:click="save">{{ __('Save') }}</flux:button>
            </div>
        </flux:modal>
    </div>
</div>
