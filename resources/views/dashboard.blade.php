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
        <div class="relative h-full flex-1 overflow-hidden">
            <livewire:admin.dashboard.products-grid />
        </div>
    </div>
</x-layouts.app>
