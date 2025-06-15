<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline"
            @focus-on-weight-input.window="setTimeout(() => document.getElementById('cart-item-weight-' + $event.detail.index)?.focus(), 50)">
            
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button wire:click="closeModal()" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    @if ($notification)
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{ $notification }}</p>
                        </div>
                    @else
                        <!-- Operation Tabs -->
                        <div class="flex border-b mb-4">
                            @foreach($operations as $opId => $operation)
                                <div class="relative flex items-center {{ $activeOperationId === $opId ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-2 px-4 -mb-px border-b-2 font-medium text-sm leading-5 group">
                                    <button type="button" wire:click="switchOperation('{{ $opId }}')"
                                        class="flex items-center gap-1 focus:outline-none">
                                        <span>{{ explode('-', $opId)[0] . '-' . substr(explode('-', $opId)[2], -4) }}</span>
                                        @if(isset($operation['is_editing']) && $operation['is_editing'])
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                EDIT
                                            </span>
                                        @endif
                                    </button>
                                    @if(count($operations) > 1)
                                        <button type="button"
                                                onclick="event.stopPropagation(); if(confirm('Вы уверены, что хотите закрыть эту операцию?')) { @this.call('removeOperationTab', '{{ $opId }}') }"
                                                class="ml-2 text-gray-400 hover:text-red-500 focus:outline-none"
                                                title="Закрыть операцию">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            <button type="button" wire:click="addNewOperation"
                                class="py-2 px-4 text-gray-500 hover:text-gray-700 font-medium text-sm leading-5 focus:outline-none">
                                [+]
                            </button>
                        </div>

                        @if(isset($operations[$activeOperationId]))
                            <div>
                                @if(isset($operations[$activeOperationId]['is_editing']) && $operations[$activeOperationId]['is_editing'])
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="text-yellow-800 font-medium">Режим редактирования операции</span>
                                        </div>
                                        <p class="text-sm text-yellow-700 mt-1">Вы редактируете существующую операцию. Изменения будут сохранены в оригинальной операции.</p>
                                    </div>
                                @endif
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                                        <select wire:model.live="operations.{{ $activeOperationId }}.type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="purchase">Purchase</option>
                                            <option value="sale">Sale</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User:</label>
                                        <select wire:model.live="operations.{{ $activeOperationId }}.user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                                <div class="mt-4 border-t pt-4">
                    <div class="flex items-end gap-2">
                        <div class="flex-grow">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Add Product:</label>
                            <button wire:click="openProductModal" type="button" class="w-full bg-white border border-gray-300 rounded py-2 px-3 text-left text-gray-700 hover:bg-gray-50 focus:outline-none focus:shadow-outline">
                                Выбрать товар из каталога...
                            </button>
                        </div>
                    </div>
                    @error('product_to_add') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                                <div class="mt-4">
                                    <h4 class="font-bold">Cart Items</h4>
                                    @error('operations.'.$activeOperationId.'.cartItems') <span class="text-red-500 text-sm mb-2 block">{{ $message }}</span>@enderror

                                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                        @forelse($operations[$activeOperationId]['cartItems'] as $index => $item)
                                        <div class="p-4 border rounded-lg" wire:key="cart-item-{{ $activeOperationId }}-{{ $index }}">
                                            <div class="flex justify-between items-start">
                                                <h5 class="font-semibold">{{ $item['name'] }}</h5>
                                                <button wire:click.prevent="removeCartItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>

                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="text-sm">Weight ({{ $item['unit'] }})</label>
                                                    <input id="cart-item-weight-{{ $index }}" onfocus="this.select()" type="number" step="0.01" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.weight" class="shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                </div>
                                                <div>
                                                    <label class="text-sm">Price per unit</label>
                                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.price_per_unit" class="shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @if($item['type'] === 'composite') disabled @endif>
                                                </div>
                                                <div>
                                                    <label class="text-sm">Clogging (%)</label>
                                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.clogging" class="shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @if($item['type'] === 'composite') disabled @endif>
                                                </div>
                                                <div class="text-right">
                                                    <label class="text-sm">Total Price</label>
                                                    <p class="font-semibold">{{ number_format($item['price'], 2) }}</p>
                                                </div>
                                            </div>
                                            
                                            @if($item['type'] === 'composite' && !empty($item['elements']))
                                            <div class="mt-4 border-t pt-2">
                                                <p class="text-sm font-semibold">Элементы состава:</p>
                                                <div class="mt-2 space-y-2">
                                                    @foreach($item['elements'] as $el_index => $element)
                                                    <div class="grid grid-cols-4 gap-2 items-center p-2 bg-gray-50 rounded" wire:key="element-{{ $activeOperationId }}-{{ $index }}-{{ $el_index }}">
                                                        <label class="text-sm flex-1 col-span-1 font-medium">{{ $element['name'] }}</label>
                                                        <div class="col-span-1 text-xs text-gray-600">
                                                            {{ $element['price'] }} грн/1%
                                                        </div>
                                                        <input type="number" step="0.0001" placeholder="%" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.elements.{{ $el_index }}.percentage" class="col-span-1 shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        <div class="col-span-1 text-xs text-gray-600 font-medium">
                                                            = {{ number_format(($element['price'] ?? 0) * ($element['percentage'] ?? 0), 2) }} грн/кг
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                
                                                @php
                                                    $totalPercentage = collect($item['elements'])->sum('percentage');
                                                    $pricePerKg = collect($item['elements'])->sum(function($el) {
                                                        return ($el['price'] ?? 0) * ($el['percentage'] ?? 0);
                                                    });
                                                @endphp
                                                
                                                <div class="mt-3 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm font-medium">Общий процент:</span>
                                                        <span class="text-sm font-bold {{ $totalPercentage > 100 ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ number_format($totalPercentage, 2) }}%
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between items-center mt-1">
                                                        <span class="text-sm font-medium">Стоимость за 1 кг:</span>
                                                        <span class="text-sm font-bold text-blue-600">{{ number_format($pricePerKg, 2) }} грн</span>
                                                    </div>
                                                    <div class="flex justify-between items-center mt-1">
                                                        <span class="text-sm font-medium">Общая стоимость ({{ $item['weight'] }} кг):</span>
                                                        <span class="text-sm font-bold text-green-600">{{ number_format($pricePerKg * ($item['weight'] ?? 0), 2) }} грн</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @empty
                                        <p class="text-gray-500">The cart is empty.</p>
                                        @endforelse
                                    </div>
                                </div>
                                
                                <div class="mt-4 border-t pt-4 text-right">
                                    <h4 class="text-lg font-bold">Total Amount: {{ number_format($operations[$activeOperationId]['totalAmount'] ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">No active operation. Please create one.</p>
                        @endif
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-between">
                    @if($notification)
                         <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                            <button wire:click.prevent="startNewOperation()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                @if(!empty($operations))
                                    Продолжить работу
                                @else
                                    Перейти к операциям
                                @endif
                            </button>
                        </span>
                    @else
                        <div class="flex flex-col md:flex-row gap-2">
                            <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                                <button wire:click.prevent="store()" type="button" @if(!isset($operations[$activeOperationId]) || empty($operations[$activeOperationId]['cartItems'])) disabled @endif
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Save
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                                <button wire:click="removeOperation('{{ $this->activeOperationId }}')" wire:confirm="Are you sure you want to delete this operation tab?" type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Delete Operation
                                </button>
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button wire:click="closeCurrentOperation" wire:confirm="Вы уверены, что хотите закрыть текущую операцию?" type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-100 text-base leading-6 font-medium text-gray-700 shadow-sm hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Закрыть операцию
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button wire:click="clearAllOperations" wire:confirm="Are you sure you want to delete ALL operation tabs?" type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Clear All
                                </button>
                            </span>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Product Selection Modal -->
    @if($showProductModal)
    <div class="fixed z-20 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button wire:click="closeProductModal" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Выберите товар
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <input wire:model.live.debounce.300ms="productSearch" 
                               type="text" 
                               placeholder="Поиск товаров..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 max-h-96 overflow-y-auto">
                        @forelse($products as $product)
                        <div wire:click="selectProductFromCard({{ $product->id }})" 
                             class="border border-gray-200 rounded-lg p-3 hover:border-blue-500 hover:shadow-md cursor-pointer transition-all duration-200">
                            
                            <!-- Product Image -->
                            <div class="h-16 bg-gray-100 rounded-md mb-2 flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1 text-sm">{{ $product->name }}</h4>
                                <p class="text-xs text-gray-600 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $product->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $product->type === 'simple' ? 'Простой' : 'Составной' }}
                                    </span>
                                </p>
                                
                                <div class="text-xs text-gray-600 space-y-0.5">
                                    @if($operations[$activeOperationId]['type'] === 'purchase')
                                        <p><span class="font-medium">Цена:</span> {{ number_format($product->purchase_price, 2) }}</p>
                                    @else
                                        <p><span class="font-medium">Цена:</span> {{ number_format($product->selling_price, 2) }}</p>
                                    @endif
                                    
                                    <p><span class="font-medium">Склад:</span> {{ number_format($product->stock, 2) }} {{ $product->unit->short_name }}</p>
                                    
                                    @if($product->clogging)
                                        <p><span class="font-medium">Засор:</span> {{ $product->clogging }}%</p>
                                    @endif
                                </div>

                                @if($product->type === 'composite' && $product->elements->count() > 0)
                                    <div class="mt-1 pt-1 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-0.5">Состав:</p>
                                        <div class="flex flex-wrap gap-0.5">
                                            @foreach($product->elements->take(2) as $element)
                                                <span class="inline-flex items-center px-1 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                                    {{ $element->name }} {{ $element->pivot->percentage }}%
                                                </span>
                                            @endforeach
                                            @if($product->elements->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $product->elements->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            @if($productSearch)
                                Товары не найдены по запросу "{{ $productSearch }}"
                            @else
                                Нет доступных товаров
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeProductModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 