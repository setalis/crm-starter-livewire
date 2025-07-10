<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-2 sm:px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-activity text-sm sm:text-base text-orange-600"></i>
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">Операции</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">
            <?php echo e($periodLabel); ?>

        </div>
    </div>

    <!-- Содержимое -->
    <div class="flex-1 p-1 sm:p-2">
        <!-- Основные показатели -->
        <div class="grid grid-cols-3 gap-1 sm:gap-2 mb-1 sm:mb-2">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-1.5 sm:p-2 rounded text-center">
                <div class="text-xs text-blue-600 dark:text-blue-400">Всего</div>
                <div class="text-xs sm:text-sm font-bold text-blue-700 dark:text-blue-300"><?php echo e($totalOperations); ?></div>
            </div>
            
            <div class="bg-green-50 dark:bg-green-900/20 p-1.5 sm:p-2 rounded text-center">
                <div class="text-xs text-green-600 dark:text-green-400">Продажи</div>
                <div class="text-xs sm:text-sm font-bold text-green-700 dark:text-green-300"><?php echo e($salesCount); ?></div>
                <div class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">
                    <?php echo e(\App\Helpers\Settings::formatPrice($averageSaleCheck)); ?>

                </div>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 p-1.5 sm:p-2 rounded text-center">
                <div class="text-xs text-red-600 dark:text-red-400">Покупки</div>
                <div class="text-xs sm:text-sm font-bold text-red-700 dark:text-red-300"><?php echo e($purchasesCount); ?></div>
                <div class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">
                    <?php echo e(\App\Helpers\Settings::formatPrice($averagePurchaseCheck)); ?>

                </div>
            </div>
        </div>

        <!-- Средний чек -->
        <div class="bg-purple-50 dark:bg-purple-900/20 p-1.5 sm:p-2 rounded mb-1 sm:mb-2">
            <div class="text-xs text-purple-600 dark:text-purple-400 text-center">Средний чек</div>
            <div class="text-xs sm:text-sm font-bold text-purple-700 dark:text-purple-300 text-center">
                <?php echo e(\App\Helpers\Settings::formatPrice($averageCheck)); ?>

            </div>
        </div>

        <!-- Топ пользователи -->
        <!--[if BLOCK]><![endif]--><?php if(count($topUsers) > 0): ?>
        <div class="hidden sm:block">
            <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Топ пользователи</h4>
            <div class="space-y-1 max-h-20 overflow-hidden">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-gray-700 p-1 rounded">
                    <span class="text-gray-900 dark:text-white truncate"><?php echo e($user['name']); ?></span>
                    <div class="text-right">
                        <div class="text-orange-600 dark:text-orange-400 font-medium"><?php echo e($user['count']); ?></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <?php echo e(\App\Helpers\Settings::formatPrice($user['amount'])); ?>

                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/dashboard/operational-stats.blade.php ENDPATH**/ ?>