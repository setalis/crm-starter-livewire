<div>
    
    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?php echo e($activeTab === 'user' ? 'Пользовательские комментарии' : 'Системные сообщения'); ?>

                <!--[if BLOCK]><![endif]--><?php if(($activeTab === 'user' && $stats['user_unread'] > 0) || ($activeTab === 'system' && $stats['system_unread'] > 0)): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <?php echo e($activeTab === 'user' ? $stats['user_unread'] : $stats['system_unread']); ?> непрочитанных
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h2>
            <div class="flex space-x-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('comments.manage')): ?>
                    <!--[if BLOCK]><![endif]--><?php if(($activeTab === 'user' && $stats['user_unread'] > 0) || ($activeTab === 'system' && $stats['system_unread'] > 0)): ?>
                        <button wire:click="markAllAsRead" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Отметить все как прочитанные
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('comments.create')): ?>
                    <!--[if BLOCK]><![endif]--><?php if($activeTab === 'user'): ?>
                        <button wire:click="openAddCommentModal" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Добавить комментарий к объекту
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?>
            </div>
        </div>

        
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="setActiveTab('user')"
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors <?php echo e($activeTab === 'user' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                    Пользовательские комментарии
                    <!--[if BLOCK]><![endif]--><?php if($stats['user_total'] > 0): ?>
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo e($stats['user_total']); ?></span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($stats['user_unread'] > 0): ?>
                        <span class="ml-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs"><?php echo e($stats['user_unread']); ?> новых</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </button>
                <button wire:click="setActiveTab('system')"
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors <?php echo e($activeTab === 'system' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                    Системные сообщения
                    <!--[if BLOCK]><![endif]--><?php if($stats['system_total'] > 0): ?>
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs"><?php echo e($stats['system_total']); ?></span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($stats['system_unread'] > 0): ?>
                        <span class="ml-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs"><?php echo e($stats['system_unread']); ?> новых</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </button>
            </nav>
        </div>

        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-sm font-medium text-blue-900">Всего <?php echo e($activeTab === 'user' ? 'пользовательских' : 'системных'); ?></div>
                <div class="text-2xl font-bold text-blue-600"><?php echo e($activeTab === 'user' ? $stats['user_total'] : $stats['system_total']); ?></div>
            </div>
            <div class="bg-red-50 rounded-lg p-3">
                <div class="text-sm font-medium text-red-900">Непрочитанные</div>
                <div class="text-2xl font-bold text-red-600"><?php echo e($activeTab === 'user' ? $stats['user_unread'] : $stats['system_unread']); ?></div>
            </div>
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'user'): ?>
            <div class="bg-yellow-50 rounded-lg p-3">
                <div class="text-sm font-medium text-yellow-900">Важные</div>
                <div class="text-2xl font-bold text-yellow-600"><?php echo e($stats['important']); ?></div>
            </div>
            <?php else: ?>
            <div class="bg-purple-50 rounded-lg p-3">
                <div class="text-sm font-medium text-purple-900">Автоматические</div>
                <div class="text-2xl font-bold text-purple-600"><?php echo e($stats['system_total']); ?></div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <div class="bg-green-50 rounded-lg p-3">
                <div class="text-sm font-medium text-green-900">За неделю</div>
                <div class="text-2xl font-bold text-green-600"><?php echo e($stats['recent']); ?></div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-<?php echo e($activeTab === 'user' ? '6' : '4'); ?> gap-4">
            <div>
                <input type="text" wire:model.live="search" placeholder="Поиск по содержимому..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'user'): ?>
            <div>
                <select wire:model.live="filterType" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все типы</option>
                    <option value="comment">Комментарий</option>
                    <option value="note">Заметка</option>
                    <option value="warning">Предупреждение</option>
                    <option value="info">Информация</option>
                </select>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <div>
                <select wire:model.live="filterIsRead" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все</option>
                    <option value="0">Непрочитанные</option>
                    <option value="1">Прочитанные</option>
                </select>
            </div>
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'user'): ?>
            <div>
                <select wire:model.live="filterImportant" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все</option>
                    <option value="1">Важные</option>
                    <option value="0">Обычные</option>
                </select>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <div>
                <select wire:model.live="filterUser" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все пользователи</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            <div>
                <select wire:model.live="filterSection" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все разделы</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionType => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sectionType); ?>"><?php echo e($this->getSectionName($sectionType)); ?> (<?php echo e($count); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-lg shadow-sm">
        <!--[if BLOCK]><![endif]--><?php if($comments->count() > 0): ?>
            <div class="divide-y divide-gray-200">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 <?php echo e(!$comment->is_read ? 'bg-blue-50 border-l-4 border-blue-400' : ''); ?>">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($comment->type_css_class); ?> bg-opacity-10">
                                        <?php echo e($comment->type_display_name); ?>

                                    </span>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($comment->is_important): ?>
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                            Важно
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    <!--[if BLOCK]><![endif]--><?php if(!$comment->is_read): ?>
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Новый
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                
                                <p class="text-gray-800 mb-3"><?php echo e($comment->content); ?></p>

                                
                                <!--[if BLOCK]><![endif]--><?php if($comment->commentable): ?>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Относится к:</span> <?php echo e($comment->commentable_description); ?>

                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><?php echo e($comment->user->name); ?></span>
                                    <span><?php echo e($comment->created_at->format('d.m.Y H:i')); ?></span>
                                    <!--[if BLOCK]><![endif]--><?php if($comment->is_read && $comment->readBy): ?>
                                        <span>Прочитано <?php echo e($comment->readBy->name); ?> <?php echo e($comment->read_at->format('d.m.Y H:i')); ?></span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>

                            
                            <div class="flex items-center space-x-2 ml-4">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('comments.manage')): ?>
                                    <!--[if BLOCK]><![endif]--><?php if(!$comment->is_read): ?>
                                        <button wire:click="markAsRead(<?php echo e($comment->id); ?>)" 
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                            Прочитано
                                        </button>
                                    <?php else: ?>
                                        <button wire:click="markAsUnread(<?php echo e($comment->id); ?>)" 
                                                class="text-gray-600 hover:text-gray-800 text-sm">
                                            Не прочитано
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?>

                                <!--[if BLOCK]><![endif]--><?php if($comment->user_id === auth()->id() || auth()->user()->can('comments.delete')): ?>
                                    <button wire:click="deleteComment(<?php echo e($comment->id); ?>)" 
                                            wire:confirm="Вы уверены, что хотите удалить этот комментарий?"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Удалить
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div class="px-6 py-4 bg-gray-50">
                <?php echo e($comments->links()); ?>

            </div>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.544-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    <?php echo e($activeTab === 'user' ? 'Пользовательских комментариев пока нет' : 'Системных сообщений пока нет'); ?>

                </h3>
                <p class="text-gray-500">
                    <!--[if BLOCK]><![endif]--><?php if($activeTab === 'user'): ?>
                        Комментарии создаются при работе с продуктами, элементами, операциями и другими объектами системы.
                    <?php else: ?>
                        Системные сообщения появятся автоматически при выполнении операций.
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showAddCommentModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAddCommentModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit="addCommentToSelectedObject">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Добавить комментарий к объекту
                            </h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип объекта</label>
                                        <select wire:model.live="selectedObjectType" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Выберите тип объекта</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->getObjectTypeOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedObjectType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Объект</label>
                                        <select wire:model="selectedObjectId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" <?php echo e(!$selectedObjectType ? 'disabled' : ''); ?>>
                                            <option value="">Выберите объект</option>
                                            <!--[if BLOCK]><![endif]--><?php if($selectedObjectType && isset($availableObjects[$selectedObjectType])): ?>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableObjects[$selectedObjectType]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $object): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($object->id); ?>">
                                                        <!--[if BLOCK]><![endif]--><?php if($selectedObjectType === 'App\Models\Product' || $selectedObjectType === 'App\Models\Element'): ?>
                                                            <?php echo e($object->name); ?>

                                                        <?php elseif($selectedObjectType === 'App\Models\Operation'): ?>
                                                            <?php echo e($object->operation_number); ?>

                                                        <?php elseif($selectedObjectType === 'App\Models\Shipment'): ?>
                                                            #<?php echo e($object->id); ?> - <?php echo e($object->company); ?>

                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedObjectId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Содержимое комментария</label>
                                    <textarea wire:model="content" rows="4" 
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Введите комментарий..."></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип комментария</label>
                                        <select wire:model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->getCommentTypeOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div class="flex items-center justify-center">
                                        <input type="checkbox" wire:model="isImportant" id="isImportantAdd" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <label for="isImportantAdd" class="ml-2 text-sm text-gray-700">Важный комментарий</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Добавить комментарий
                            </button>
                            <button type="button" wire:click="closeAddCommentModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="addComment">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Добавить комментарий
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Содержимое</label>
                                    <textarea wire:model="content" rows="4" 
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Введите комментарий..."></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Тип</label>
                                    <select wire:model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->getCommentTypeOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="isImportant" id="isImportant" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="isImportant" class="ml-2 text-sm text-gray-700">Важный комментарий</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Добавить
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <?php if(session()->has('error')): ?>
        <div class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/comments/comment-manager.blade.php ENDPATH**/ ?>