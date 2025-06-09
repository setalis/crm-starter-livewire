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

        {{-- Desktop view --}}
        <div class="hidden overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800 md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">
                            <span class="sr-only"></span>
                        </th>
                        <th scope="col" class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Наименование') }}</th>
                        <th scope="col" class="px-3.5 py-2.5 text-left text-sm font-semibold rtl:text-right">{{ __('Краткое наименование') }}</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($units as $unit)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="{{ $unit->id }}">
                            <td class="px-3.5 py-2.5 text-left text-sm rtl:text-right"></td>
                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $unit->name }}</td>
                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $unit->short_name }}</td>
                            <td class="relative whitespace-nowrap py-2.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                    <flux:button flat wire:click="edit({{ $unit->id }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </flux:button>
                                    <flux:button flat color="danger" wire:click="delete({{ $unit->id }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="space-y-4">
                                    <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                        <i class="bi bi-rulers text-5xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-lg font-semibold">{{ __('No units found') }}</p>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Please create a new unit to get started.') }}</p>
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
        <div class="space-y-4 md:hidden">
            @forelse($units as $unit)
                <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800" wire:key="mobile-{{ $unit->id }}">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold">{{ $unit->name }}</span>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <flux:button flat wire:click="edit({{ $unit->id }})">
                                <i class="bi bi-pencil-fill"></i>
                            </flux:button>
                            <flux:button flat color="danger" wire:click="delete({{ $unit->id }})">
                                <i class="bi bi-trash-fill"></i>
                            </flux:button>
                        </div>
                    </div>
                    <div class="mt-2 space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                        <p><strong>{{ __('Краткое наименование') }}:</strong> {{ $unit->short_name }}</p>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="space-y-4">
                        <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                            <i class="bi bi-rulers text-5xl"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-lg font-semibold">{{ __('No units found') }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Please create a new unit to get started.') }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
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
