<div>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Переучеты касс</h2>
        @can('cash_recounts.create')
            <flux:button wire:click="openModal" variant="primary">
                Создать переучет кассы
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

    <!-- Список переучетов касс -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Номер
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Касса
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Статус
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ответственный
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ожидаемый баланс
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Фактический баланс
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Расхождение
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Дата создания
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($recounts as $recount)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $recount->number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $recount->cashRegister->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($recount->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($recount->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $recount->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $recount->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \App\Helpers\Settings::formatPrice($recount->expected_balance) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($recount->actual_balance !== null)
                                {{ \App\Helpers\Settings::formatPrice($recount->actual_balance) }}
                            @else
                                <span class="text-gray-400">Не указан</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($recount->actual_balance !== null && $recount->discrepancy != 0)
                                <span class="@if($recount->discrepancy > 0) text-green-600 @else text-red-600 @endif">
                                    {{ $recount->discrepancy > 0 ? '+' : '' }}{{ \App\Helpers\Settings::formatPrice(abs($recount->discrepancy)) }}
                                    ({{ $recount->discrepancy_text }})
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $recount->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            @can('cash_recounts.view')
                                <flux:button size="sm" wire:click="viewRecount({{ $recount->id }})" variant="ghost" class="text-gray-500 hover:text-gray-700" title="Просмотр">
                                    <flux:icon name="eye" variant="outline" />
                                </flux:button>
                            @endcan
                            
                            @if($recount->status === 'pending' && !$recount->started_at)
                                @can('cash_recounts.edit')
                                    <flux:button size="sm" wire:click="startRecount({{ $recount->id }})" variant="ghost" class="text-green-500 hover:text-green-700" title="Начать">
                                        <flux:icon name="play" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @if($recount->status === 'pending' && $recount->started_at)
                                @can('cash_recounts.edit')
                                    <flux:button size="sm" wire:click="showCompleteModal({{ $recount->id }})" variant="ghost" class="text-blue-500 hover:text-blue-700" title="Редактировать">
                                        <flux:icon name="pencil" variant="outline" />
                                    </flux:button>
                                @endcan
                                @can('cash_recounts.complete')
                                    <flux:button size="sm" wire:click="showCompleteModal({{ $recount->id }})" variant="ghost" class="text-green-500 hover:text-green-700" title="Завершить">
                                        <flux:icon name="check" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @if($recount->status === 'pending')
                                @can('cash_recounts.cancel')
                                    <flux:button size="sm" wire:click="cancelRecount({{ $recount->id }})" variant="ghost" class="text-orange-500 hover:text-orange-700" title="Отменить">
                                        <flux:icon name="x-mark" variant="outline" />
                                    </flux:button>
                                @endcan
                            @endif
                            
                            @can('cash_recounts.delete')
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
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            Переучеты касс не найдены
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $recounts->links() }}
    </div>

    <!-- Модальное окно создания переучета кассы -->
    <flux:modal wire:model="isModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Создать переучет кассы</flux:heading>
            </div>

            <div class="space-y-4">
                <flux:field>
                    <flux:label>Касса</flux:label>
                    <flux:select wire:model="form.cash_register_id">
                        <option value="">Выберите кассу</option>
                        @foreach($cashRegisters as $register)
                            <option value="{{ $register->id }}">
                                {{ $register->name }} ({{ \App\Helpers\Settings::formatPrice($register->balance) }})
                            </option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.cash_register_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Причина переучета *</flux:label>
                    <flux:textarea wire:model="form.reason" placeholder="Укажите причину проведения переучета кассы"></flux:textarea>
                    <flux:error name="form.reason" />
                </flux:field>

                <flux:field>
                    <flux:label>Примечания</flux:label>
                    <flux:textarea wire:model="form.notes" placeholder="Дополнительные примечания"></flux:textarea>
                </flux:field>
            </div>

            <div class="flex justify-end space-x-3">
                <flux:button wire:click="closeModal" variant="ghost">
                    Отмена
                </flux:button>
                <flux:button wire:click="createRecount" variant="primary">
                    Создать переучет
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Модальное окно просмотра переучета кассы -->
    <flux:modal wire:model="isViewModal">
        @if($selectedRecount)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Переучет кассы {{ $selectedRecount->number }}</flux:heading>
                    <p class="text-gray-600 mt-1">{{ $selectedRecount->cashRegister->name }}</p>
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
                        <p class="mt-1">{{ $selectedRecount->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    @if($selectedRecount->completed_at)
                        <div>
                            <flux:label>Дата завершения</flux:label>
                            <p class="mt-1">{{ $selectedRecount->completed_at->format('d.m.Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                    <div>
                        <flux:label>Ожидаемый баланс</flux:label>
                        <p class="mt-1 text-lg font-semibold">{{ \App\Helpers\Settings::formatPrice($selectedRecount->expected_balance) }}</p>
                    </div>
                    <div>
                        <flux:label>Фактический баланс</flux:label>
                        <p class="mt-1 text-lg font-semibold">
                            @if($selectedRecount->actual_balance !== null)
                                {{ \App\Helpers\Settings::formatPrice($selectedRecount->actual_balance) }}
                            @else
                                <span class="text-gray-400">Не указан</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <flux:label>Расхождение</flux:label>
                        <p class="mt-1 text-lg font-semibold">
                            @if($selectedRecount->actual_balance !== null && $selectedRecount->discrepancy != 0)
                                <span class="@if($selectedRecount->discrepancy > 0) text-green-600 @else text-red-600 @endif">
                                    {{ $selectedRecount->discrepancy > 0 ? '+' : '' }}{{ \App\Helpers\Settings::formatPrice(abs($selectedRecount->discrepancy)) }}
                                    <br><small>({{ $selectedRecount->discrepancy_text }})</small>
                                </span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </p>
                    </div>
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

                @if($selectedRecount->duration)
                    <div>
                        <flux:label>Длительность</flux:label>
                        <p class="mt-1">{{ $selectedRecount->duration }}</p>
                    </div>
                @endif

                <div class="flex justify-end">
                    <flux:button wire:click="closeViewModal" variant="ghost">
                        Закрыть
                    </flux:button>
                </div>
            </div>
        @endif
        </flux:modal>

    <!-- Модальное окно завершения переучета -->
    <flux:modal wire:model="isCompleteModal">
        @if($selectedRecount)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        @if($selectedRecount->actual_balance !== null)
                            Редактировать переучет кассы
                        @else
                            Завершить переучет кассы
                        @endif
                    </flux:heading>
                    <p class="text-gray-600 mt-1">{{ $selectedRecount->cashRegister->name }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <flux:label>Ожидаемый баланс</flux:label>
                            <p class="mt-1 text-lg font-semibold">{{ \App\Helpers\Settings::formatPrice($selectedRecount->expected_balance) }}</p>
                        </div>
                        <div>
                            <flux:label>Расчетное расхождение</flux:label>
                            <p class="mt-1 text-lg font-semibold" id="discrepancy-preview">
                                @if($completeForm['actual_balance'] !== null)
                                    @php $diff = $completeForm['actual_balance'] - $selectedRecount->expected_balance @endphp
                                    <span class="@if($diff > 0) text-green-600 @elseif($diff < 0) text-red-600 @else text-gray-600 @endif">
                                        {{ $diff > 0 ? '+' : '' }}{{ \App\Helpers\Settings::formatPrice(abs($diff)) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <flux:field>
                    <flux:label>Фактический баланс *</flux:label>
                    <flux:input wire:model.live="completeForm.actual_balance" type="number" step="0.01" min="0" placeholder="0.00" />
                    <flux:error name="completeForm.actual_balance" />
                </flux:field>

                <div class="flex justify-end space-x-3">
                    <flux:button wire:click="closeCompleteModal" variant="ghost">
                        Отмена
                    </flux:button>
                    <flux:button wire:click="completeRecount" variant="primary">
                        @if($selectedRecount->actual_balance !== null)
                            Сохранить изменения
                        @else
                            Завершить переучет
                        @endif
                    </flux:button>
                </div>
            </div>
        @endif
        </flux:modal>
</div> 