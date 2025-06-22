<div>
    <div class="space-y-6 p-6">
        <flux:header class="flex-wrap justify-between gap-4">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Units') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <flux:button primary wire:click="create">
                <i class="bi bi-plus-lg -ml-1 mr-2"></i>
                {{ __('Create') }}
            </flux:button>
        </flux:header>

        <!-- Адаптивная таблица единиц измерения -->
        <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <!-- Десктопная версия таблицы -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Наименование</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Краткое наименование</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($units as $unit)
                        <tr class="hover:bg-gray-50 transition-colors duration-150" wire:key="{{ $unit->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $unit->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $unit->short_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <flux:button size="sm" wire:click="edit({{ $unit->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                        <flux:icon name="pencil" variant="outline" />
                                    </flux:button>
                                    <flux:button size="sm" wire:click="delete({{ $unit->id }})" 
                                                wire:confirm="Вы уверены, что хотите удалить эту единицу измерения?" 
                                                variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                        <flux:icon name="trash" variant="outline" />
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="space-y-4">
                                    <div class="flex justify-center text-zinc-400">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-lg font-semibold">{{ __('No units found') }}</p>
                                        <p class="text-sm text-zinc-500">{{ __('Please create a new unit to get started.') }}</p>
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
                    <div class="grid grid-cols-3 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div>Наименование</div>
                        <div>Краткое название</div>
                        <div class="text-center">Действия</div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($units as $unit)
                    <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150" wire:key="tablet-{{ $unit->id }}">
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <div class="text-sm font-medium text-gray-900">{{ $unit->name }}</div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $unit->short_name }}
                                </span>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <flux:button size="sm" wire:click="edit({{ $unit->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                        <flux:icon name="pencil" variant="outline" />
                                    </flux:button>
                                    <flux:button size="sm" wire:click="delete({{ $unit->id }})" 
                                                wire:confirm="Вы уверены, что хотите удалить эту единицу измерения?" 
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-lg font-semibold">{{ __('No units found') }}</p>
                                <p class="text-sm text-zinc-500">{{ __('Please create a new unit to get started.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Мобильная версия (карточки) -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200">
                    @forelse($units as $unit)
                    <div class="p-4 space-y-3" wire:key="mobile-{{ $unit->id }}">
                        <!-- Заголовок карточки -->
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">{{ $unit->name }}</div>
                            <div class="flex items-center space-x-2">
                                <flux:button size="sm" wire:click="edit({{ $unit->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                    <flux:icon name="pencil" variant="outline" />
                                </flux:button>
                                <flux:button size="sm" wire:click="delete({{ $unit->id }})" 
                                            wire:confirm="Вы уверены, что хотите удалить эту единицу измерения?" 
                                            variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                    <flux:icon name="trash" variant="outline" />
                                </flux:button>
                            </div>
                        </div>

                        <!-- Краткое название -->
                        <div class="pt-2 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Краткое наименование:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $unit->short_name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 py-12 text-center">
                        <div class="space-y-4">
                            <div class="flex justify-center text-zinc-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-lg font-semibold">{{ __('No units found') }}</p>
                                <p class="text-sm text-zinc-500">{{ __('Please create a new unit to get started.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $units->links() }}
        </div>

        <flux:modal wire:model="isModal" max-width="xl">
            <div class="p-3 sm:p-0">
                <flux:heading class="mb-6">{{ $unit_id ? __('Редактировать еденицу измерения') : __('Создать еденицу измерения') }}</flux:heading>
                <div class="grid grid-cols-1 gap-4 mb-3">
                    <flux:input :label="__('Наименование')" wire:model="name" />
                    <flux:input :label="__('Краткое наименование')" wire:model="short_name" />
                </div>
            </div>
            <div class="flex justify-end gap-x-4 bg-zinc-50 px-4 py-3 dark:bg-zinc-800 sm:px-6">
                <flux:button flat wire:click="closeModal">{{ __('Отменить') }}</flux:button>
                <flux:button primary wire:click="store" class="!bg-green-500 hover:!bg-green-600 !border-green-500">{{ __('Создать') }}</flux:button>
            </div>
        </flux:modal>
    </div>
</div>
