<div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline"
            @focus-on-weight-input.window="setTimeout(() => document.getElementById('cart-item-weight-' + $event.detail.index)?.focus(), 50)">
            
            <div class="absolute top-0 right-0 pt-4 pr-4 flex gap-2">
                <!-- Кнопка свернуть -->
                <button wire:click="closeModal" type="button" class="rounded-md bg-white text-gray-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" title="Свернуть модальное окно">
                    <span class="sr-only">Minimize</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </button>
                
                <!-- Кнопка закрыть -->
                <button wire:click="smartCloseModal" type="button" class="rounded-md bg-white text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" title="Закрыть операции">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!--[if BLOCK]><![endif]--><?php if($notification): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            <p class="font-bold">Success</p>
                            <p><?php echo e($notification); ?></p>
                        </div>
                    <?php else: ?>
                        <!-- Operation Tabs -->
                        <div class="flex border-b mb-4">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opId => $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative flex items-center <?php echo e($activeOperationId === $opId ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> py-2 px-4 -mb-px border-b-2 font-medium text-sm leading-5 group">
                                    <button type="button" wire:click="switchOperation('<?php echo e($opId); ?>')"
                                        class="flex items-center gap-1 focus:outline-none">
                                        <span><?php echo e(explode('-', $opId)[0] . '-' . substr(explode('-', $opId)[2], -4)); ?></span>
                                        <!--[if BLOCK]><![endif]--><?php if(isset($operation['is_editing']) && $operation['is_editing']): ?>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                EDIT
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </button>
                                    <!--[if BLOCK]><![endif]--><?php if(count($operations) > 1): ?>
                                        <button type="button"
                                                onclick="event.stopPropagation(); if(confirm('Вы уверены, что хотите закрыть эту операцию?')) { window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('removeOperationTab', '<?php echo e($opId); ?>') }"
                                                class="ml-2 text-gray-400 hover:text-red-500 focus:outline-none"
                                                title="Закрыть операцию">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <button type="button" wire:click="addNewOperation"
                                class="py-2 px-4 text-gray-500 hover:text-gray-700 font-medium text-sm leading-5 focus:outline-none">
                                [+]
                            </button>
                        </div>

                        <!--[if BLOCK]><![endif]--><?php if(isset($operations[$activeOperationId])): ?>
                            <div>
                                <!--[if BLOCK]><![endif]--><?php if(isset($operations[$activeOperationId]['is_editing']) && $operations[$activeOperationId]['is_editing']): ?>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="text-yellow-800 font-medium">Режим редактирования операции</span>
                                        </div>
                                        <p class="text-sm text-yellow-700 mt-1">Вы редактируете существующую операцию. Изменения будут сохранены в оригинальной операции.</p>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                                        <select wire:model.live="operations.<?php echo e($activeOperationId); ?>.type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="purchase">Purchase</option>
                                            <option value="sale">Sale</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User:</label>
                                        <select wire:model.live="operations.<?php echo e($activeOperationId); ?>.user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="">Select User</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                    </div>
                                </div>

                                <!-- Информационный блок о расчете засора -->
                                <!-- <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-900">Как работает расчет засора</h3>
                                            <div class="mt-2 text-sm text-blue-800">
                                                <p>• <strong>Вес</strong> - общий вес металла включая засор</p>
                                                <p>• <strong>Засор</strong> - процент примесей в металле</p>
                                                <p>• <strong>Чистый вес</strong> = Общий вес - (Общий вес × Засор%)</p>
                                                <p>• <strong>Итого</strong> = Чистый вес × Цена за единицу</p>
                                                <p class="mt-1 font-medium">На склад поступает только чистый вес металла!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                <div class="mt-4 border-t pt-4">
                    <div class="flex items-end gap-2">
                        <div class="flex-grow">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Add Product:</label>
                            <button wire:click="openProductModal" type="button" class="w-auto bg-orange-500 border rounded-lg py-2 px-3 text-left text-white hover:bg-orange-600 focus:outline-none focus:shadow-outline">
                                Выбрать товар из каталога...
                            </button>
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['product_to_add'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                                <div class="mt-4">
                                    <h4 class="font-bold">Товары в заказе</h4>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['operations.'.$activeOperationId.'.cartItems'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm mb-2 block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $operations[$activeOperationId]['cartItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="p-4 border rounded-lg" wire:key="cart-item-<?php echo e($activeOperationId); ?>-<?php echo e($index); ?>">
                                            <div class="flex justify-between items-start">
                                                <h5 class="font-semibold"><?php echo e($item['name']); ?></h5>
                                                <button wire:click.prevent="removeCartItem(<?php echo e($index); ?>)" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>

                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="text-sm">Вес (<?php echo e($item['unit']); ?>)</label>
                                                    <input id="cart-item-weight-<?php echo e($index); ?>" onfocus="this.select()" type="number" step="0.01" wire:model.live.debounce.700ms="operations.<?php echo e($activeOperationId); ?>.cartItems.<?php echo e($index); ?>.weight" class="shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                </div>
                                                <div>
                                                    <label class="text-sm flex items-center justify-between">
                                                        <span>Цена за ед.изм.</span>
                                                        <!--[if BLOCK]><![endif]--><?php if($item['type'] === 'simple'): ?>
                                                            <!--[if BLOCK]><![endif]--><?php if(isset($item['custom_price_set']) && $item['custom_price_set']): ?>
                                                                <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">Ручная</span>
                                                            <?php else: ?>
                                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Авто</span>
                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </label>
                                                    <div class="flex">
                                                        <input type="number" step="0.01" 
                                                               wire:model.live.debounce.1000ms="operations.<?php echo e($activeOperationId); ?>.cartItems.<?php echo e($index); ?>.price_per_unit" 
                                                               class="shadow-sm appearance-none border rounded-l w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php if(isset($item['custom_price_set']) && $item['custom_price_set']): ?> bg-orange-50 border-orange-300 <?php endif; ?>" 
                                                               <?php if($item['type'] === 'composite'): ?> disabled <?php endif; ?>
                                                               onfocus="this.select(); window.Livewire.find('<?php echo e($_instance->getId()); ?>').startEditingPrice(<?php echo e($index); ?>)"
                                                               onblur="window.Livewire.find('<?php echo e($_instance->getId()); ?>').stopEditingPrice(<?php echo e($index); ?>)"
                                                               placeholder="Введите цену">
                                                        <!--[if BLOCK]><![endif]--><?php if($item['type'] === 'simple' && isset($item['custom_price_set']) && $item['custom_price_set']): ?>
                                                            <button type="button" 
                                                                    wire:click="resetPriceToAuto(<?php echo e($index); ?>)"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-r border border-l-0 border-blue-500 text-xs"
                                                                    title="Сбросить к автоматической цене">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                                </svg>
                                                            </button>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                    <!--[if BLOCK]><![endif]--><?php if($item['type'] === 'simple'): ?>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <!--[if BLOCK]><![endif]--><?php if(isset($item['custom_price_set']) && $item['custom_price_set']): ?>
                                                                <span class="text-orange-600">💡 Ручная цена. Не будет меняться при изменении веса.</span>
                                                            <?php else: ?>
                                                                <span class="text-green-600">🔄 Автоматическая цена. Обновляется при изменении веса.</span>
                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                                <div>
                                                    <label class="text-sm">Засор (%)</label>
                                                    <input type="number" step="0.01" wire:model.live.debounce.700ms="operations.<?php echo e($activeOperationId); ?>.cartItems.<?php echo e($index); ?>.clogging" class="shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" <?php if($item['type'] === 'composite'): ?> disabled <?php endif; ?>>
                                                    <!--[if BLOCK]><![endif]--><?php if($item['type'] === 'simple'): ?>
                                                        <?php
                                                            $weight = (float)($item['weight'] ?? 0);
                                                            $clogging = (float)($item['clogging'] ?? 0);
                                                            $effectiveWeight = $weight - ($weight * $clogging / 100);
                                                        ?>
                                                        <div class="text-xs text-gray-600 mt-1">
                                                            Чистый вес: <strong class="text-green-600"><?php echo e(number_format($effectiveWeight, 3)); ?> <?php echo e($item['unit']); ?></strong>
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                                <div class="text-right">
                                                    <label class="text-sm">Total Price</label>
                                                    <p class="font-semibold"><?php echo e(number_format($item['price'], 2)); ?></p>
                                                </div>
                                            </div>
                                            
                                            <!--[if BLOCK]><![endif]--><?php if($item['type'] === 'composite' && !empty($item['elements'])): ?>
                                            <div class="mt-4 border-t pt-2">
                                                <p class="text-sm font-semibold">Элементы состава:</p>
                                                <div class="mt-2 space-y-2">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item['elements']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $el_index => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="grid grid-cols-4 gap-2 items-center p-2 bg-gray-50 rounded" wire:key="element-<?php echo e($activeOperationId); ?>-<?php echo e($index); ?>-<?php echo e($el_index); ?>">
                                                        <label class="text-sm flex-1 col-span-1 font-medium"><?php echo e($element['name']); ?></label>
                                                        <div class="col-span-1 text-xs text-gray-600">
                                                            <?php echo e(\App\Helpers\Settings::formatPrice($element['price'] ?? 0)); ?>/1%
                                                        </div>
                                                        <input type="number" step="0.0001" placeholder="%" wire:model.live.debounce.700ms="operations.<?php echo e($activeOperationId); ?>.cartItems.<?php echo e($index); ?>.elements.<?php echo e($el_index); ?>.percentage" class="col-span-1 shadow-sm appearance-none border rounded w-full py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        <div class="col-span-1 text-xs text-gray-600 font-medium">
                                                            = <?php echo e(\App\Helpers\Settings::formatPrice(($element['price'] ?? 0) * (float)($element['percentage'] ?? 0))); ?>/кг
                                                        </div>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                                
                                                <?php
                                                    $totalPercentage = collect($item['elements'])->sum(function($el) {
                                                        return (float)($el['percentage'] ?? 0);
                                                    });
                                                    $pricePerKg = collect($item['elements'])->sum(function($el) {
                                                        return ($el['price'] ?? 0) * (float)($el['percentage'] ?? 0);
                                                    });
                                                ?>
                                                
                                                <div class="mt-3 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm font-medium">Общий процент:</span>
                                                        <span class="text-sm font-bold <?php echo e($totalPercentage > 100 ? 'text-red-600' : 'text-green-600'); ?>">
                                                            <?php echo e(number_format($totalPercentage, 2)); ?>%
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between items-center mt-1">
                                                        <span class="text-sm font-medium">Стоимость за 1 кг:</span>
                                                        <span class="text-sm font-bold text-blue-600"><?php echo e(\App\Helpers\Settings::formatPrice($pricePerKg)); ?></span>
                                                    </div>
                                                    <div class="flex justify-between items-center mt-1">
                                                        <span class="text-sm font-medium">Общая стоимость (<?php echo e($item['weight']); ?> кг):</span>
                                                        <span class="text-sm font-bold text-green-600"><?php echo e(\App\Helpers\Settings::formatPrice($pricePerKg * (float)($item['weight'] ?? 0))); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-gray-500">The cart is empty.</p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                
                                <!-- Комментарий -->
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('comments.create')): ?>
                                <div class="mt-4 border-t pt-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Комментарий:</label>
                                    <textarea wire:model="operationComment" 
                                              rows="3" 
                                              placeholder="Добавьте комментарий к операции..." 
                                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mt-4 text-right">
                                    <h4 class="text-lg font-bold">Total Amount: <?php echo e(\App\Helpers\Settings::formatPrice($operations[$activeOperationId]['totalAmount'] ?? 0)); ?></h4>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No active operation. Please create one.</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-between">
                    <!--[if BLOCK]><![endif]--><?php if($notification): ?>
                         <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                            <button wire:click.prevent="startNewOperation()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                <!--[if BLOCK]><![endif]--><?php if(!empty($operations)): ?>
                                    Продолжить работу
                                <?php else: ?>
                                    Перейти к операциям
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                        </span>
                    <?php else: ?>
                        <div class="flex flex-col md:flex-row gap-2">
                            <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                                <button wire:click.prevent="store()" type="button" <?php if(!isset($operations[$activeOperationId]) || empty($operations[$activeOperationId]['cartItems'])): ?> disabled <?php endif; ?>
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Save
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                                <button wire:click="removeOperation('<?php echo e($this->activeOperationId); ?>')" wire:confirm="Are you sure you want to delete this operation tab?" type="button"
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
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </form>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showProductModal): ?>
    <div class="fixed z-60 inset-0 overflow-y-auto ease-out duration-400">
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
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div wire:click="selectProductFromCard(<?php echo e($product->id); ?>)" 
                             class="border border-gray-200 rounded-lg p-3 hover:border-blue-500 hover:shadow-md cursor-pointer transition-all duration-200">
                            
                            <!-- Product Image -->
                            <div class="h-16 bg-gray-100 rounded-md mb-2 flex items-center justify-center overflow-hidden">
                                <!--[if BLOCK]><![endif]--><?php if($product->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                                         alt="<?php echo e($product->name); ?>" 
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Product Info -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1 text-sm"><?php echo e($product->name); ?></h4>
                                <p class="text-xs text-gray-600 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        <?php echo e($product->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                        <?php echo e($product->type === 'simple' ? 'Простой' : 'Составной'); ?>

                                    </span>
                                </p>
                                
                                <div class="text-xs text-gray-600 space-y-0.5">
                                    <!--[if BLOCK]><![endif]--><?php if($operations[$activeOperationId]['type'] === 'purchase'): ?>
                                        <p><span class="font-medium">Цена:</span> <?php echo e(\App\Helpers\Settings::formatPrice($product->purchase_price ?? 0)); ?></p>
                                    <?php else: ?>
                                        <p><span class="font-medium">Цена:</span> <?php echo e(\App\Helpers\Settings::formatPrice($product->selling_price ?? 0)); ?></p>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <p><span class="font-medium">Склад:</span> <?php echo e(number_format($product->stock, 2)); ?> <?php echo e($product->unit->short_name); ?></p>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($product->clogging): ?>
                                        <p><span class="font-medium">Засор:</span> <?php echo e($product->clogging); ?>%</p>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!--[if BLOCK]><![endif]--><?php if($product->type === 'composite' && $product->elements->count() > 0): ?>
                                    <div class="mt-1 pt-1 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 mb-0.5">Состав:</p>
                                        <div class="flex flex-wrap gap-0.5">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $product->elements->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="inline-flex items-center px-1 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                                    <?php echo e($element->name); ?> <?php echo e($element->pivot->percentage); ?>%
                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <!--[if BLOCK]><![endif]--><?php if($product->elements->count() > 2): ?>
                                                <span class="text-xs text-gray-500">+<?php echo e($product->elements->count() - 2); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <!--[if BLOCK]><![endif]--><?php if($productSearch): ?>
                                Товары не найдены по запросу "<?php echo e($productSearch); ?>"
                            <?php else: ?>
                                Нет доступных товаров
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/operations/create.blade.php ENDPATH**/ ?>