<div>
    <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xl']); ?><?php echo e(__('Отчеты по движению')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>

    <div class="mt-6">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <?php if (isset($component)) { $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::field','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal8a84eac5abb8af1e2274971f8640b38f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Дата начала <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $attributes = $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $component = $__componentOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['type' => 'date','wire:model' => 'startDate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model' => 'startDate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $attributes = $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $component = $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
                </div>
                <div>
                    <?php if (isset($component)) { $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::field','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal8a84eac5abb8af1e2274971f8640b38f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Дата окончания <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $attributes = $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $component = $__componentOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['type' => 'date','wire:model' => 'endDate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model' => 'endDate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $attributes = $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $component = $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
                </div>
                <div class="flex items-end space-x-2">
                    <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'primary','wire:click' => 'generateReport','icon' => 'chart-bar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','wire:click' => 'generateReport','icon' => 'chart-bar']); ?>
                        Сформировать отчет
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
                    <!--[if BLOCK]><![endif]--><?php if($showReport): ?>
                        <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'outline','wire:click' => 'exportToExcel','icon' => 'document-arrow-down']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','wire:click' => 'exportToExcel','icon' => 'document-arrow-down']); ?>
                            Экспорт в Excel
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
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($showReport && isset($reportData['dates'])): ?>
        <div class="mt-6">
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <!-- Заголовок с датами -->
                        <thead class="bg-blue-50 dark:bg-blue-900/20">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[150px] sticky left-0 bg-blue-50 dark:bg-blue-900/20">
                                    Позиция
                                </th>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[200px]">
                                        <?php echo e($dateInfo['formatted']); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 min-w-[250px] bg-yellow-50 dark:bg-yellow-900/20">
                                    🏁 ИТОГО ЗА ПЕРИОД
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <!-- Строка по кассе -->
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-white dark:bg-zinc-900">
                                    Касса
                                </td>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top">
                                        <?php $cashData = $reportData['cash'][$dateInfo['date']]; ?>
                                        <!--[if BLOCK]><![endif]--><?php if($cashData['income'] > 0 || $cashData['expense'] > 0): ?>
                                            <div class="space-y-1">
                                                <!--[if BLOCK]><![endif]--><?php if($cashData['income'] > 0): ?>
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <strong>Пополнение:</strong><br>
                                                        <?php echo e(number_format($cashData['income'], 2)); ?> <?php echo e($this->currencySymbol); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <!--[if BLOCK]><![endif]--><?php if($cashData['expense'] > 0): ?>
                                                    <div class="text-red-600 dark:text-red-400">
                                                        <strong>Снятие:</strong><br>
                                                        <?php echo e(number_format($cashData['expense'], 2)); ?> <?php echo e($this->currencySymbol); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        <?php else: ?>
                                            <div class="text-zinc-400 text-center">-</div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-50 dark:bg-yellow-900/20">
                                    <?php $cashTotals = $reportData['cashTotals']; ?>
                                    <div class="space-y-1">
                                        <!--[if BLOCK]><![endif]--><?php if($cashTotals['income'] > 0): ?>
                                            <div class="text-green-600 dark:text-green-400">
                                                <strong>Всего пополнений:</strong><br>
                                                <?php echo e(number_format($cashTotals['income'], 2)); ?> <?php echo e($this->currencySymbol); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($cashTotals['expense'] > 0): ?>
                                            <div class="text-red-600 dark:text-red-400">
                                                <strong>Всего снятий:</strong><br>
                                                <?php echo e(number_format($cashTotals['expense'], 2)); ?> <?php echo e($this->currencySymbol); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <div class="border-t pt-1 mt-2 <?php echo e(($cashTotals['income'] - $cashTotals['expense']) >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'); ?>">
                                            <strong>Баланс кассы:</strong><br>
                                            <?php echo e(($cashTotals['income'] - $cashTotals['expense']) >= 0 ? '+' : ''); ?><?php echo e(number_format($cashTotals['income'] - $cashTotals['expense'], 2)); ?> <?php echo e($this->currencySymbol); ?>

                                        </div>
                                                                         </div>
                                 </td>
                                 <td class="px-4 py-4 text-center bg-blue-200 dark:bg-blue-900/40 border-r border-zinc-200 dark:border-zinc-700">
                                     <div class="text-blue-900 dark:text-blue-100 font-bold">
                                         <div class="text-lg">✅ ФИНАЛ</div>
                                         <div class="text-sm mt-1">Все данные<br>учтены</div>
                                     </div>
                                 </td>
                             </tr>

                            <!-- Строки по продуктам -->
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportData['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productName => $productDates): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-white dark:bg-zinc-900">
                                        <?php echo e($productName); ?>

                                    </td>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top">
                                            <?php $productData = $productDates[$dateInfo['date']]; ?>
                                            <!--[if BLOCK]><![endif]--><?php if($productData['has_data']): ?>
                                                <div class="space-y-1 text-xs">
                                                    <!-- Общий вес за день -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['total_weight'] > 0): ?>
                                                        <div class="font-bold text-gray-900 dark:text-gray-100">
                                                            <strong>Общий вес:</strong> <?php echo e(number_format($productData['total_weight'], 3)); ?> кг
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                                    <!-- Общие суммы в деньгах -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['purchase_amount'] > 0): ?>
                                                        <div class="text-red-600 dark:text-red-400">
                                                            <strong>💰 Потрачено:</strong> <?php echo e(number_format($productData['purchase_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['sale_amount'] > 0): ?>
                                                        <div class="text-green-600 dark:text-green-400">
                                                            <strong>💰 Получено:</strong> <?php echo e(number_format($productData['sale_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    
                                                    <!-- Разница по металлу -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['purchase_amount'] > 0 || $productData['sale_amount'] > 0): ?>
                                                        <?php 
                                                            $metalDifference = $productData['sale_amount'] - $productData['purchase_amount'];
                                                        ?>
                                                        <div class="border-t pt-1 <?php echo e($metalDifference >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'); ?>">
                                                            <strong>📊 Результат:</strong><br>
                                                            <?php echo e($metalDifference >= 0 ? '+' : ''); ?><?php echo e(number_format($metalDifference, 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    
                                                    <!-- Средние цены -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['purchase_avg_price'] > 0): ?>
                                                        <div class="text-blue-600 dark:text-blue-400">
                                                            <strong>Ср. цена покупки:</strong> <?php echo e(number_format($productData['purchase_avg_price'], 2)); ?> <?php echo e($this->currencySymbol); ?>/кг
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['sale_avg_price'] > 0): ?>
                                                        <div class="text-green-600 dark:text-green-400">
                                                            <strong>Ср. цена продажи:</strong> <?php echo e(number_format($productData['sale_avg_price'], 2)); ?> <?php echo e($this->currencySymbol); ?>/кг
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    
                                                    <!-- Средний засор -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['avg_contamination'] > 0): ?>
                                                        <div class="text-orange-600 dark:text-orange-400">
                                                            <strong>Ср. засор:</strong> <?php echo e(number_format($productData['avg_contamination'], 1)); ?>%
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    
                                                    <!-- Операции -->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['purchase_weight'] > 0): ?>
                                                        <div class="text-red-600 dark:text-red-400">
                                                            Покупка: <?php echo e(number_format($productData['purchase_weight'], 3)); ?> кг
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['sale_weight'] > 0): ?>
                                                        <div class="text-green-600 dark:text-green-400">
                                                            Продажа: <?php echo e(number_format($productData['sale_weight'], 3)); ?> кг
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($productData['shipment_weight'] > 0): ?>
                                                        <div class="text-purple-600 dark:text-purple-400 border-t pt-1">
                                                            <strong>🚚 Отгрузка:</strong> <?php echo e(number_format($productData['shipment_weight'], 3)); ?> кг
                                                            <!--[if BLOCK]><![endif]--><?php if($productData['shipment_amount'] > 0): ?>
                                                                <br><strong>Сумма:</strong> <?php echo e(number_format($productData['shipment_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            <!--[if BLOCK]><![endif]--><?php if(!empty($productData['shipments_details'])): ?>
                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productData['shipments_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="mt-1 text-xs bg-purple-50 dark:bg-purple-900/20 p-2 rounded">
                                                                        <div><strong>🏢 <?php echo e($shipment['company']); ?></strong></div>
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['car_number']): ?>
                                                                            <div>🚗 <?php echo e($shipment['car_number']); ?></div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['driver_name']): ?>
                                                                            <div>👤 <?php echo e($shipment['driver_name']); ?></div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <div>⚖️ Брутто: <?php echo e(number_format($shipment['weight'], 3)); ?> кг</div>
                                                                        <div>🧽 Чистый: <?php echo e(number_format($shipment['clean_weight'], 3)); ?> кг</div>
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['price_per_kg'] > 0): ?>
                                                                            <div>💵 Цена: <?php echo e(number_format($shipment['price_per_kg'], 2)); ?> <?php echo e($this->currencySymbol); ?>/кг</div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['total_amount'] > 0): ?>
                                                                            <div class="font-bold text-green-600">💰 Сумма: <?php echo e(number_format($shipment['total_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?></div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['shipping_cost'] > 0): ?>
                                                                            <div>🚛 Доставка: <?php echo e(number_format($shipment['shipping_cost'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?></div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <!--[if BLOCK]><![endif]--><?php if($shipment['actual_clogging'] > 0): ?>
                                                                            <div>🧹 Засор: <?php echo e(number_format($shipment['actual_clogging'], 1)); ?>%</div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        <div class="text-gray-500">🕐 <?php echo e($shipment['created_at']); ?></div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php else: ?>
                                                <div class="text-zinc-400 text-center">-</div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-50 dark:bg-yellow-900/20">
                                        <?php $productTotal = $reportData['productTotals'][$productName]; ?>
                                        <!--[if BLOCK]><![endif]--><?php if($productTotal['has_data']): ?>
                                            <div class="space-y-1 text-xs">
                                                <!-- Общий вес за период -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['total_weight'] > 0): ?>
                                                    <div class="font-bold text-gray-900 dark:text-gray-100">
                                                        <strong>Общий вес:</strong> <?php echo e(number_format($productTotal['total_weight'], 3)); ?> кг
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                                <!-- Общие суммы за период в деньгах -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['purchase_amount'] > 0): ?>
                                                    <div class="text-red-600 dark:text-red-400">
                                                        <strong>💰 Всего потрачено:</strong> <?php echo e(number_format($productTotal['purchase_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['sale_amount'] > 0): ?>
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <strong>💰 Всего получено:</strong> <?php echo e(number_format($productTotal['sale_amount'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                
                                                <!-- Общая разница по металлу за период -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['purchase_amount'] > 0 || $productTotal['sale_amount'] > 0): ?>
                                                    <?php 
                                                        $totalMetalDifference = $productTotal['sale_amount'] - $productTotal['purchase_amount'];
                                                    ?>
                                                    <div class="border-t pt-1 <?php echo e($totalMetalDifference >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'); ?>">
                                                        <strong>📊 Общий результат:</strong><br>
                                                        <?php echo e($totalMetalDifference >= 0 ? '+' : ''); ?><?php echo e(number_format($totalMetalDifference, 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                
                                                <!-- Средние цены за период -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['purchase_avg_price'] > 0): ?>
                                                    <div class="text-blue-600 dark:text-blue-400">
                                                        <strong>Ср. цена покупки:</strong> <?php echo e(number_format($productTotal['purchase_avg_price'], 2)); ?> <?php echo e($this->currencySymbol); ?>/кг
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['sale_avg_price'] > 0): ?>
                                                    <div class="text-green-600 dark:text-green-400">
                                                        <strong>Ср. цена продажи:</strong> <?php echo e(number_format($productTotal['sale_avg_price'], 2)); ?> <?php echo e($this->currencySymbol); ?>/кг
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                
                                                <!-- Средний засор за период -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['avg_contamination'] > 0): ?>
                                                    <div class="text-orange-600 dark:text-orange-400">
                                                        <strong>Ср. засор:</strong> <?php echo e(number_format($productTotal['avg_contamination'], 1)); ?>%
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                
                                                <!-- Итоги операций -->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['purchase_weight'] > 0): ?>
                                                    <div class="text-red-600 dark:text-red-400">
                                                        Всего покупок: <?php echo e(number_format($productTotal['purchase_weight'], 3)); ?> кг
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['sale_weight'] > 0): ?>
                                                    <div class="text-green-600 dark:text-green-400">
                                                        Всего продаж: <?php echo e(number_format($productTotal['sale_weight'], 3)); ?> кг
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <!--[if BLOCK]><![endif]--><?php if($productTotal['shipment_weight'] > 0): ?>
                                                    <div class="text-purple-600 dark:text-purple-400">
                                                        <strong>🚚 Всего отгрузок:</strong> <?php echo e(number_format($productTotal['shipment_weight'], 3)); ?> кг
                                                        <?php
                                                            $totalShipmentAmount = 0;
                                                            foreach($reportData['dates'] as $dateInfo) {
                                                                $totalShipmentAmount += $reportData['products'][$productName][$dateInfo['date']]['shipment_amount'] ?? 0;
                                                            }
                                                        ?>
                                                        <!--[if BLOCK]><![endif]--><?php if($totalShipmentAmount > 0): ?>
                                                            <br><strong>Общая сумма:</strong> <?php echo e(number_format($totalShipmentAmount, 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        <?php else: ?>
                                            <div class="text-zinc-400 text-center">-</div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!-- Итоговая строка по дням -->
                            <tr class="bg-gray-100 dark:bg-gray-700 font-bold border-t-2 border-gray-300 dark:border-gray-600">
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-zinc-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-gray-100 dark:bg-gray-700">
                                    💰 ИТОГО ЗА ДЕНЬ
                                </td>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="px-4 py-3 text-sm border-r border-zinc-200 dark:border-zinc-700 align-top">
                                                                                 <?php $dayTotal = $reportData['dailyTotals'][$dateInfo['date']]; ?>
                                         <div class="space-y-1 text-center">
                                             <!--[if BLOCK]><![endif]--><?php if($dayTotal['expenses'] > 0): ?>
                                                 <div class="text-red-600 dark:text-red-400">
                                                     <strong>Расход:</strong><br>
                                                     <small>(покупка металла)</small><br>
                                                     <?php echo e(number_format($dayTotal['expenses'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                 </div>
                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                             <!--[if BLOCK]><![endif]--><?php if($dayTotal['income'] > 0): ?>
                                                 <div class="text-green-600 dark:text-green-400">
                                                     <strong>Приход:</strong><br>
                                                     <small>(продажа металла)</small><br>
                                                     <?php echo e(number_format($dayTotal['income'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                 </div>
                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                             <!--[if BLOCK]><![endif]--><?php if($dayTotal['cash_income'] > 0): ?>
                                                 <div class="text-blue-600 dark:text-blue-400">
                                                     <strong>Пополнение:</strong><br>
                                                     <?php echo e(number_format($dayTotal['cash_income'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                 </div>
                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                             <!--[if BLOCK]><![endif]--><?php if($dayTotal['cash_expense'] > 0): ?>
                                                 <div class="text-orange-600 dark:text-orange-400">
                                                     <strong>Снятие:</strong><br>
                                                     <?php echo e(number_format($dayTotal['cash_expense'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                                 </div>
                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <div class="border-t pt-1 mt-2 <?php echo e($dayTotal['day_result'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'); ?>">
                                                <strong>РЕЗУЛЬТАТ:</strong><br>
                                                                                                 <?php echo e($dayTotal['day_result'] >= 0 ? '+' : ''); ?><?php echo e(number_format($dayTotal['day_result'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                            </div>
                                        </div>
                                                                         </td>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                 <td class="px-4 py-3 text-sm border-r border-zinc-200 dark:border-zinc-700 align-top bg-yellow-100 dark:bg-yellow-900/30">
                                     <?php $periodTotal = $reportData['periodTotal']; ?>
                                     <div class="space-y-1 text-center">
                                         <div class="text-red-600 dark:text-red-400">
                                             <strong>Общий расход:</strong><br>
                                             <?php echo e(number_format($periodTotal['expenses'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                         </div>
                                         <div class="text-green-600 dark:text-green-400">
                                             <strong>Общий приход:</strong><br>
                                             <?php echo e(number_format($periodTotal['income'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                         </div>
                                         <div class="border-t pt-1 mt-2 <?php echo e($periodTotal['period_result'] >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'); ?>">
                                             <strong>ИТОГО:</strong><br>
                                             <?php echo e($periodTotal['period_result'] >= 0 ? '+' : ''); ?><?php echo e(number_format($periodTotal['period_result'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                         </div>
                                     </div>
                                 </td>
                             </tr>
                            
                            <!-- Итог за весь период -->
                            <tr class="bg-blue-100 dark:bg-blue-900/30 font-bold border-t-4 border-blue-400 dark:border-blue-600">
                                <td class="px-4 py-4 text-sm font-bold text-blue-900 dark:text-blue-100 border-r border-zinc-200 dark:border-zinc-700 sticky left-0 bg-blue-100 dark:bg-blue-900/30">
                                    🏆 ИТОГО ЗА ПЕРИОД
                                </td>
                                <td colspan="<?php echo e(count($reportData['dates'])); ?>" class="px-4 py-4 text-center">
                                                                         <?php $periodTotal = $reportData['periodTotal']; ?>
                                     <div class="flex justify-center space-x-8 text-sm">
                                         <!--[if BLOCK]><![endif]--><?php if($periodTotal['expenses'] > 0): ?>
                                             <div class="text-red-600 dark:text-red-400">
                                                 <strong>Общие расходы:</strong><br>
                                                 <small>(покупка металла)</small><br>
                                                 <?php echo e(number_format($periodTotal['expenses'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                             </div>
                                         <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                         <!--[if BLOCK]><![endif]--><?php if($periodTotal['income'] > 0): ?>
                                             <div class="text-green-600 dark:text-green-400">
                                                 <strong>Общий приход:</strong><br>
                                                 <small>(продажа металла)</small><br>
                                                 <?php echo e(number_format($periodTotal['income'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                             </div>
                                         <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($periodTotal['cash_income'] > 0): ?>
                                            <div class="text-blue-600 dark:text-blue-400">
                                                <strong>Пополнения:</strong><br>
                                                                                                 <?php echo e(number_format($periodTotal['cash_income'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($periodTotal['cash_expense'] > 0): ?>
                                            <div class="text-orange-600 dark:text-orange-400">
                                                <strong>Снятия:</strong><br>
                                                                                                 <?php echo e(number_format($periodTotal['cash_expense'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <div class="border-l-2 pl-4 <?php echo e($periodTotal['period_result'] >= 0 ? 'text-green-700 dark:text-green-300 border-green-400' : 'text-red-700 dark:text-red-300 border-red-400'); ?>">
                                            <strong>ФИНАЛЬНЫЙ РЕЗУЛЬТАТ:</strong><br>
                                                                                         <span class="text-lg"><?php echo e($periodTotal['period_result'] >= 0 ? '+' : ''); ?><?php echo e(number_format($periodTotal['period_result'], 0, ',', ' ')); ?> <?php echo e($this->currencySymbol); ?></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif($showReport): ?>
        <div class="mt-6">
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-8 text-center">
                <div class="text-zinc-500 dark:text-zinc-400">
                    Нет данных за выбранный период
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> 
 <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/reports/report-manager.blade.php ENDPATH**/ ?>