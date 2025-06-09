<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline"
            @focus-on-weight-input.window="setTimeout(() => document.getElementById('cart-item-weight-' + $event.detail.index)?.focus(), 50)">
            
            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
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
                                <button type="button" wire:click="switchOperation('{{ $opId }}')"
                                    class="py-2 px-4 -mb-px border-b-2 font-medium text-sm leading-5 focus:outline-none 
                                    {{ $activeOperationId === $opId ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    {{ explode('-', $opId)[0] . '-' . substr(explode('-', $opId)[2], -4) }}
                                </button>
                            @endforeach
                            <button type="button" wire:click="addNewOperation"
                                class="py-2 px-4 text-gray-500 hover:text-gray-700 font-medium text-sm leading-5 focus:outline-none">
                                [+]
                            </button>
                        </div>

                        @if(isset($operations[$activeOperationId]))
                            <div>
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

                                <div class="mt-4 border-t pt-4" x-data="{ product_id: @entangle('product_to_add').live }">
                                    <div class="flex items-end gap-2">
                                        <div class="flex-grow">
                                            <label for="product_to_add" class="block text-gray-700 text-sm font-bold mb-2">Add Product:</label>
                                            <select x-model="product_id" id="product_to_add" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button @click.prevent="$wire.addProductToCart(product_id)" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add</button>
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
                                                <p class="text-sm font-semibold">Elements:</p>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                                                    @foreach($item['elements'] as $el_index => $element)
                                                    <div class="grid grid-cols-3 gap-2 items-center" wire:key="element-{{ $activeOperationId }}-{{ $index }}-{{ $el_index }}">
                                                        <label class="text-sm flex-1 col-span-1">{{ $element['name'] }}</label>
                                                        <input type="number" step="0.01" placeholder="price/{{$element['unit']}}" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.elements.{{ $el_index }}.price" class="col-span-1 shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        <input type="number" step="0.0001" placeholder="%" wire:model.live.debounce.300ms="operations.{{ $activeOperationId }}.cartItems.{{ $index }}.elements.{{ $el_index }}.percentage" class="col-span-1 shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                    </div>
                                                    @endforeach
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
                                Start New Operation
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
                        <div>
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
</div> 