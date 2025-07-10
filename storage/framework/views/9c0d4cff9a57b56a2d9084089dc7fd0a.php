<div>
    <div class="space-y-6 p-6">
        <?php if (isset($component)) { $__componentOriginale96c14d638c792103c11b984a4ed1896 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale96c14d638c792103c11b984a4ed1896 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::header','data' => ['class' => 'flex-wrap justify-between gap-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex-wrap justify-between gap-4']); ?>
            <?php if (isset($component)) { $__componentOriginalbbbea167ab072e3e3621cf7b736152aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbbea167ab072e3e3621cf7b736152aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => route('dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard'))]); ?><?php echo e(__('Dashboard')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Склад')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Остатки')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbbbea167ab072e3e3621cf7b736152aa)): ?>
<?php $attributes = $__attributesOriginalbbbea167ab072e3e3621cf7b736152aa; ?>
<?php unset($__attributesOriginalbbbea167ab072e3e3621cf7b736152aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbbbea167ab072e3e3621cf7b736152aa)): ?>
<?php $component = $__componentOriginalbbbea167ab072e3e3621cf7b736152aa; ?>
<?php unset($__componentOriginalbbbea167ab072e3e3621cf7b736152aa); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $attributes = $__attributesOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__attributesOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $component = $__componentOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__componentOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>

        <!-- Вкладки -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    wire:click="setActiveTab('products')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'products',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'products'
                    ]); ?>"
                >
                    <i class="bi bi-box mr-2"></i>
                    <?php echo e(__('Товары')); ?>

                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none rounded-full',
                        'text-blue-100 bg-blue-600' => $activeTab === 'products',
                        'text-gray-600 bg-gray-200 dark:text-gray-300 dark:bg-gray-600' => $activeTab !== 'products'
                    ]); ?>">
                        <?php echo e($products->count()); ?>

                    </span>
                </button>
                
                <button 
                    wire:click="setActiveTab('elements')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'elements',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'elements'
                    ]); ?>"
                >
                    <i class="bi bi-puzzle mr-2"></i>
                    <?php echo e(__('Элементы')); ?>

                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none rounded-full',
                        'text-blue-100 bg-blue-600' => $activeTab === 'elements',
                        'text-gray-600 bg-gray-200 dark:text-gray-300 dark:bg-gray-600' => $activeTab !== 'elements'
                    ]); ?>">
                        <?php echo e($elements->count()); ?>

                    </span>
                </button>
                
                <button 
                    wire:click="setActiveTab('summary')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                        'border-blue-500 text-blue-600 dark:text-blue-400' => $activeTab === 'summary',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' => $activeTab !== 'summary'
                    ]); ?>"
                >
                    <i class="bi bi-bar-chart mr-2"></i>
                    <?php echo e(__('Сводка')); ?>

                </button>
            </nav>
        </div>

        <!-- Контент вкладок -->
        <!--[if BLOCK]><![endif]--><?php if($activeTab === 'products'): ?>
        <!-- Товары -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e(__('Остатки товаров')); ?></h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <?php echo e(__('Всего товаров:')); ?> <?php echo e($products->count()); ?>

                </div>
            </div>
            
            <!-- Адаптивная таблица складских остатков товаров -->
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                <!-- Десктопная версия -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Изображение')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Наименование')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Тип')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Остаток')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Единица измерения')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Средняя цена закупки')); ?></th>
                                <th class="px-3.5 py-2.5 text-center text-sm font-semibold rtl:text-right"><?php echo e(__('Сумма')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="product-<?php echo e($product->id); ?>">
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-center">
                                    <!--[if BLOCK]><![endif]--><?php if($product->image): ?>
                                        <img src="<?php echo e(Storage::url($product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-12 h-12 object-cover rounded-lg mx-auto">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto">
                                            <i class="bi bi-image text-gray-400 text-xl"></i>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm text-center"><?php echo e($product->name); ?></td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm text-center">
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $product->type === 'simple',
                                        'bg-blue-100 text-blue-800' => $product->type === 'composite',
                                    ]); ?>">
                                        <?php echo e($product->type === 'simple' ? 'Простой' : 'Составной'); ?>

                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm text-center">
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'font-semibold',
                                        'text-red-600' => $product->stock <= 10,
                                        'text-yellow-600' => $product->stock > 10 && $product->stock <= 50,
                                        'text-green-600' => $product->stock > 50,
                                    ]); ?>">
                                        <?php echo e(number_format($product->stock, 4)); ?>

                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm text-center"><?php echo e($product->unit->name); ?></td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm text-center"><?php echo e(\App\Helpers\Settings::formatPrice($product->average_purchase_price ?? 0)); ?></td>
                                <td class="whitespace-nowrap px-3.5 py-2.5 text-sm font-semibold text-center"><?php echo e(\App\Helpers\Settings::formatPrice($product->stock * ($product->average_purchase_price ?? 0))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="space-y-4">
                                        <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                            <i class="bi bi-box text-5xl"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold"><?php echo e(__('Товары не найдены')); ?></p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400"><?php echo e(__('В системе пока нет товаров.')); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                        <!--[if BLOCK]><![endif]--><?php if($products->count() > 0): ?>
                        <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <td colspan="6" class="px-3.5 py-2.5 text-sm font-semibold text-right"><?php echo e(__('Общая сумма товаров:')); ?></td>
                                <td class="px-3.5 py-2.5 text-sm font-bold text-center">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($products->sum(function($product) { return $product->stock * ($product->average_purchase_price ?? 0); }))); ?>

                                </td>
                            </tr>
                        </tfoot>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </table>
                </div>

                <!-- Мобильная версия (карточки) -->
                <div class="md:hidden">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-b border-zinc-200 dark:border-zinc-700 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:key="mobile-product-<?php echo e($product->id); ?>">
                        <!-- Заголовок карточки -->
                        <div class="flex items-center space-x-3 mb-3">
                            <!--[if BLOCK]><![endif]--><?php if($product->image): ?>
                                <img src="<?php echo e(Storage::url($product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-12 h-12 object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400 text-xl"></i>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-white"><?php echo e($product->name); ?></h3>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1',
                                    'bg-green-100 text-green-800' => $product->type === 'simple',
                                    'bg-blue-100 text-blue-800' => $product->type === 'composite',
                                ]); ?>">
                                    <?php echo e($product->type === 'simple' ? 'Простой' : 'Составной'); ?>

                                </span>
                            </div>
                        </div>

                        <!-- Детали товара -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Остаток</div>
                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'font-semibold text-sm',
                                    'text-red-600' => $product->stock <= 10,
                                    'text-yellow-600' => $product->stock > 10 && $product->stock <= 50,
                                    'text-green-600' => $product->stock > 50,
                                ]); ?>">
                                    <?php echo e(number_format($product->stock, 4)); ?> <?php echo e($product->unit->name); ?>

                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Средняя цена</div>
                                <div class="font-medium text-sm text-gray-900 dark:text-white">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($product->average_purchase_price ?? 0)); ?>

                                </div>
                            </div>
                        </div>

                        <!-- Общая сумма -->
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Общая сумма:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($product->stock * ($product->average_purchase_price ?? 0))); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-12 text-center">
                        <div class="space-y-4">
                            <div class="flex justify-center text-zinc-400 dark:text-zinc-500">
                                <i class="bi bi-box text-5xl"></i>
                            </div>
                            <div class="space-y-1">
                                <p class="text-lg font-semibold"><?php echo e(__('Товары не найдены')); ?></p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400"><?php echo e(__('В системе пока нет товаров.')); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($products->count() > 0): ?>
                    <!-- Итоговая сумма для мобильной версии -->
                    <div class="bg-zinc-50 dark:bg-zinc-800 p-4 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900 dark:text-white"><?php echo e(__('Общая сумма товаров:')); ?></span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                <?php echo e(\App\Helpers\Settings::formatPrice($products->sum(function($product) { return $product->stock * ($product->average_purchase_price ?? 0); }))); ?>

                            </span>
                        </div>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($activeTab === 'elements'): ?>
        <!-- Элементы -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e(__('Остатки элементов')); ?></h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <?php echo e(__('Всего элементов:')); ?> <?php echo e($elements->count()); ?>

                </div>
            </div>
            
            <!-- Адаптивная таблица элементов -->
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                <!-- Десктопная версия таблицы -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Наименование</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Остаток</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Единица измерения</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена за ед.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150" wire:key="element-<?php echo e($element->id); ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($element->name); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-red-100 text-red-800' => $element->stock <= 10,
                                        'bg-yellow-100 text-yellow-800' => $element->stock > 10 && $element->stock <= 50,
                                        'bg-green-100 text-green-800' => $element->stock > 50,
                                    ]); ?>">
                                        <?php echo e(number_format($element->stock, 4)); ?> <?php echo e($element->unit->short_name ?? $element->unit->name); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($element->unit->name); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(\App\Helpers\Settings::formatPrice($element->unit_price)); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"><?php echo e(\App\Helpers\Settings::formatPrice($element->stock * $element->unit_price)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="space-y-4">
                                        <div class="flex justify-center text-zinc-400">
                                            <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold"><?php echo e(__('Элементы не найдены')); ?></p>
                                            <p class="text-sm text-zinc-500"><?php echo e(__('В системе пока нет элементов.')); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                        <!--[if BLOCK]><![endif]--><?php if($elements->count() > 0): ?>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-sm font-semibold text-right text-gray-900"><?php echo e(__('Общая сумма элементов:')); ?></td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($elements->sum(function($element) { return $element->stock * $element->unit_price; }))); ?>

                                </td>
                            </tr>
                        </tfoot>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </table>
                </div>

                <!-- Планшетная версия таблицы -->
                <div class="hidden md:block lg:hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="grid grid-cols-4 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div>Элемент</div>
                            <div>Остаток</div>
                            <div>Цена за ед.</div>
                            <div class="text-right">Сумма</div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150" wire:key="tablet-element-<?php echo e($element->id); ?>">
                            <div class="grid grid-cols-4 gap-4 items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($element->name); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($element->unit->name); ?></div>
                                </div>
                                <div>
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        'bg-red-100 text-red-800' => $element->stock <= 10,
                                        'bg-yellow-100 text-yellow-800' => $element->stock > 10 && $element->stock <= 50,
                                        'bg-green-100 text-green-800' => $element->stock > 50,
                                    ]); ?>">
                                        <?php echo e(number_format($element->stock, 3)); ?>

                                    </span>
                                </div>
                                <div class="text-sm text-gray-900">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($element->unit_price)); ?>

                                </div>
                                <div class="text-right text-sm font-semibold text-gray-900">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($element->stock * $element->unit_price)); ?>

                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 py-12 text-center">
                            <div class="space-y-4">
                                <div class="flex justify-center text-zinc-400">
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-semibold"><?php echo e(__('Элементы не найдены')); ?></p>
                                    <p class="text-sm text-zinc-500"><?php echo e(__('В системе пока нет элементов.')); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($elements->count() > 0): ?>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-900"><?php echo e(__('Общая сумма элементов:')); ?></span>
                                <span class="text-sm font-bold text-gray-900">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($elements->sum(function($element) { return $element->stock * $element->unit_price; }))); ?>

                                </span>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Мобильная версия (карточки) -->
                <div class="md:hidden">
                    <div class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 space-y-3" wire:key="mobile-element-<?php echo e($element->id); ?>">
                            <!-- Заголовок карточки -->
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($element->name); ?></div>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-red-100 text-red-800' => $element->stock <= 10,
                                    'bg-yellow-100 text-yellow-800' => $element->stock > 10 && $element->stock <= 50,
                                    'bg-green-100 text-green-800' => $element->stock > 50,
                                ]); ?>">
                                    <?php echo e(number_format($element->stock, 3)); ?> <?php echo e($element->unit->short_name ?? $element->unit->name); ?>

                                </span>
                            </div>

                            <!-- Детали элемента -->
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Единица:</span>
                                    <div class="font-medium text-gray-900"><?php echo e($element->unit->name); ?></div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Цена за ед.:</span>
                                    <div class="font-medium text-gray-900"><?php echo e(\App\Helpers\Settings::formatPrice($element->unit_price)); ?></div>
                                </div>
                            </div>

                            <!-- Сумма -->
                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500 text-sm">Общая сумма:</span>
                                    <span class="text-lg font-semibold text-gray-900">
                                        <?php echo e(\App\Helpers\Settings::formatPrice($element->stock * $element->unit_price)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 py-12 text-center">
                            <div class="space-y-4">
                                <div class="flex justify-center text-zinc-400">
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-semibold"><?php echo e(__('Элементы не найдены')); ?></p>
                                    <p class="text-sm text-zinc-500"><?php echo e(__('В системе пока нет элементов.')); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($elements->count() > 0): ?>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-900"><?php echo e(__('Общая сумма элементов:')); ?></span>
                                <span class="text-lg font-bold text-gray-900">
                                    <?php echo e(\App\Helpers\Settings::formatPrice($elements->sum(function($element) { return $element->stock * $element->unit_price; }))); ?>

                                </span>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($activeTab === 'summary'): ?>
        <!-- Сводка -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e(__('Сводка склада')); ?></h2>
            
            <!-- Статистика карточки -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Товары -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-box text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Товары')); ?></p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($products->count()); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Элементы -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-puzzle text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Элементы')); ?></p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($elements->count()); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Стоимость товаров -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-currency-exchange text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Товары')); ?></p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                <?php echo e(\App\Helpers\Settings::formatPrice($products->sum(function($product) { return $product->stock * ($product->average_purchase_price ?? 0); }))); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Стоимость элементов -->
                <div class="rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-currency-exchange text-2xl text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Элементы')); ?></p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                <?php echo e(\App\Helpers\Settings::formatPrice($elements->sum(function($element) { return $element->stock * $element->unit_price; }))); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Общий итог -->
            <div class="rounded-lg border-2 border-green-200 bg-green-50 p-6 dark:border-green-800 dark:bg-green-900/20">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200"><?php echo e(__('Общая стоимость склада')); ?></h3>
                        <p class="text-sm text-green-600 dark:text-green-300"><?php echo e(__('Сумма всех товаров и элементов')); ?></p>
                    </div>
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                        <?php echo e(\App\Helpers\Settings::formatPrice(
                            $products->sum(function($product) { return $product->stock * ($product->average_purchase_price ?? 0); }) +
                            $elements->sum(function($element) { return $element->stock * $element->unit_price; })
                        )); ?>

                    </span>
                </div>
            </div>

            <!-- Товары с низким остатком -->
            <?php
                $lowStockProducts = $products->filter(function($product) { return $product->stock <= 10; });
                $lowStockElements = $elements->filter(function($element) { return $element->stock <= 10; });
            ?>
            
            <!--[if BLOCK]><![endif]--><?php if($lowStockProducts->count() > 0 || $lowStockElements->count() > 0): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    <?php echo e(__('Предупреждения о низких остатках')); ?>

                </h3>
                
                <?php if($lowStockProducts->count() > 0): ?>
                <div class="mb-3">
                    <h4 class="font-medium text-red-700 dark:text-red-300 mb-2"><?php echo e(__('Товары')); ?>:</h4>
                    <div class="space-y-1">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="text-sm text-red-600 dark:text-red-400">
                            • <?php echo e($product->name); ?> - <?php echo e(number_format($product->stock, 3)); ?> <?php echo e($product->unit->name); ?>

                        </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($lowStockElements->count() > 0): ?>
                <div>
                    <h4 class="font-medium text-red-700 dark:text-red-300 mb-2"><?php echo e(__('Элементы')); ?>:</h4>
                    <div class="space-y-1">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lowStockElements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="text-sm text-red-600 dark:text-red-400">
                            • <?php echo e($element->name); ?> - <?php echo e(number_format($element->stock, 3)); ?> <?php echo e($element->unit->name); ?>

                        </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/warehouse/stock-manager.blade.php ENDPATH**/ ?>