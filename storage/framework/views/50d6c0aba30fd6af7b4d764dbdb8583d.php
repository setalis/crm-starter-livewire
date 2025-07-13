<div>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Operations')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                
                <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo e(session('message')); ?></span>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if(empty($operations)): ?>
                    <button wire:click="addNewOperation('purchase')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Новая операция
                    </button>
                <?php else: ?>
                    <button wire:click="showOperationsCart" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded my-3">
                        Continue with <?php echo e(count($operations)); ?> open operation(s)
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($isModal): ?>
                    <?php echo $__env->make('livewire.admin.operations.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $operationsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e(\App\Helpers\Settings::formatDateTime($operation->created_at)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900"><?php echo e($operation->operation_number); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                            'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                            'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                        ]); ?>">
                                            <!--[if BLOCK]><![endif]--><?php if($operation->type === 'purchase'): ?>
                                                Покупка
                                            <?php elseif($operation->type === 'sale'): ?>
                                                Продажа
                                            <?php elseif($operation->type === 'conversion'): ?>
                                                Конвертация
                                            <?php else: ?>
                                                <?php echo e(ucfirst($operation->type)); ?>

                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($operation->user->name); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button wire:click="showOperationDetails(<?php echo e($operation->id); ?>)" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-150">
                                            <!--[if BLOCK]><![endif]--><?php if($operation->type === 'conversion'): ?>
                                                1
                                            <?php else: ?>
                                                <?php echo e($operation->items->count()); ?>

                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        <?php echo e(\App\Helpers\Settings::formatPrice($operation->total_amount)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <!--[if BLOCK]><![endif]--><?php if($operation->type === 'conversion'): ?>
                                            <span class="text-xs text-gray-500 italic">Управляется в конвертациях</span>
                                        <?php else: ?>
                                            <div class="flex items-center justify-center space-x-1">
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'eye','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'pencil','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'trash','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $operationsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="grid grid-cols-5 gap-4 items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($operation->operation_number); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e(\App\Helpers\Settings::formatDate($operation->created_at)); ?></div>
                                    </div>
                                    <div>
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                            'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                            'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                        ]); ?>">
                                            <!--[if BLOCK]><![endif]--><?php if($operation->type === 'purchase'): ?>
                                                Покупка
                                            <?php elseif($operation->type === 'sale'): ?>
                                                Продажа
                                            <?php elseif($operation->type === 'conversion'): ?>
                                                Конвертация
                                            <?php else: ?>
                                                <?php echo e(ucfirst($operation->type)); ?>

                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-900"><?php echo e($operation->user->name); ?></div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e(\App\Helpers\Settings::formatPrice($operation->total_amount)); ?></div>
                                        <button wire:click="showOperationDetails(<?php echo e($operation->id); ?>)" 
                                                class="text-xs text-blue-600 hover:text-blue-800">
                                            <!--[if BLOCK]><![endif]--><?php if($operation->type === 'conversion'): ?>
                                                1 элемент
                                            <?php else: ?>
                                                <?php echo e($operation->items->count()); ?> поз.
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </button>
                                    </div>
                                    <div class="text-center">
                                        <!--[if BLOCK]><![endif]--><?php if($operation->type === 'conversion'): ?>
                                            <span class="text-xs text-gray-500">—</span>
                                        <?php else: ?>
                                            <div class="flex items-center justify-center space-x-1">
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'eye','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'pencil','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']); ?>
                                                    <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'trash','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <!-- Мобильная версия (карточки) -->
                    <div class="md:hidden">
                        <div class="divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $operationsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 space-y-3">
                                <!-- Заголовок карточки -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($operation->operation_number); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e(\App\Helpers\Settings::formatDateTime($operation->created_at)); ?></div>
                                    </div>
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $operation->type === 'purchase',
                                        'bg-blue-100 text-blue-800' => $operation->type === 'sale',
                                        'bg-yellow-100 text-yellow-800' => $operation->type === 'conversion',
                                    ]); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($operation->type === 'purchase'): ?>
                                            Покупка
                                        <?php elseif($operation->type === 'sale'): ?>
                                            Продажа
                                        <?php elseif($operation->type === 'conversion'): ?>
                                            Конвертация
                                        <?php else: ?>
                                            <?php echo e(ucfirst($operation->type)); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                </div>

                                <!-- Детали операции -->
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Пользователь:</span>
                                        <div class="font-medium text-gray-900"><?php echo e($operation->user->name); ?></div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Позиций:</span>
                                        <button wire:click="showOperationDetails(<?php echo e($operation->id); ?>)" class="font-medium text-blue-600 hover:text-blue-800">
                                            <!--[if BLOCK]><![endif]--><?php if($operation->type === 'conversion'): ?>
                                                1
                                            <?php else: ?>
                                                <?php echo e($operation->items->count()); ?>

                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </button>
                                    </div>
                                </div>

                                <!-- Сумма и действия -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?php echo e(\App\Helpers\Settings::formatPrice($operation->total_amount)); ?>

                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if($operation->type !== 'conversion'): ?>
                                        <div class="flex items-center space-x-2">
                                            <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'showOperationDetails('.e($operation->id).')','variant' => 'ghost','class' => 'text-gray-400 hover:text-gray-600','title' => 'Просмотр']); ?>
                                                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'eye','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'edit('.e($operation->id).')','variant' => 'ghost','class' => 'text-blue-400 hover:text-blue-600','title' => 'Редактировать']); ?>
                                                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'pencil','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'delete('.e($operation->id).')','wire:confirm' => 'Вы уверены, что хотите удалить эту операцию?','variant' => 'ghost','class' => 'text-red-400 hover:text-red-600','title' => 'Удалить']); ?>
                                                <?php if (isset($component)) { $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.index','data' => ['name' => 'trash','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash','variant' => 'outline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $attributes = $__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__attributesOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2)): ?>
<?php $component = $__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2; ?>
<?php unset($__componentOriginalc7d5f44bf2a2d803ed0b55f72f1f82e2); ?>
<?php endif; ?>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-500 italic">Управляется в конвертациях</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <?php echo e($operationsList->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Operation Details Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showOperationDetailsModal && $selectedOperation): ?>
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
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
                        Детали операции <?php echo e($selectedOperation->operation_number); ?>

                    </h3>
                    
                    <!-- Operation Info -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Дата</label>
                                <p class="text-sm text-gray-900"><?php echo e(\App\Helpers\Settings::formatDateTime($selectedOperation->created_at)); ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Тип</label>
                                <p class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        <?php echo e($selectedOperation->type === 'purchase' ? 'bg-green-100 text-green-800' : 
                                           ($selectedOperation->type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($selectedOperation->type === 'purchase'): ?>
                                            Покупка
                                        <?php elseif($selectedOperation->type === 'sale'): ?>
                                            Продажа
                                        <?php elseif($selectedOperation->type === 'conversion'): ?>
                                            Конвертация
                                        <?php else: ?>
                                            <?php echo e(ucfirst($selectedOperation->type)); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Пользователь</label>
                                <p class="text-sm text-gray-900"><?php echo e($selectedOperation->user->name); ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Общая сумма</label>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e(\App\Facades\Settings::formatPrice($selectedOperation->total_amount)); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Details -->
                    <!--[if BLOCK]><![endif]--><?php if($selectedOperation->type === 'conversion' && $selectedOperation->conversion): ?>
                    <div class="mb-6 bg-amber-50 p-4 rounded-lg border border-amber-200">
                        <h4 class="text-md font-medium text-amber-900 mb-4">Детали конвертации</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Исходный продукт -->
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-semibold text-gray-900 mb-2">Исходный продукт</h5>
                                <div class="space-y-2">
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Название:</span> 
                                        <?php echo e($selectedOperation->conversion->sourceProduct->name); ?>

                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Количество:</span> 
                                        <?php echo e(number_format($selectedOperation->conversion->source_quantity, 3)); ?> <?php echo e($selectedOperation->conversion->sourceProduct->unit->name ?? 'кг'); ?>

                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Тип:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?php echo e($selectedOperation->conversion->sourceProduct->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo e($selectedOperation->conversion->sourceProduct->type === 'simple' ? 'Простой' : 'Составной'); ?>

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
                                        <?php echo e($selectedOperation->conversion->targetProduct->name); ?>

                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Количество:</span> 
                                        <?php echo e(number_format($selectedOperation->conversion->target_quantity, 3)); ?> <?php echo e($selectedOperation->conversion->targetProduct->unit->name ?? 'кг'); ?>

                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Тип:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?php echo e($selectedOperation->conversion->targetProduct->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo e($selectedOperation->conversion->targetProduct->type === 'simple' ? 'Простой' : 'Составной'); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Дополнительные элементы (если есть) -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedOperation->conversion->elements->count() > 0): ?>
                        <div class="mt-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Добавленные элементы в запасы</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedOperation->conversion->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversionElement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-900"><?php echo e($conversionElement->element->name); ?></span>
                                        <span class="text-sm font-semibold text-gray-700">
                                            <?php echo e(number_format($conversionElement->quantity, 3)); ?> <?php echo e($conversionElement->element->unit->name ?? 'кг'); ?>

                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Заметки -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedOperation->conversion->notes): ?>
                        <div class="mt-4">
                            <h5 class="font-semibold text-gray-900 mb-2">Заметки</h5>
                            <p class="text-sm text-gray-700 bg-white p-3 rounded border"><?php echo e($selectedOperation->conversion->notes); ?></p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- Items List -->
                    <!--[if BLOCK]><![endif]--><?php if($selectedOperation->items->count() > 0): ?>
                        <!--[if BLOCK]><![endif]--><?php if($selectedOperation->type !== 'conversion'): ?>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Товары в операции</h4>
                        <?php else: ?>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Связанные товары</h4>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedOperation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-semibold text-gray-900"><?php echo e($item->product->name); ?></h5>
                                    <p class="text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?php echo e($item->product->type === 'simple' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo e($item->product->type === 'simple' ? 'Простой' : 'Составной'); ?>

                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-lg"><?php echo e(\App\Helpers\Settings::formatPrice($item->price)); ?></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <label class="text-xs text-gray-500">Общий вес</label>
                                    <p class="text-sm text-gray-900"><?php echo e(number_format($item->weight, 2)); ?> <?php echo e($item->product->unit->short_name); ?></p>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php if($item->product->type === 'simple'): ?>
                                    <?php
                                        $clogging = $item->clogging ?? 0;
                                        $effectiveWeight = $item->weight - ($item->weight * $clogging / 100);
                                    ?>
                                    <div>
                                        <label class="text-xs text-gray-500">Засор</label>
                                        <p class="text-sm text-gray-900"><?php echo e($clogging); ?>%</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Чистый вес</label>
                                        <p class="text-sm font-semibold text-green-600"><?php echo e(number_format($effectiveWeight, 2)); ?> <?php echo e($item->product->unit->short_name); ?></p>
                                    </div>
                                <?php else: ?>
                                    <div></div>
                                    <div></div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div>
                                    <label class="text-xs text-gray-500">Цена за единицу</label>
                                    <?php
                                        // Для простых продуктов рассчитываем цену за единицу на основе чистого веса
                                        if ($item->product->type === 'simple') {
                                            $clogging = $item->clogging ?? 0;
                                            $effectiveWeight = $item->weight - ($item->weight * $clogging / 100);
                                            $pricePerUnit = $effectiveWeight > 0 ? $item->price / $effectiveWeight : 0;
                                        } else {
                                            $pricePerUnit = $item->weight > 0 ? $item->price / $item->weight : 0;
                                        }
                                    ?>
                                    <p class="text-sm text-gray-900"><?php echo e(\App\Helpers\Settings::formatPrice($pricePerUnit)); ?>/<?php echo e($item->product->unit->short_name); ?></p>
                                </div>
                            </div>

                            <!--[if BLOCK]><![endif]--><?php if($item->product->type === 'composite' && $item->elements->count() > 0): ?>
                            <div class="border-t border-gray-100 pt-3">
                                <h6 class="text-sm font-medium text-gray-700 mb-2">Элементы состава:</h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemElement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-gray-50 p-3 rounded">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium"><?php echo e($itemElement->element->name); ?></span>
                                            <span class="text-sm text-gray-600"><?php echo e($itemElement->percentage); ?>%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php
                                                $elementWeight = $item->weight * ($itemElement->percentage / 100);
                                            ?>
                                            Вес: <?php echo e(number_format($elementWeight, 4)); ?> <?php echo e($itemElement->element->unit->short_name); ?>

                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <p>В данной операции нет связанных товаров</p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeOperationDetailsModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/operations/operation-manager.blade.php ENDPATH**/ ?>