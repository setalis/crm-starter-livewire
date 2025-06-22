<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if(empty($operations))
                    <button wire:click="addNewOperation('purchase')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Create New Operation
                    </button>
                @else
                    <button wire:click="showOperationsCart" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded my-3">
                        Continue with {{ count($operations) }} open operation(s)
                    </button>
                @endif
                
                @if($isModal)
                    @include('livewire.admin.operations.create')
                @endif
                <!-- Адаптивная таблица операций -->
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <!-- Десктопная версия таблицы -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номер</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Элементы</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($operationsList as $operation)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \App\Helpers\Settings::formatDateTime($operation->created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $operation->operation_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                            'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                            'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                        ])>
                                            @if($operation->type === 'purchase')
                                                Покупка
                                            @elseif($operation->type === 'sale')
                                                Продажа
                                            @elseif($operation->type === 'conversion')
                                                Конвертация
                                            @else
                                                {{ ucfirst($operation->type) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $operation->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button wire:click="showOperationDetails({{ $operation->id }})" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-150">
                                            @if($operation->type === 'conversion')
                                                1
                                            @else
                                                {{ $operation->items->count() }}
                                            @endif
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        {{ \App\Helpers\Settings::formatPrice($operation->total_amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($operation->type === 'conversion')
                                            <span class="text-xs text-gray-500 italic">Управляется в конвертациях</span>
                                        @else
                                            <div class="flex items-center justify-center space-x-1">
                                                <flux:button size="sm" wire:click="showOperationDetails({{ $operation->id }})" variant="ghost" class="text-gray-400 hover:text-gray-600" title="Просмотр">
                                                    <flux:icon name="eye" variant="outline" />
                                                </flux:button>
                                                <flux:button size="sm" wire:click="edit({{ $operation->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                                    <flux:icon name="pencil" variant="outline" />
                                                </flux:button>
                                                <flux:button size="sm" wire:click="delete({{ $operation->id }})" 
                                                            wire:confirm="Вы уверены, что хотите удалить эту операцию?" 
                                                            variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                                    <flux:icon name="trash" variant="outline" />
                                                </flux:button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Планшетная версия таблицы -->
                    <div class="hidden md:block lg:hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <div class="grid grid-cols-5 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div>Операция</div>
                                <div>Тип</div>
                                <div>Пользователь</div>
                                <div class="text-right">Сумма</div>
                                <div class="text-center">Действия</div>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($operationsList as $operation)
                            <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="grid grid-cols-5 gap-4 items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $operation->operation_number }}</div>
                                        <div class="text-xs text-gray-500">{{ \App\Helpers\Settings::formatDate($operation->created_at) }}</div>
                                    </div>
                                    <div>
                                        <span @class([
                                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                            'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                            'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                        ])>
                                            @if($operation->type === 'purchase')
                                                Покупка
                                            @elseif($operation->type === 'sale')
                                                Продажа
                                            @elseif($operation->type === 'conversion')
                                                Конвертация
                                            @else
                                                {{ ucfirst($operation->type) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-900">{{ $operation->user->name }}</div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">{{ \App\Helpers\Settings::formatPrice($operation->total_amount) }}</div>
                                        <button wire:click="showOperationDetails({{ $operation->id }})" 
                                                class="text-xs text-blue-600 hover:text-blue-800">
                                            @if($operation->type === 'conversion')
                                                1 элемент
                                            @else
                                                {{ $operation->items->count() }} поз.
                                            @endif
                                        </button>
                                    </div>
                                    <div class="text-center">
                                        @if($operation->type === 'conversion')
                                            <span class="text-xs text-gray-500">—</span>
                                        @else
                                            <div class="flex items-center justify-center space-x-1">
                                                <flux:button size="sm" wire:click="showOperationDetails({{ $operation->id }})" variant="ghost" class="text-gray-400 hover:text-gray-600" title="Просмотр">
                                                    <flux:icon name="eye" variant="outline" />
                                                </flux:button>
                                                <flux:button size="sm" wire:click="edit({{ $operation->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                                    <flux:icon name="pencil" variant="outline" />
                                                </flux:button>
                                                <flux:button size="sm" wire:click="delete({{ $operation->id }})" 
                                                            wire:confirm="Вы уверены, что хотите удалить эту операцию?" 
                                                            variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                                    <flux:icon name="trash" variant="outline" />
                                                </flux:button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Мобильная версия (карточки) -->
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($operationsList as $operation)
                            <div class="p-4 space-y-3">
                                <!-- Заголовок карточки -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $operation->operation_number }}</div>
                                        <div class="text-xs text-gray-500">{{ \App\Helpers\Settings::formatDateTime($operation->created_at) }}</div>
                                    </div>
                                    <span @class([
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                        'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                        'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                    ])>
                                        @if($operation->type === 'purchase')
                                            Покупка
                                        @elseif($operation->type === 'sale')
                                            Продажа
                                        @elseif($operation->type === 'conversion')
                                            Конвертация
                                        @else
                                            {{ ucfirst($operation->type) }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Детали операции -->
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Пользователь:</span>
                                        <div class="font-medium text-gray-900">{{ $operation->user->name }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Позиций:</span>
                                        <button wire:click="showOperationDetails({{ $operation->id }})" class="font-medium text-blue-600 hover:text-blue-800">
                                            @if($operation->type === 'conversion')
                                                1
                                            @else
                                                {{ $operation->items->count() }}
                                            @endif
                                        </button>
                                    </div>
                                </div>

                                <!-- Сумма и действия -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ \App\Helpers\Settings::formatPrice($operation->total_amount) }}
                                    </div>
                                    @if($operation->type !== 'conversion')
                                        <div class="flex items-center space-x-2">
                                            <flux:button size="sm" wire:click="showOperationDetails({{ $operation->id }})" variant="ghost" class="text-gray-400 hover:text-gray-600" title="Просмотр">
                                                <flux:icon name="eye" variant="outline" />
                                            </flux:button>
                                            <flux:button size="sm" wire:click="edit({{ $operation->id }})" variant="ghost" class="text-blue-400 hover:text-blue-600" title="Редактировать">
                                                <flux:icon name="pencil" variant="outline" />
                                            </flux:button>
                                            <flux:button size="sm" wire:click="delete({{ $operation->id }})" 
                                                        wire:confirm="Вы уверены, что хотите удалить эту операцию?" 
                                                        variant="ghost" class="text-red-400 hover:text-red-600" title="Удалить">
                                                <flux:icon name="trash" variant="outline" />
                                            </flux:button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500 italic">Управляется в конвертациях</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    {{ $operationsList->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Operation Details Modal -->
    @if($showOperationDetailsModal && $selectedOperation)
    <div class="fixed z-30 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button wire:click="closeOperationDetailsModal" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Детали операции {{ $selectedOperation->operation_number }}
                    </h3>
                    
                    <!-- Operation Info -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Дата</label>
                                <p class="text-sm text-gray-900">{{ \App\Helpers\Settings::formatDateTime($selectedOperation->created_at) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Тип</label>
                                <p class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $selectedOperation->type === 'purchase' ? 'bg-green-100 text-green-800' : 
                                           ($selectedOperation->type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                        @if($selectedOperation->type === 'purchase')
                                            Покупка
                                        @elseif($selectedOperation->type === 'sale')
                                            Продажа
                                        @elseif($selectedOperation->type === 'conversion')
                                            Конвертация
                                        @else
                                            {{ ucfirst($selectedOperation->type) }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Пользователь</label>
                                <p class="text-sm text-gray-900">{{ $selectedOperation->user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Общая сумма</label>
                                <p class="text-sm font-semibold text-gray-900">{{ \App\Facades\Settings::formatPrice($selectedOperation->total_amount) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Details -->
                    @if($selectedOperation->type === 'conversion' && $selectedOperation->conversion)
                    <div class="mb-6 bg-amber-50 p-4 rounded-lg border border-amber-200">
                        <h4 class="text-md font-medium text-amber-900 mb-4">Детали конвертации</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Исходный продукт -->
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-semibold text-gray-900 mb-2">Исходный продукт</h5>
                                <div class="space-y-2">
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Название:</span> 
                                        {{ $selectedOperation->conversion->sourceProduct->name }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Количество:</span> 
                                        {{ number_format($selectedOperation->conversion->source_quantity, 3) }} {{ $selectedOperation->conversion->sourceProduct->unit->name ?? 'кг' }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Тип:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $selectedOperation->conversion->sourceProduct->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $selectedOperation->conversion->sourceProduct->type === 'simple' ? 'Простой' : 'Составной' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Целевой продукт -->
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-semibold text-gray-900 mb-2">Целевой продукт</h5>
                                <div class="space-y-2">
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Название:</span> 
                                        {{ $selectedOperation->conversion->targetProduct->name }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Количество:</span> 
                                        {{ number_format($selectedOperation->conversion->target_quantity, 3) }} {{ $selectedOperation->conversion->targetProduct->unit->name ?? 'кг' }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Тип:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $selectedOperation->conversion->targetProduct->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $selectedOperation->conversion->targetProduct->type === 'simple' ? 'Простой' : 'Составной' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Дополнительные элементы (если есть) -->
                        @if($selectedOperation->conversion->elements->count() > 0)
                        <div class="mt-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Добавленные элементы в запасы</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($selectedOperation->conversion->elements as $conversionElement)
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-900">{{ $conversionElement->element->name }}</span>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ number_format($conversionElement->quantity, 3) }} {{ $conversionElement->element->unit->name ?? 'кг' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Заметки -->
                        @if($selectedOperation->conversion->notes)
                        <div class="mt-4">
                            <h5 class="font-semibold text-gray-900 mb-2">Заметки</h5>
                            <p class="text-sm text-gray-700 bg-white p-3 rounded border">{{ $selectedOperation->conversion->notes }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Items List -->
                    @if($selectedOperation->items->count() > 0)
                        @if($selectedOperation->type !== 'conversion')
                        <h4 class="text-md font-medium text-gray-900 mb-3">Товары в операции</h4>
                        @else
                        <h4 class="text-md font-medium text-gray-900 mb-3">Связанные товары</h4>
                        @endif
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($selectedOperation->items as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-semibold text-gray-900">{{ $item->product->name }}</h5>
                                    <p class="text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $item->product->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $item->product->type === 'simple' ? 'Простой' : 'Составной' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-lg">{{ \App\Helpers\Settings::formatPrice($item->price) }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <label class="text-xs text-gray-500">Общий вес</label>
                                    <p class="text-sm text-gray-900">{{ number_format($item->weight, 2) }} {{ $item->product->unit->short_name }}</p>
                                </div>
                                @if($item->product->type === 'simple')
                                    @php
                                        $clogging = $item->clogging ?? 0;
                                        $effectiveWeight = $item->weight - ($item->weight * $clogging / 100);
                                    @endphp
                                    <div>
                                        <label class="text-xs text-gray-500">Засор</label>
                                        <p class="text-sm text-gray-900">{{ $clogging }}%</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Чистый вес</label>
                                        <p class="text-sm font-semibold text-green-600">{{ number_format($effectiveWeight, 2) }} {{ $item->product->unit->short_name }}</p>
                                    </div>
                                @else
                                    <div></div>
                                    <div></div>
                                @endif
                                <div>
                                    <label class="text-xs text-gray-500">Цена за единицу</label>
                                    @php
                                        // Для простых продуктов рассчитываем цену за единицу на основе чистого веса
                                        if ($item->product->type === 'simple') {
                                            $clogging = $item->clogging ?? 0;
                                            $effectiveWeight = $item->weight - ($item->weight * $clogging / 100);
                                            $pricePerUnit = $effectiveWeight > 0 ? $item->price / $effectiveWeight : 0;
                                        } else {
                                            $pricePerUnit = $item->weight > 0 ? $item->price / $item->weight : 0;
                                        }
                                    @endphp
                                    <p class="text-sm text-gray-900">{{ \App\Helpers\Settings::formatPrice($pricePerUnit) }}/{{ $item->product->unit->short_name }}</p>
                                </div>
                            </div>

                            @if($item->product->type === 'composite' && $item->elements->count() > 0)
                            <div class="border-t border-gray-100 pt-3">
                                <h6 class="text-sm font-medium text-gray-700 mb-2">Элементы состава:</h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($item->elements as $itemElement)
                                    <div class="bg-gray-50 p-3 rounded">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium">{{ $itemElement->element->name }}</span>
                                            <span class="text-sm text-gray-600">{{ $itemElement->percentage }}%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @php
                                                $elementWeight = $item->weight * ($itemElement->percentage / 100);
                                            @endphp
                                            Вес: {{ number_format($elementWeight, 4) }} {{ $itemElement->element->unit->short_name }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>В данной операции нет связанных товаров</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeOperationDetailsModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
