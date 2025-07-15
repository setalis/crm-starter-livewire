<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <?php echo e($isEditing ? 'Редактировать роль' : 'Создать роль'); ?>

                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <?php echo e($isEditing ? 'Изменение разрешений для роли' : 'Создание новой роли с набором разрешений'); ?>

                </p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.roles.index')); ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                    Назад к списку
                </a>
                <button wire:click="save" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?php echo e($isEditing ? 'Сохранить изменения' : 'Создать роль'); ?>

                </button>
            </div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 dark:bg-green-950 dark:border-green-800 dark:text-green-200">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <?php if(session()->has('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 dark:bg-red-950 dark:border-red-800 dark:text-red-200">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Основная информация о роли -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Основная информация</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Название роли *
                    </label>
                    <input type="text" wire:model="name" 
                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Например: Менеджер">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Статистика</h4>
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>Всего разрешений:</span>
                            <span class="font-medium"><?php echo e($permissions->flatten()->count()); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Выбрано:</span>
                            <span class="font-medium text-blue-600"><?php echo e(count($selectedPermissions)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Разрешения -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700 rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Разрешения</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Выберите разрешения для этой роли. Разрешения сгруппированы по разделам системы.
                    </p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionName => $sectionPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <!-- Заголовок секции -->
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                        <?php echo e($sectionName ?: 'Общие разрешения'); ?>

                                    </h4>
                                    <?php
                                        $sectionPermissionNames = $sectionPermissions->pluck('name')->toArray();
                                        $selectedInSection = count(array_intersect($sectionPermissionNames, $selectedPermissions));
                                        $allSelected = $selectedInSection === count($sectionPermissionNames);
                                        $someSelected = $selectedInSection > 0 && !$allSelected;
                                    ?>
                                    <button 
                                        wire:click="toggleSection(<?php echo e(json_encode($sectionPermissionNames)); ?>)"
                                        class="text-xs px-2 py-1 rounded <?php echo e($allSelected ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($someSelected ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400')); ?>">
                                        <?php echo e($allSelected ? 'Снять все' : 'Выбрать все'); ?>

                                        (<?php echo e($selectedInSection); ?>/<?php echo e(count($sectionPermissionNames)); ?>)
                                    </button>
                                </div>

                                <!-- Разрешения секции -->
                                <div class="space-y-2">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sectionPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-start">
                                            <input type="checkbox" 
                                                   wire:click="togglePermission('<?php echo e($permission->name); ?>')"
                                                   <?php echo e(in_array($permission->name, $selectedPermissions) ? 'checked' : ''); ?>

                                                   class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <div class="ml-3">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    <?php echo e($permission->display_name ?: $permission->name); ?>

                                                </span>
                                                <!--[if BLOCK]><![endif]--><?php if($permission->description): ?>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($permission->description); ?></p>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Footer с действиями -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Выбрано <?php echo e(count($selectedPermissions)); ?> из <?php echo e($permissions->flatten()->count()); ?> разрешений
                        </div>
                        <div class="flex space-x-3">
                            <a href="<?php echo e(route('admin.roles.index')); ?>" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500">
                                Отмена
                            </a>
                            <button wire:click="save" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <?php echo e($isEditing ? 'Сохранить изменения' : 'Создать роль'); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('role-permissions-updated', (roleName) => {
        // Показываем уведомление о том, что разрешения обновлены
        console.log('Разрешения роли обновлены:', roleName);
        
        // Можно добавить дополнительную логику для обновления других частей интерфейса
        // Например, обновить меню навигации или показать модальное окно
        
        // Простое уведомление через alert (можно заменить на более красивое уведомление)
        if (window.confirm('Разрешения роли "' + roleName + '" были обновлены. Хотите обновить страницу для применения изменений?')) {
            window.location.reload();
        }
    });
});
</script> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/roles/role-editor.blade.php ENDPATH**/ ?>