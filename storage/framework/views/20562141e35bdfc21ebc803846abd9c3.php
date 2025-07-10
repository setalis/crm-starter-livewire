<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-2 sm:px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-boxes text-sm sm:text-base text-purple-600"></i>
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">Склад</h3>
        </div>
        <a href="<?php echo e(route('admin.warehouse.stock.index')); ?>" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hidden sm:block">
            Подробнее →
        </a>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 p-1 sm:p-2">
        <!-- Основные показатели -->
        <div class="grid grid-cols-3 gap-1 sm:gap-2 mb-1 sm:mb-2">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-1.5 sm:p-2 rounded">
                <div class="text-xs text-blue-600 dark:text-blue-400">Товары</div>
                <div class="text-xs sm:text-sm font-bold text-blue-700 dark:text-blue-300"><?php echo e($totalProducts); ?></div>
                <!--[if BLOCK]><![endif]--><?php if($lowStockProducts > 0): ?>
                <div class="text-xs text-red-600 dark:text-red-400 hidden sm:block"><?php echo e($lowStockProducts); ?> на исходе</div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <div class="bg-purple-50 dark:bg-purple-900/20 p-1.5 sm:p-2 rounded">
                <div class="text-xs text-purple-600 dark:text-purple-400">Элементы</div>
                <div class="text-xs sm:text-sm font-bold text-purple-700 dark:text-purple-300"><?php echo e($totalElements); ?></div>
                <!--[if BLOCK]><![endif]--><?php if($lowStockElements > 0): ?>
                <div class="text-xs text-red-600 dark:text-red-400 hidden sm:block"><?php echo e($lowStockElements); ?> на исходе</div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 p-1.5 sm:p-2 rounded">
                <div class="text-xs text-green-600 dark:text-green-400">Стоимость</div>
                <div class="text-xs sm:text-sm font-bold text-green-700 dark:text-green-300">
                    <?php echo e(\App\Helpers\Settings::formatPrice($warehouseValue + $elementsValue)); ?>

                </div>
            </div>
        </div>

        <!-- Критические остатки - скрываем на мобильных -->
        <!--[if BLOCK]><![endif]--><?php if(count($criticalProducts) > 0): ?>
        <div class="mb-1 sm:mb-2 hidden sm:block">
            <div class="flex items-center space-x-1 mb-1">
                <i class="bi bi-exclamation-triangle text-red-500 text-xs"></i>
                <h4 class="text-xs font-medium text-red-700 dark:text-red-300">Критические остатки</h4>
            </div>
            <div class="space-y-1 max-h-16 overflow-hidden">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = array_slice($criticalProducts, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between text-xs bg-red-50 dark:bg-red-900/20 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate"><?php echo e($product['name']); ?></span>
                    <span class="text-red-600 dark:text-red-400 font-medium">
                        <?php echo e(number_format($product['stock'], 1)); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($criticalProducts) > 2): ?>
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    +<?php echo e(count($criticalProducts) - 2); ?>

                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Топ товары - скрываем на мобильных -->
        <!--[if BLOCK]><![endif]--><?php if(count($topProducts) > 0): ?>
        <div class="hidden sm:block">
            <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Топ по стоимости</h4>
            <div class="space-y-1 max-h-16 overflow-hidden">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = array_slice($topProducts, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-gray-700 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate"><?php echo e($product['name']); ?></span>
                    <span class="text-green-600 dark:text-green-400 font-medium">
                        <?php echo e(\App\Helpers\Settings::formatPrice($product['value'])); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($topProducts) > 2): ?>
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    +<?php echo e(count($topProducts) - 2); ?>

                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Мобильная версия - показываем только критические остатки -->
        <!--[if BLOCK]><![endif]--><?php if(count($criticalProducts) > 0): ?>
        <div class="sm:hidden">
            <div class="text-center p-2 bg-red-50 dark:bg-red-900/20 rounded">
                <div class="text-xs text-red-600 dark:text-red-400">⚠️ Критических остатков: <?php echo e(count($criticalProducts)); ?></div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/dashboard/warehouse-stats.blade.php ENDPATH**/ ?>