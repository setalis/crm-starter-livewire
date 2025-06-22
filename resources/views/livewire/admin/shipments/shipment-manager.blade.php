<div class="space-y-6 p-6">
    <flux:header class="flex-wrap justify-between gap-4 mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('dashboard')">Главная</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.warehouse.stock.index')">Склад</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Отгрузки</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <div class="flex justify-end w-full mt-4 gap-2">
            @can('shipments.export')
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Экспорт
                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        <button type="button" wire:click="exportShipments" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <div>
                                    <div class="font-medium">Сводный отчет (CSV)</div>
                                    <div class="text-xs text-gray-500">По отгрузкам</div>
                                </div>
                            </div>
                        </button>
                        <button type="button" wire:click="exportShipmentsExcel" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <div>
                                    <div class="font-medium">Сводный отчет (Excel)</div>
                                    <div class="text-xs text-gray-500">По отгрузкам</div>
                                </div>
                            </div>
                        </button>
                        <hr class="my-1">
                        <button type="button" wire:click="exportShipmentsDetailed" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                <div>
                                    <div class="font-medium">Детальный отчет</div>
                                    <div class="text-xs text-gray-500">По каждой позиции</div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            @endcan
            @can('shipments.create')
            <button type="button" wire:click="openModal" class="inline-flex items-center px-6 py-2 border border-transparent text-base leading-6 font-semibold rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Создать отгрузку
            </button>
            @endcan
        </div>
    </flux:header>

    <!-- Фильтры -->
    <div class="flex flex-wrap gap-4 mb-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Статус</label>
            <select wire:model="filterStatus" class="rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                <option value="">Все</option>
                <option value="draft">Черновик</option>
                <option value="confirmed">Подтверждено</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Предприятие</label>
            <input type="text" wire:model.live.debounce.300ms="filterCompany" placeholder="Поиск по предприятию" 
                data-flux-control
                data-flux-group-target
                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
        </div>
    </div>

    <!-- Таблица отгрузок -->
    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
        <!-- Десктопная версия таблицы -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Предприятие</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Позиции</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Валовая выручка</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Чистая прибыль</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $shipment->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $shipment->created_at->format('d.m.Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $shipment->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $shipment->company ?: 'Не указано' }}</div>
                                @if($shipment->car_number)
                                    <div class="text-xs text-gray-500">{{ $shipment->car_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($shipment->items as $item)
                                        <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-0.5 text-xs">
                                            {{ $products->find($item->product_id)->name ?? '' }} ({{ $item->weight }} кг)
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($shipment->stage === 'confirmed')
                                    @php $revenue = $this->getShipmentRevenue($shipment); @endphp
                                    <span class="font-semibold text-blue-600">
                                        {{ \App\Helpers\Settings::formatPrice($revenue) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($shipment->stage === 'confirmed')
                                    @php $profit = $this->getShipmentProfit($shipment); @endphp
                                    <span class="font-semibold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ \App\Helpers\Settings::formatPrice($profit) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shipment->stage === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}
                                    </span>
                                    @if($shipment->stage === 'draft')
                                        @can('shipments.confirm')
                                        <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Внести фактические данные">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            Внести факт
                                        </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-colors duration-150" title="Подробнее">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    @can('shipments.edit')
                                    <button type="button" wire:click="openModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-yellow-600 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-500 transition-colors duration-150" title="Редактировать">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    @endcan
                                    @if($shipment->stage === 'confirmed')
                                        @can('shipments.confirm')
                                        <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-green-600 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500 transition-colors duration-150" title="Редактировать фактические данные">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-zinc-400 dark:text-zinc-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-zinc-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p>Нет отгрузок</p>
                                    <p class="text-xs">Создайте первую отгрузку</p>
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
                <div class="grid grid-cols-5 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div>Отгрузка</div>
                    <div>Предприятие</div>
                    <div>Статус</div>
                    <div class="text-right">Финансы</div>
                    <div class="text-center">Действия</div>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($shipments as $shipment)
                <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="grid grid-cols-5 gap-4 items-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">#{{ $shipment->id }}</div>
                            <div class="text-xs text-gray-500">{{ $shipment->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $shipment->company ?: 'Не указано' }}</div>
                            @if($shipment->car_number)
                                <div class="text-xs text-gray-500">{{ $shipment->car_number }}</div>
                            @endif
                            <div class="text-xs text-blue-600 mt-1">{{ $shipment->items->count() }} поз.</div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $shipment->stage === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}
                            </span>
                            @if($shipment->stage === 'draft')
                                @can('shipments.confirm')
                                <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                    Внести факт
                                </button>
                                @endcan
                            @endif
                        </div>
                        <div class="text-right">
                            @if($shipment->stage === 'confirmed')
                                @php 
                                    $revenue = $this->getShipmentRevenue($shipment);
                                    $profit = $this->getShipmentProfit($shipment);
                                @endphp
                                <div class="text-sm font-medium text-blue-600">{{ \App\Helpers\Settings::formatPrice($revenue) }}</div>
                                <div class="text-xs {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($profit) }}</div>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500" title="Подробнее">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                @can('shipments.edit')
                                <button type="button" wire:click="openModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-yellow-600 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-500" title="Редактировать">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                @endcan
                                @if($shipment->stage === 'confirmed')
                                    @can('shipments.confirm')
                                    <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-green-600 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500" title="Редактировать фактические данные">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </button>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-zinc-400">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-zinc-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>Нет отгрузок</p>
                        <p class="text-xs">Создайте первую отгрузку</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Мобильная версия (карточки) -->
        <div class="md:hidden">
            <div class="divide-y divide-gray-200">
                @forelse($shipments as $shipment)
                <div class="p-4 space-y-3">
                    <!-- Заголовок карточки -->
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">#{{ $shipment->id }}</div>
                            <div class="text-xs text-gray-500">{{ $shipment->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $shipment->stage === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}
                        </span>
                    </div>

                    <!-- Детали отгрузки -->
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-500 text-sm">Предприятие:</span>
                            <div class="font-medium text-gray-900">{{ $shipment->company ?: 'Не указано' }}</div>
                            @if($shipment->car_number)
                                <div class="text-xs text-gray-500">{{ $shipment->car_number }}</div>
                            @endif
                        </div>
                        
                        <div>
                            <span class="text-gray-500 text-sm">Позиции ({{ $shipment->items->count() }}):</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($shipment->items as $item)
                                    <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-0.5 text-xs">
                                        {{ $products->find($item->product_id)->name ?? '' }} ({{ $item->weight }} кг)
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Финансы и действия -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                        <div>
                            @if($shipment->stage === 'confirmed')
                                @php 
                                    $revenue = $this->getShipmentRevenue($shipment);
                                    $profit = $this->getShipmentProfit($shipment);
                                @endphp
                                <div class="text-sm font-medium text-blue-600">{{ \App\Helpers\Settings::formatPrice($revenue) }}</div>
                                <div class="text-xs {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($profit) }}</div>
                            @else
                                <span class="text-gray-400 text-sm">Финансы не рассчитаны</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($shipment->stage === 'draft')
                                @can('shipments.confirm')
                                <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="text-xs text-blue-600 hover:text-blue-800 px-2 py-1 border border-blue-200 rounded">
                                    Внести факт
                                </button>
                                @endcan
                            @endif
                            <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200" title="Подробнее">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            @can('shipments.edit')
                            <button type="button" wire:click="openModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-yellow-600 bg-yellow-100 hover:bg-yellow-200" title="Редактировать">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            @endcan
                            @if($shipment->stage === 'confirmed')
                                @can('shipments.confirm')
                                <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-green-600 bg-green-100 hover:bg-green-200" title="Редактировать фактические данные">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-zinc-400">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-zinc-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>Нет отгрузок</p>
                        <p class="text-xs">Создайте первую отгрузку</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Модальное окно создания отгрузки -->
    @if($isModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-3xl p-6 relative">
                <button type="button" wire:click="closeModal" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Создание отгрузки</h3>
                <form wire:submit.prevent="saveShipment" class="space-y-6">
                    <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Позиции отгрузки</h4>
                            @if($editingItemIndex !== null)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Редактирование позиции #{{ $editingItemIndex + 1 }}
                                </span>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-2">
                            <select wire:model.live="product_id" class="col-span-2 rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="">Выберите металл</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (остаток: {{ $product->stock }} кг)
                                    </option>
                                @endforeach
                            </select>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" wire:model.live="weight" placeholder="Вес, кг" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                @if($product_id && $weight)
                                    @php
                                        $selectedProduct = $products->find($product_id);
                                        $available = $selectedProduct ? $selectedProduct->stock : 0;
                                    @endphp
                                    @if($weight > $available)
                                        <div class="absolute -bottom-5 left-0 text-xs text-red-600">
                                            Недостаточно на складе ({{ $available }} кг)
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <select wire:model.live="writeoff_type" class="rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                <option value="partial">С остатком</option>
                                <option value="full">В ноль</option>
                            </select>
                            <input type="number" step="0.01" min="0" wire:model.live="stock_after" placeholder="Остаток на складе" class="rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            @if($editingItemIndex !== null)
                                <div class="flex gap-2">
                                    <button type="button" wire:click="updateShipmentItem" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        Обновить
                                    </button>
                                    <button type="button" wire:click="cancelEditItem" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Отмена
                                    </button>
                                </div>
                            @else
                                <button type="button" wire:click="addShipmentItem" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    Добавить
                                </button>
                            @endif
                        </div>
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full table-auto rounded-lg overflow-hidden">
                                <thead class="bg-zinc-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Металл</th>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Вес, кг</th>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Тип списания</th>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Остаток на складе</th>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Расхождение</th>
                                        <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Предв. затраты</th>
                                        <th class="px-3.5 py-2.5"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @php $totalCost = 0; @endphp
                                    @forelse($shipmentItems as $index => $item)
                                        @php
                                            $product = $products->find($item['product_id']);
                                            $purchase = $product?->average_purchase_price ?? 0;
                                            $clogging = $product?->clogging ?? 0;
                                            
                                            // Правильная формула затрат: Чистый вес × Средняя цена
                                            $cleanWeightCost = $item['weight'] * (1 - ($clogging / 100));
                                            $cost = $cleanWeightCost * $purchase;
                                            $totalCost += $cost;
                                        @endphp
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 {{ $editingItemIndex === $index ? 'bg-yellow-50 border-l-4 border-yellow-400' : '' }}">
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                                <div class="font-medium">{{ $products->find($item['product_id'])->name ?? '' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    Ср. цена: {{ \App\Helpers\Settings::formatPrice($purchase) }}/кг
                                                    @if($clogging > 0)
                                                        | Засор: {{ $clogging }}%
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item['weight'] }}</td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $item['writeoff_type'] == 'full' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $item['writeoff_type'] == 'full' ? 'В ноль' : 'С остатком' }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item['stock_after'] ?? '—' }}</td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                                @if(isset($item['stock_discrepancy']) && $item['stock_discrepancy'] != 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $item['stock_discrepancy'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $item['stock_discrepancy'] > 0 ? '+' : '' }}{{ $item['stock_discrepancy'] }} кг
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-sm font-medium">{{ \App\Helpers\Settings::formatPrice($cost) }}</td>
                                            <td class="whitespace-nowrap px-3.5 py-2.5 text-center">
                                                <div class="flex items-center gap-1">
                                                    <button type="button" wire:click="editShipmentItem({{ $index }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500" title="Редактировать позицию">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <button type="button" wire:click="removeShipmentItem({{ $index }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-red-600 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500" title="Удалить позицию">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-zinc-400 dark:text-zinc-500">Нет добавленных позиций</td>
                                        </tr>
                                    @endforelse
                                    @if(count($shipmentItems) > 0)
                                        <tr class="bg-zinc-50 dark:bg-zinc-800 font-semibold">
                                            <td colspan="5" class="px-3.5 py-2.5 text-right">Общие предварительные затраты:</td>
                                            <td class="px-3.5 py-2.5">{{ \App\Helpers\Settings::formatPrice($totalCost) }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Номер автомобиля</label>
                            <input type="text" wire:model="car_number" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">ФИО водителя</label>
                            <input type="text" wire:model="driver_name" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Предприятие</label>
                            <input type="text" wire:model="company" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Затраты на отгрузку</label>
                            <input type="number" step="0.01" min="0" wire:model="shipping_cost" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Комментарий</label>
                            <textarea wire:model="comment" rows="2" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"></textarea>
                        </div>
                    </div>
                    @can('comments.create')
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Ваш комментарий</label>
                        <textarea wire:model="user_comment" rows="2" placeholder="Добавьте ваш комментарий к отгрузке..." class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"></textarea>
                    </div>
                    @endcan
                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-base leading-6 font-semibold rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Сохранить отгрузку
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($isConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-3xl p-6 relative">
                <button type="button" wire:click="closeConfirmModal" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Внесение фактических данных</h3>
                <form wire:submit.prevent="saveConfirmation" class="space-y-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto rounded-lg overflow-hidden">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Металл</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Вес (заявл.)</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Факт. вес</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Цена на заводе</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Засор</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Затраты</th>
                                    <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Прибыль</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @php $totalProfit = 0; @endphp
                                @foreach($confirmItems as $index => $item)
                                    @php
                                        $product = $products->find($item['product_id']);
                                        $purchase = $product?->average_purchase_price ?? 0;
                                        $clogging = $product?->clogging ?? 0;
                                        
                                        // Правильная формула затрат: Чистый вес × Средняя цена
                                        $cleanWeightCost = $item['weight'] * (1 - ($clogging / 100));
                                        $cost = $cleanWeightCost * $purchase;
                                        
                                        // Вычисляем чистый вес без засора
                                        $actualWeight = $item['actual_weight'] ?? 0;
                                        $actualPrice = $item['actual_price'] ?? 0;
                                        $actualClogging = $item['actual_clogging'] ?? 0;
                                        $cleanWeight = $actualWeight * (1 - ($actualClogging / 100));
                                        
                                        $income = $cleanWeight * $actualPrice;
                                        $profit = $income - $cost;
                                        $totalProfit += $profit;
                                    @endphp
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $products->find($item['product_id'])->name ?? '' }}</td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item['weight'] }}</td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                            <input type="number" step="0.01" min="0" wire:model.defer="confirmItems.{{ $index }}.actual_weight" class="w-24 rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                        </td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                            <input type="number" step="0.01" min="0" wire:model.defer="confirmItems.{{ $index }}.actual_price" class="w-24 rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                        </td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                            <input type="number" step="0.01" min="0" wire:model.defer="confirmItems.{{ $index }}.actual_clogging" class="w-20 rounded-md border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-900 focus:border-blue-500 focus:ring focus:ring-blue-200/50 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                                        </td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                            {{ number_format($cost, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                            <span class="font-semibold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($profit, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <td colspan="6" class="px-3.5 py-2.5 text-right font-semibold">Общая прибыль (без затрат на отгрузку):</td>
                                    <td class="px-3.5 py-2.5 font-bold">
                                        <span class="{{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($totalProfit, 2) }}</span>
                                    </td>
                                </tr>
                                @if($confirmShipment && $confirmShipment->shipping_cost > 0)
                                <tr>
                                    <td colspan="6" class="px-3.5 py-2.5 text-right font-semibold">Затраты на отгрузку:</td>
                                    <td class="px-3.5 py-2.5 font-bold text-red-600">
                                        -{{ number_format($confirmShipment->shipping_cost, 2) }}
                                    </td>
                                </tr>
                                <tr class="border-t-2 border-gray-400">
                                    <td colspan="6" class="px-3.5 py-2.5 text-right font-bold">Чистая прибыль:</td>
                                    <td class="px-3.5 py-2.5 font-bold">
                                        @php $netProfit = $totalProfit - ($confirmShipment->shipping_cost ?? 0); @endphp
                                        <span class="{{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($netProfit, 2) }}</span>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-base leading-6 font-semibold rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Подтвердить отгрузку
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Модальное окно подробностей -->
    @if($isDetailsModal && $detailsShipment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-2xl p-6 relative">
                <button type="button" wire:click="closeDetailsModal" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Подробности отгрузки #{{ $detailsShipment->id }}</h3>
                <div class="mb-2 text-sm text-gray-700 dark:text-gray-200">
                    <div><b>Дата:</b> {{ $detailsShipment->created_at->format('d.m.Y H:i') }}</div>
                    <div><b>Статус:</b> {{ $detailsShipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}</div>
                    <div><b>Предприятие:</b> {{ $detailsShipment->company }}</div>
                    <div><b>Номер авто:</b> {{ $detailsShipment->car_number }}</div>
                    <div><b>Водитель:</b> {{ $detailsShipment->driver_name }}</div>
                    <div><b>Комментарий:</b> {{ $detailsShipment->comment }}</div>
                    <div><b>Затраты на отгрузку:</b> {{ \App\Helpers\Settings::formatPrice($detailsShipment->shipping_cost) }}</div>
                    <div class="mt-2"><b>Валовая выручка:</b> <span class="font-semibold text-blue-600">{{ \App\Helpers\Settings::formatPrice($this->getShipmentRevenue($detailsShipment)) }}</span></div>
                    <div><b>Чистая прибыль:</b> <span class="font-semibold {{ $this->getShipmentProfit($detailsShipment) >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($this->getShipmentProfit($detailsShipment)) }}</span></div>
                </div>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full table-auto rounded-lg overflow-hidden">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Металл</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Вес (заявл.)</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Факт. вес</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Цена на заводе</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Засор</th>
                                <th class="px-3.5 py-2.5 text-left text-sm font-semibold">Расхождение склада</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($detailsShipment->items as $item)
                                <tr>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $products->find($item->product_id)->name ?? '' }}</td>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item->weight }}</td>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item->actual_weight }}</td>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item->actual_price }}</td>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">{{ $item->actual_clogging }}</td>
                                    <td class="whitespace-nowrap px-3.5 py-2.5 text-sm">
                                        @if($item->stock_discrepancy && $item->stock_discrepancy != 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $item->stock_discrepancy > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $item->stock_discrepancy > 0 ? '+' : '' }}{{ $item->stock_discrepancy }} кг
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
