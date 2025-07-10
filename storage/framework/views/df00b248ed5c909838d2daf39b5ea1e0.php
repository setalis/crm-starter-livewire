<div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-neutral-200 dark:border-neutral-700 p-3">
    <div class="flex items-center justify-between">
        <!-- Левая часть - заголовок с иконкой -->
        <div class="flex items-center space-x-2">
            <i class="bi bi-calendar3 text-blue-600 dark:text-blue-400"></i>
            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Период:</h3>
        </div>
        
        <!-- Центральная часть - текущий период в одну строку -->
        <div class="flex-1 text-center px-4">
            <div class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">
                <?php echo e($periodLabel); ?> - <?php echo e($dateRange); ?>

            </div>
        </div>
        
        <!-- Правая часть - селектор -->
        <select wire:model.live="period" class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md">
            <option value="day">День</option>
            <option value="week">Неделя</option>
            <option value="month">Месяц</option>
        </select>
    </div>
</div> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/dashboard/period-selector.blade.php ENDPATH**/ ?>