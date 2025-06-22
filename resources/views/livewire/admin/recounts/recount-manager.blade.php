<div>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Переучеты складских остатков</h2>
        @can('recounts.create')
            <flux:button wire:click="openCreateModal" variant="primary">
                Создать переучет
            </flux:button>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Список переучетов -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номер</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ответственный</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Позиций</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма расхождений</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата создания</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($recounts as $recount)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $recount->number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $recount->type_text }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($recount->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($recount->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $recount->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $recount->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $recount->items->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($recount->total_discrepancy_amount != 0)
                                <span class="@if($recount->total_discrepancy_amount > 0) text-green-600 @else text-red-600 @endif">
                                    {{ number_format($recount->total_discrepancy_amount, 2) }} ₽
                                </span>
                            @else
                                -
                            @endif
                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \App\Helpers\Settings::formatDateTime($recount->created_at) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            @can('recounts.view')
                                <flux:button size="sm" wire:click="viewRecount({{ $recount->id }})" variant="ghost" class="text-gray-500 hover:text-gray-700" title="Просмотр">
                                    <flux:icon name="eye" variant="outline" />
                                </flux:button>
                            @endcan
                            
                            @if($recount->status === 'pending' && !$recount->started_at)
                                @can('recounts.start')
                                    <flux:button size="sm" wire:click="startRecount({{ $recount->id }})" variant="ghost" class="text-green-500 hover:text-green-700" title="Начать">
                                        <flux:icon name="play" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @if($recount->status === 'pending' && $recount->started_at)
                                @can('recounts.complete')
                                    <flux:button size="sm" wire:click="completeRecount({{ $recount->id }})" variant="ghost" class="text-green-500 hover:text-green-700" title="Завершить">
                                        <flux:icon name="check" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @if($recount->status === 'pending')
                                @can('recounts.cancel')
                                    <flux:button size="sm" wire:click="cancelRecount({{ $recount->id }})" variant="ghost" class="text-orange-500 hover:text-orange-700" title="Отменить">
                                        <flux:icon name="x-mark" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @can('recounts.delete')
                                <flux:button size="sm" wire:click="deleteRecount({{ $recount->id }})" 
                                            wire:confirm="Вы уверены, что хотите удалить переучет {{ $recount->number }}?" 
                                            variant="ghost" class="text-red-500 hover:text-red-700" title="Удалить">
                                    <flux:icon name="trash" variant="outline" />
                                </flux:button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Переучеты не найдены</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $recounts->links() }}
    </div>

    <!-- Модальное окно создания переучета -->
    <flux:modal wire:model="showCreateModal">
        <div class="p-6">
            <flux:heading size="lg" class="mb-6">Создать переучет</flux:heading>

            <div class="space-y-4">
                <flux:field>
                    <flux:label>Тип переучета</flux:label>
                    <flux:select wire:model="form.type">
                        <option value="products">Продукты</option>
                        <option value="elements">Элементы</option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Причина переучета *</flux:label>
                    <flux:textarea wire:model="form.reason" placeholder="Укажите причину проведения переучета"></flux:textarea>
                    <flux:error name="form.reason" />
                </flux:field>

                <flux:field>
                    <flux:label>Примечания</flux:label>
                    <flux:textarea wire:model="form.notes" placeholder="Дополнительные примечания"></flux:textarea>
                </flux:field>
            </div>
        </div>
        
        <div class="flex justify-end gap-x-4 bg-zinc-50 px-6 py-3 dark:bg-zinc-800">
            <flux:button flat wire:click="closeModals">
                Отмена
            </flux:button>
            <flux:button primary wire:click="createRecount">
                Создать переучет
            </flux:button>
        </div>
    </flux:modal>

    <!-- Модальное окно просмотра переучета -->
    <flux:modal wire:model="showViewModal">
        @if($selectedRecount)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Переучет {{ $selectedRecount->number }}</flux:heading>
                    <p class="text-gray-600 mt-1">{{ $selectedRecount->type_text }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:label>Статус</flux:label>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($selectedRecount->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($selectedRecount->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $selectedRecount->status_text }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <flux:label>Ответственный</flux:label>
                        <p class="mt-1">{{ $selectedRecount->user->name }}</p>
                    </div>
                    <div>
                        <flux:label>Дата создания</flux:label>
                                                        <p class="mt-1">{{ \App\Helpers\Settings::formatDateTime($selectedRecount->created_at) }}</p>
                    </div>
                    @if($selectedRecount->completed_at)
                        <div>
                            <flux:label>Дата завершения</flux:label>
                                                            <p class="mt-1">{{ \App\Helpers\Settings::formatDateTime($selectedRecount->completed_at) }}</p>
                        </div>
                    @endif
                </div>

                <div>
                    <flux:label>Причина</flux:label>
                    <p class="mt-1">{{ $selectedRecount->reason }}</p>
                </div>

                @if($selectedRecount->notes)
                    <div>
                        <flux:label>Примечания</flux:label>
                        <p class="mt-1">{{ $selectedRecount->notes }}</p>
                    </div>
                @endif

                <!-- Список позиций -->
                <div>
                    <div class="flex justify-between items-center">
                        <flux:heading size="base">Позиции переучета</flux:heading>
                        @if($selectedRecount->status === 'pending' && $selectedRecount->started_at)
                            <div class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded">
                                💡 Введите фактические остатки в поля справа
                            </div>
                        @endif
                    </div>
                    <div class="mt-3 overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Наименование</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ожидается</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Фактически</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Расхождение</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Сумма</th>
                                    @if($selectedRecount->status === 'pending')
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($selectedRecount->items as $item)
                                    <tr>
                                        <td class="px-3 py-2 text-sm">
                                            {{ $item->countable->name }}
                                            @if($item->countable->unit)
                                                <span class="text-gray-500">({{ $item->countable->unit->name }})</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-sm">{{ number_format($item->expected_quantity, 3) }}</td>
                                        <td class="px-3 py-2 text-sm">
                                            @if($selectedRecount->status === 'pending' && $selectedRecount->started_at)
                                                <flux:input 
                                                    wire:model.live.debounce.500ms="quickEditQuantities.{{ $item->id }}" 
                                                    type="number" 
                                                    step="0.001" 
                                                    min="0" 
                                                    placeholder="{{ number_format($item->expected_quantity, 3) }}"
                                                    class="w-20"
                                                />
                                            @else
                                                @if($item->actual_quantity !== null)
                                                    {{ number_format($item->actual_quantity, 3) }}
                                                @else
                                                    <span class="text-gray-400">Не указано</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-sm">
                                            @if($item->actual_quantity !== null)
                                                <span class="@if($item->discrepancy > 0) text-green-600 @elseif($item->discrepancy < 0) text-red-600 @endif">
                                                    {{ $item->discrepancy > 0 ? '+' : '' }}{{ number_format($item->discrepancy, 3) }}
                                                    @if($item->discrepancy != 0)
                                                        ({{ $item->discrepancy_text }})
                                                    @endif
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-sm">
                                            @if($item->discrepancy_amount != 0)
                                                <span class="@if($item->discrepancy_amount > 0) text-green-600 @else text-red-600 @endif">
                                                    {{ $item->discrepancy_amount > 0 ? '+' : '' }}{{ number_format($item->discrepancy_amount, 2) }} ₽
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if($selectedRecount->status === 'pending')
                                            <td class="px-3 py-2 text-sm">
                                                <flux:button size="sm" wire:click="editItem({{ $item->id }})" variant="ghost" class="text-blue-500 hover:text-blue-700" title="Изменить">
                                                    <flux:icon name="pencil" variant="outline" />
                                                </flux:button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($selectedRecount->status === 'completed' && $selectedRecount->total_discrepancy_amount != 0)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Общая сумма расхождений:</span>
                            <span class="text-lg font-bold @if($selectedRecount->total_discrepancy_amount > 0) text-green-600 @else text-red-600 @endif">
                                {{ $selectedRecount->total_discrepancy_amount > 0 ? '+' : '' }}{{ number_format($selectedRecount->total_discrepancy_amount, 2) }} ₽
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    @if($selectedRecount->status === 'pending' && $selectedRecount->started_at)
                        <flux:button wire:click="saveQuickEdit" variant="primary">
                            Сохранить все изменения
                        </flux:button>
                    @endif
                    <flux:button wire:click="closeModals" variant="ghost">
                        Закрыть
                    </flux:button>
                </div>
            </div>
        @endif
        </flux:modal>

    <!-- Модальное окно редактирования позиции -->
    <flux:modal wire:model="showItemModal">
        @if($selectedItem)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Редактировать позицию</flux:heading>
                    <p class="text-gray-600 mt-1">{{ $selectedItem->countable->name }}</p>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Ожидаемое количество</flux:label>
                            <p class="mt-1 text-gray-600">{{ number_format($selectedItem->expected_quantity, 3) }}
                                @if($selectedItem->countable->unit)
                                    {{ $selectedItem->countable->unit->name }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <flux:label>Цена за единицу</flux:label>
                            <p class="mt-1 text-gray-600">{{ number_format($selectedItem->unit_price, 2) }} ₽</p>
                        </div>
                    </div>

                    <flux:field>
                        <flux:label>Фактическое количество *</flux:label>
                        <flux:input wire:model="itemForm.actual_quantity" type="number" step="0.001" min="0" />
                        <flux:error name="itemForm.actual_quantity" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Примечания</flux:label>
                        <flux:textarea wire:model="itemForm.notes" placeholder="Примечания к позиции"></flux:textarea>
                    </flux:field>
                </div>

                <div class="flex justify-end space-x-3">
                    <flux:button wire:click="closeModals" variant="ghost">
                        Отмена
                    </flux:button>
                    <flux:button wire:click="updateItem" variant="primary">
                        Сохранить
                    </flux:button>
                </div>
            </div>
        @endif
        </flux:modal>
</div> 