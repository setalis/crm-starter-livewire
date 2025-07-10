<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-neutral-200 dark:border-neutral-700 flex flex-col h-full">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center space-x-2">
            <i class="bi bi-graph-up text-base text-blue-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Оборот</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            <?php echo e(\App\Helpers\Settings::formatPrice($totalRevenue)); ?>

        </div>
    </div>

    <!-- График -->
    <div class="flex-1 p-3 min-h-0">
        <canvas id="revenue-chart-canvas" class="w-full h-full"></canvas>
    </div>
</div>

<script>
function createRevenueChart() {
    console.log('REVENUE CHART: Attempting to create chart');
    const ctx = document.getElementById('revenue-chart-canvas');
    
    if (!ctx) {
        console.log('REVENUE CHART: Canvas not found, waiting...');
        return false;
    }
    
    console.log('REVENUE CHART: Canvas found, creating chart');
    try {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: <?php echo json_encode($chartData, 15, 512) ?>,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 20 }
                    } 
                },
                scales: {
                    x: { display: true, grid: { display: false } },
                    y: { 
                        type: 'linear',
                        display: true, 
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Сумма'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Средний чек'
                        }
                    }
                }
            }
        });
        console.log('REVENUE CHART: Chart created successfully');
        return true;
    } catch (error) {
        console.error('REVENUE CHART: Error creating chart:', error);
        return false;
    }
}

// Пробуем создать график сразу
if (!createRevenueChart()) {
    console.log('REVENUE CHART: Setting up observer to wait for canvas');
    
    // Если не получилось - ждем появления canvas через MutationObserver
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const canvas = document.getElementById('revenue-chart-canvas');
                if (canvas) {
                    console.log('REVENUE CHART: Canvas appeared, creating chart');
                    if (createRevenueChart()) {
                        observer.disconnect();
                    }
                }
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Страховка - отключаем observer через 5 секунд
    setTimeout(() => {
        observer.disconnect();
        console.log('REVENUE CHART: Observer timeout');
    }, 5000);
}
</script> <?php /**PATH H:\OSPanel\home\crm-starter.kit\resources\views/livewire/admin/dashboard/revenue-chart.blade.php ENDPATH**/ ?>