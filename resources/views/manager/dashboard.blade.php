<x-layouts.app>
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Быстрые действия -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Быстрая покупка</h3>
                </div>
                <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <i class="bi bi-plus text-green-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('manager.operations.create', 'purchase') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                    Создать покупку
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Быстрая продажа</h3>
                </div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <i class="bi bi-arrow-up-right text-blue-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('manager.operations.create', 'sale') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                    Создать продажу
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Склад</h3>
                </div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <i class="bi bi-boxes text-purple-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('manager.warehouse.stock.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700">
                    Просмотреть склад
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Касса</h3>
                </div>
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                    <i class="bi bi-wallet2 text-yellow-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('manager.cash-register.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-yellow-600 hover:bg-yellow-700">
                    Управление кассой
                </a>
            </div>
        </div>
    </div>

    <!-- Виджеты -->
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!-- Виджет операций -->
        <div class="relative aspect-video overflow-hidden">
            <livewire:admin.dashboard.operations-widget />
        </div>
        <!-- Виджет элементов -->
        <div class="relative aspect-video overflow-hidden">
            <livewire:admin.dashboard.elements-widget />
        </div>
        <!-- Виджет товаров -->
        <div class="relative aspect-video overflow-hidden">
            <livewire:admin.dashboard.products-widget />
        </div>
    </div>

    <!-- Сетка товаров -->
    <div class="relative h-full flex-1 overflow-hidden">
        <livewire:admin.dashboard.products-grid />
    </div>
</div>
</x-layouts.app> 