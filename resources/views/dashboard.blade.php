<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Виджет товаров -->
            <div class="relative aspect-video overflow-hidden">
                <livewire:admin.dashboard.products-widget />
            </div>
            <!-- Виджет операций -->
            <div class="relative aspect-video overflow-hidden">
                <livewire:admin.dashboard.operations-widget />
            </div>
            <!-- Виджет элементов -->
            <div class="relative aspect-video overflow-hidden">
                <livewire:admin.dashboard.elements-widget />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="h-full flex items-center justify-center bg-white dark:bg-zinc-800 rounded-xl">
                <div class="text-center">
                    <i class="bi bi-graph-up text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Аналитика</h3>
                    <p class="text-gray-500 dark:text-gray-400">Здесь будут графики и отчеты</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
