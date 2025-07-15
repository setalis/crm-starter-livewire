<div>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Управление разрешениями</h1>
    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 dark:bg-green-950 dark:border-green-800 dark:text-green-200">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <?php if(session()->has('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 dark:bg-red-950 dark:border-red-800 dark:text-red-200">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="mb-6 flex justify-between items-center">
        <div class="flex-1 max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Поиск разрешений..." 
                data-flux-control
                data-flux-group-target
                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
        </div>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.create')): ?>
            <button wire:click="create" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Добавить разрешение
            </button>
        <?php endif; ?>
    </div>

    <!-- Адаптивная таблица разрешений -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <!-- Десктопная версия -->
        <div class="hidden lg:block">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Разрешение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Отображаемое имя</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Раздел</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Роли</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($permission->name); ?></div>
                            <!--[if BLOCK]><![endif]--><?php if($permission->description): ?>
                                <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($permission->description); ?></div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                            <?php echo e($permission->display_name ?: '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!--[if BLOCK]><![endif]--><?php if($permission->section): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <?php echo e($permission->section->display_name); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-gray-500 dark:text-gray-400">Без раздела</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <?php echo e($permission->roles_count); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.edit')): ?>
                                    <button wire:click="edit(<?php echo e($permission->id); ?>)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Редактировать
                                    </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.delete')): ?>
                                    <button 
                                        wire:click="delete(<?php echo e($permission->id); ?>)" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 <?php echo e($permission->roles_count > 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                        wire:confirm="Вы уверены, что хотите удалить это разрешение?"
                                        <?php echo e($permission->roles_count > 0 ? 'disabled' : ''); ?>

                                    >
                                        Удалить
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            Разрешения не найдены
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
        </div>

        <!-- Планшетная версия -->
        <div class="hidden md:block lg:hidden">
            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-4 gap-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <div>Разрешение</div>
                    <div>Раздел</div>
                    <div class="text-center">Роли</div>
                    <div class="text-center">Действия</div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-800 bg-white dark:bg-gray-900">
                    <div class="grid grid-cols-4 gap-4 items-center">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($permission->name); ?></div>
                            <!--[if BLOCK]><![endif]--><?php if($permission->display_name): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($permission->display_name); ?></div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <!--[if BLOCK]><![endif]--><?php if($permission->section): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <?php echo e($permission->section->display_name); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Без раздела</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <?php echo e($permission->roles_count); ?>

                            </span>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.edit')): ?>
                                    <button wire:click="edit(<?php echo e($permission->id); ?>)" class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Изменить
                                    </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.delete')): ?>
                                    <button 
                                        wire:click="delete(<?php echo e($permission->id); ?>)" 
                                        class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 <?php echo e($permission->roles_count > 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                        wire:confirm="Вы уверены, что хотите удалить это разрешение?"
                                        <?php echo e($permission->roles_count > 0 ? 'disabled' : ''); ?>

                                    >
                                        Удалить
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-4 py-12 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900">
                    Разрешения не найдены
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Мобильная версия (карточки) -->
        <div class="md:hidden bg-white dark:bg-gray-900">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-b border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <!-- Заголовок карточки -->
                    <div class="mb-3">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($permission->name); ?></h3>
                        <!--[if BLOCK]><![endif]--><?php if($permission->display_name): ?>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo e($permission->display_name); ?></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($permission->description): ?>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($permission->description); ?></div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Детали -->
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Раздел</div>
                            <!--[if BLOCK]><![endif]--><?php if($permission->section): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 mt-1">
                                    <?php echo e($permission->section->display_name); ?>

                                </span>
                            <?php else: ?>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Без раздела</div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Ролей</div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mt-1">
                                <?php echo e($permission->roles_count); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="flex items-center justify-end space-x-3 pt-2 border-t border-gray-100 dark:border-gray-600">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.edit')): ?>
                            <button wire:click="edit(<?php echo e($permission->id); ?>)" class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Редактировать
                            </button>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.delete')): ?>
                            <button 
                                wire:click="delete(<?php echo e($permission->id); ?>)" 
                                class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 <?php echo e($permission->roles_count > 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                wire:confirm="Вы уверены, что хотите удалить это разрешение?"
                                <?php echo e($permission->roles_count > 0 ? 'disabled' : ''); ?>

                            >
                                Удалить
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    Разрешения не найдены
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <div class="mt-4">
        <?php echo e($permissions->links()); ?>

    </div>

    <!-- Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            
            <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg" wire:click.stop>
                <form wire:submit="save">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                <?php echo e($editingPermission ? 'Редактировать разрешение' : 'Создать разрешение'); ?>

                            </h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Название разрешения *</label>
                                <input type="text" wire:model="name" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="например: users.create">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Отображаемое имя</label>
                                <input type="text" wire:model="display_name" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="например: Создание пользователей">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Раздел</label>
                                <select wire:model="section_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Без раздела</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($section->id); ?>"><?php echo e($section->display_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Описание</label>
                                <textarea wire:model="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Краткое описание разрешения"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <?php echo e($editingPermission ? 'Обновить' : 'Создать'); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/permissions/permission-manager.blade.php ENDPATH**/ ?>