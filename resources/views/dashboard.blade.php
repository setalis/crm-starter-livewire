<x-layouts.app :title="__('Dashboard')">
    <div class="flex w-full flex-col gap-4 rounded-xl" style="height: calc(100vh - 120px);">
        <!-- Селектор периода -->
        <div>
            <livewire:admin.dashboard.period-selector />
        </div>
        
        <!-- Верхняя часть - Статистика (30% высоты) -->
        <div class="grid gap-4 md:grid-cols-3" style="height: 220px; min-height: 220px;">
            <!-- Финансовые показатели -->
            <div class="relative overflow-hidden h-full">
                <livewire:admin.dashboard.financial-stats />
            </div>
            <!-- Операционные показатели -->
            <div class="relative overflow-hidden h-full">
                <livewire:admin.dashboard.operational-stats />
            </div>
            <!-- Складские показатели -->
            <div class="relative overflow-hidden h-full">
                <livewire:admin.dashboard.warehouse-stats />
            </div>
        </div>
        
        <!-- Нижняя часть - Графики (70% высоты) -->
        <div class="grid gap-4 md:grid-cols-2 flex-1" style="min-height: 0;">
            <!-- График оборота -->
            <div class="relative overflow-hidden h-full">
                <livewire:admin.dashboard.revenue-chart />
            </div>
            <!-- График продаж vs покупок -->
            <div class="relative overflow-hidden h-full">
                <livewire:admin.dashboard.operations-sales-chart />
            </div>
        </div>
    </div>
    

</x-layouts.app>

