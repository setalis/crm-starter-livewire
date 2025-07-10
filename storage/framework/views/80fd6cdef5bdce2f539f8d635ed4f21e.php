<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dashboard'))]); ?>
    <div class="flex w-full flex-col gap-4 rounded-xl h-full min-h-0" style="height: calc(100vh - 120px);">
        <!-- Селектор периода -->
        <div class="flex-shrink-0">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.period-selector', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
        
        <!-- Верхняя часть - Статистика -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-3 flex-shrink-0" style="min-height: 180px;">
            <!-- Финансовые показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.financial-stats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <!-- Операционные показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.operational-stats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <!-- Складские показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.warehouse-stats', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
        
        <!-- Нижняя часть - Графики -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 flex-1 min-h-0">
            <!-- График оборота -->
            <div class="relative overflow-hidden h-full min-h-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.revenue-chart', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <!-- График продаж vs покупок -->
            <div class="relative overflow-hidden h-full min-h-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.dashboard.operations-sales-chart', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2800847645-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>

<?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/dashboard.blade.php ENDPATH**/ ?>