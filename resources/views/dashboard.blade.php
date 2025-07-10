<x-layouts.app :title="__('Dashboard')">
    <div class="flex w-full flex-col gap-4 rounded-xl h-full min-h-0" style="height: calc(100vh - 120px);">
        <!-- Селектор периода -->
        <div class="flex-shrink-0">
            <livewire:admin.dashboard.period-selector />
        </div>
        
        <!-- Верхняя часть - Статистика -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-3 flex-shrink-0" style="min-height: 180px;">
            <!-- Финансовые показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <livewire:admin.dashboard.financial-stats />
            </div>
            <!-- Операционные показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <livewire:admin.dashboard.operational-stats />
            </div>
            <!-- Складские показатели -->
            <div class="relative overflow-hidden h-full min-h-0">
                <livewire:admin.dashboard.warehouse-stats />
            </div>
        </div>
        
        <!-- Нижняя часть - Графики -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 flex-1 min-h-0">
            <!-- График оборота -->
            <div class="relative overflow-hidden h-full min-h-0">
                <livewire:admin.dashboard.revenue-chart />
            </div>
            <!-- График продаж vs покупок -->
            <div class="relative overflow-hidden h-full min-h-0">
                <livewire:admin.dashboard.operations-sales-chart />
            </div>
        </div>
    </div>
</x-layouts.app>

