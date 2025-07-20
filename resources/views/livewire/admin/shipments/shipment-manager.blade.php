<div class="space-y-6 p-6">
    <div class="mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('dashboard')">Главная</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('admin.warehouse.stock.index')">Склад</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Отгрузки</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 sm:gap-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
            <flux:field>
                <flux:label>Статус</flux:label>
                <flux:select wire:model.defer="filterStatus">
                    <option value="">Все</option>
                    <option value="draft">Черновик</option>
                    <option value="confirmed">Подтверждено</option>
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label>Предприятие</flux:label>
                <flux:input 
                    type="text" 
                    wire:model.defer="filterCompany" 
                    placeholder="Поиск по предприятию"
                />
            </flux:field>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 mt-2 sm:mt-0">
            @can('shipments.export')
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Экспорт
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-64 origin-top-right rounded-lg bg-white border border-gray-200 shadow-lg focus:outline-none">
                    <div class="py-2">
                        <button type="button" wire:click="exportShipments" @click="open = false" class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-150">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <div>
                                <div class="font-medium text-gray-900">Сводный отчет (CSV)</div>
                                <div class="text-sm text-gray-500">По отгрузкам</div>
                            </div>
                        </button>
                        <button type="button" wire:click="exportShipmentsExcel" @click="open = false" class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-150">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <div>
                                <div class="font-medium text-gray-900">Сводный отчет (Excel)</div>
                                <div class="text-sm text-gray-500">По отгрузкам</div>
                            </div>
                        </button>
                        <hr class="my-2 border-gray-200">
                        <button type="button" wire:click="exportShipmentsDetailed" @click="open = false" class="flex items-center gap-3 w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-150">
                            <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                            <div>
                                <div class="font-medium text-gray-900">Детальный отчет</div>
                                <div class="text-sm text-gray-500">По каждой позиции</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            @endcan
            @can('shipments.create')
            <button type="button" wire:click="openModal" class="inline-flex items-center gap-2 px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Создать отгрузку
            </button>
            @endcan
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex flex-col gap-1 items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shipment->stage === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $shipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}
                                </span>
                                @if($shipment->stage === 'draft')
                                    @can('shipments.confirm')
                                    <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-200">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        Внести факт
                                    </button>
                                    @endcan
                                @endif
                            </div>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                @can('shipments.edit')
                                    <button type="button" wire:click="openModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-200 transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                @endcan
                                @if($shipment->stage === 'confirmed')
                                    @can('shipments.confirm')
                                        <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
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
                    <div class="text-center">Статус</div>
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
                        <div class="text-center">
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
                                <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="flex items-center justify-center w-7 h-7 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                @can('shipments.edit')
                                <button type="button" wire:click="openModal({{ $shipment->id }})" class="flex items-center justify-center w-7 h-7 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-200 transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                @endcan
                                @if($shipment->stage === 'confirmed')
                                    @can('shipments.confirm')
                                    <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="flex items-center justify-center w-7 h-7 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
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
                            <button type="button" wire:click="openDetailsModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                            @can('shipments.edit')
                            <button type="button" wire:click="openModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-200 transition-colors duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            @endcan
                            @if($shipment->stage === 'confirmed')
                                @can('shipments.confirm')
                                <button type="button" wire:click="openConfirmModal({{ $shipment->id }})" class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
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
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-4xl p-6 relative">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sm:px-8 rounded-t-lg mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white sm:text-2xl">{{ $editMode ? 'Редактирование отгрузки' : 'Создание отгрузки' }}</h3>
                        </div>
                        <button type="button" wire:click="closeModal" class="rounded-lg bg-white/10 p-2 text-white hover:bg-white/20 transition-colors duration-200" title="Закрыть">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <form wire:submit.prevent="saveShipment" class="space-y-6">
                    <div class="border-b border-zinc-200 dark:border-zinc-700 pb-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Позиции отгрузки</h4>
                            @if($editingItemIndex !== null)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Редактирование позиции #{{ $editingItemIndex + 1 }}
                                </span>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <flux:field>
                                    <flux:label>Металл</flux:label>
                                    <flux:select wire:model.defer="product_id">
                                        <option value="">Выберите металл</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (остаток: {{ $product->stock }} кг)
                                            </option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <div>
                                <flux:field>
                                    <flux:label>Вес, кг</flux:label>
                                    <flux:input 
                                        type="number" 
                                        step="0.01" 
                                        min="0" 
                                        wire:model.defer="weight" 
                                        placeholder="0.00"
                                    />
                                    @if($product_id && $weight)
                                        @php
                                            $selectedProduct = $products->find($product_id);
                                            $available = $selectedProduct ? $selectedProduct->stock : 0;
                                        @endphp
                                        @if($weight > $available)
                                            <flux:text class="text-yellow-600 text-sm">На складе: {{ $available }} кг (можно отгрузить больше)</flux:text>
                                        @endif
                                    @endif
                                </flux:field>
                            </div>
                            <div>
                                <flux:field>
                                    <flux:label>Тип списания</flux:label>
                                    <flux:select wire:model.defer="writeoff_type">
                                        <option value="partial">С остатком</option>
                                        <option value="full">В ноль</option>
                                    </flux:select>
                                </flux:field>
                            </div>
                            <div>
                                <flux:field>
                                    <flux:label>Остаток на складе</flux:label>
                                    <flux:input 
                                        type="number" 
                                        step="0.01" 
                                        min="0" 
                                        wire:model.defer="stock_after" 
                                        placeholder="0.00"
                                    />
                                </flux:field>
                            </div>
                            <div class="flex items-end">
                                @if($editingItemIndex !== null)
                                    <div class="flex gap-2 w-full">
                                        <button 
                                            type="button" 
                                            wire:click="updateShipmentItem" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            Обновить
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="cancelEditItem" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            Отмена
                                        </button>
                                    </div>
                                @else
                                    <button 
                                        type="button" 
                                        wire:click="addShipmentItem" 
                                        class="inline-flex items-center gap-2 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        Добавить
                                    </button>
                                @endif
                            </div>
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
                                                    <button type="button" wire:click="editShipmentItem({{ $index }})" class="flex items-center justify-center w-7 h-7 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <button type="button" wire:click="removeShipmentItem({{ $index }})" class="flex items-center justify-center w-7 h-7 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200">
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <flux:field>
                            <flux:label>Номер автомобиля</flux:label>
                            <flux:input 
                                type="text" 
                                wire:model.defer="car_number" 
                                placeholder="А123БВ77"
                            />
                        </flux:field>
                        <flux:field>
                            <flux:label>ФИО водителя</flux:label>
                            <flux:input 
                                type="text" 
                                wire:model.defer="driver_name" 
                                placeholder="Иванов Иван Иванович"
                            />
                        </flux:field>
                        <flux:field>
                            <flux:label>Предприятие</flux:label>
                            <flux:input 
                                type="text" 
                                wire:model.defer="company" 
                                placeholder="ООО Рога и Копыта"
                            />
                        </flux:field>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <flux:field>
                            <flux:label>Затраты на отгрузку</flux:label>
                            <flux:input 
                                type="number" 
                                step="0.01" 
                                min="0" 
                                wire:model.defer="shipping_cost" 
                                placeholder="0.00"
                            />
                        </flux:field>
                        <flux:field>
                            <flux:label>Комментарий</flux:label>
                            <flux:textarea 
                                wire:model.defer="comment" 
                                rows="3" 
                                placeholder="Дополнительная информация об отгрузке"
                            />
                        </flux:field>
                    </div>
                    <div class="pt-6 flex justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-2 text-white rounded-lg transition-colors duration-200 font-medium {{ $editMode ? 'bg-blue-500 hover:bg-blue-600' : 'bg-green-500 hover:bg-green-600' }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            {{ $editMode ? 'Обновить отгрузку' : 'Сохранить отгрузку' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($isConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-5xl p-6 relative">
                <button type="button" wire:click="closeConfirmModal" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Внесение фактических данных</h3>
                <form wire:submit.prevent="saveConfirmation" class="space-y-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Металл</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Вес (заявл.)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Факт. вес</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Цена на заводе</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Засор</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Затраты</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Прибыль</th>
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
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $products->find($item['product_id'])->name ?? '' }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $item['weight'] }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <flux:input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                wire:model.defer="confirmItems.{{ $index }}.actual_weight" 
                                                size="sm"
                                                class="w-28"
                                            />
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <flux:input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                wire:model.defer="confirmItems.{{ $index }}.actual_price" 
                                                size="sm"
                                                class="w-28"
                                            />
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <flux:input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                wire:model.defer="confirmItems.{{ $index }}.actual_clogging" 
                                                size="sm"
                                                class="w-24"
                                            />
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ \App\Helpers\Settings::formatPrice($cost) }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <span class="font-semibold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($profit) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Общая прибыль (без затрат на отгрузку):</td>
                                    <td class="px-4 py-3 font-bold">
                                        <span class="{{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($totalProfit) }}</span>
                                    </td>
                                </tr>
                                @if($confirmShipment && $confirmShipment->shipping_cost > 0)
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Затраты на отгрузку:</td>
                                    <td class="px-4 py-3 font-bold text-red-600">
                                        -{{ \App\Helpers\Settings::formatPrice($confirmShipment->shipping_cost) }}
                                    </td>
                                </tr>
                                <tr class="border-t-2 border-zinc-300 dark:border-zinc-600">
                                    <td colspan="6" class="px-4 py-3 text-right font-bold text-zinc-700 dark:text-zinc-300">Чистая прибыль:</td>
                                    <td class="px-4 py-3 font-bold">
                                        @php $netProfit = $totalProfit - ($confirmShipment->shipping_cost ?? 0); @endphp
                                        <span class="{{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($netProfit) }}</span>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                    <div class="pt-6 flex justify-end">
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-8 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
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
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-4xl p-6 relative">
                <button type="button" wire:click="closeDetailsModal" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Подробности отгрузки #{{ $detailsShipment->id }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Дата:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $detailsShipment->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Статус:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $detailsShipment->stage === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200' }}">
                                {{ $detailsShipment->stage === 'draft' ? 'Черновик' : 'Подтверждено' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Предприятие:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $detailsShipment->company ?: 'Не указано' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Номер авто:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $detailsShipment->car_number ?: 'Не указано' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Водитель:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $detailsShipment->driver_name ?: 'Не указано' }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Затраты на отгрузку:</span>
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ \App\Helpers\Settings::formatPrice($detailsShipment->shipping_cost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Валовая выручка:</span>
                            <span class="text-sm font-semibold text-blue-600">{{ \App\Helpers\Settings::formatPrice($this->getShipmentRevenue($detailsShipment)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Чистая прибыль:</span>
                            <span class="text-sm font-semibold {{ $this->getShipmentProfit($detailsShipment) >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ \App\Helpers\Settings::formatPrice($this->getShipmentProfit($detailsShipment)) }}</span>
                        </div>
                        @if($detailsShipment->comment)
                        <div class="pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Комментарий:</span>
                            <p class="text-sm text-zinc-900 dark:text-zinc-100 mt-1">{{ $detailsShipment->comment }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Металл</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Вес (заявл.)</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Факт. вес</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Цена на заводе</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Засор</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-700 dark:text-zinc-300">Расхождение склада</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($detailsShipment->items as $item)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $products->find($item->product_id)->name ?? '' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->weight }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->actual_weight ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->actual_price ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->actual_clogging ?: '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm">
                                        @if($item->stock_discrepancy && $item->stock_discrepancy != 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->stock_discrepancy > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-200' }}">
                                                {{ $item->stock_discrepancy > 0 ? '+' : '' }}{{ $item->stock_discrepancy }} кг
                                            </span>
                                        @else
                                            <span class="text-zinc-400">—</span>
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
