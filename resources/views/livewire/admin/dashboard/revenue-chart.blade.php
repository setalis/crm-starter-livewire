<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-graph-up text-base text-blue-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Оборот</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ \App\Helpers\Settings::formatPrice($totalRevenue) }}
        </div>
    </div>

    <!-- График -->
    <div class="flex-1 p-3" style="min-height: 280px;">
        <canvas id="revenueChart" width="400" height="280"></canvas>
        <!-- Отладка -->
        <div class="text-xs text-gray-500 mt-2">
            Период: {{ ($periodData = $this->getPeriodDates()) ? $periodData['label'] : 'не задан' }} | 
            Меток: {{ count($chartData['labels'] ?? []) }} | 
            Оборот: {{ $totalRevenue }}
        </div>
    </div>
</div>

<script>
let revenueChartInstance = null;

// Функция создания/обновления графика
function createRevenueChart(chartData) {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js не загружен');
        return;
    }
    
    const ctx = document.getElementById('revenueChart');
    if (!ctx) {
        console.error('Canvas не найден');
        return;
    }

    // Уничтожаем старый график
    if (revenueChartInstance) {
        revenueChartInstance.destroy();
    }

    // Если данные не переданы - используем статические
    if (!chartData) {
        chartData = @json($chartData);
    }
    
    // Если нет данных - показываем пустой график
    if (!chartData || !chartData.labels || chartData.labels.length === 0) {
        const emptyData = {
            labels: ['Нет данных за период'],
            datasets: [{
                label: 'Оборот',
                data: [0],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true
            }]
        };
        
        revenueChartInstance = new Chart(ctx, {
            type: 'line',
            data: emptyData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        console.log('График оборота: показан пустой график');
        return;
    }

    // Создаем график с реальными данными
    revenueChartInstance = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Оборот: ' + new Intl.NumberFormat('ru-RU', {
                                style: 'currency',
                                currency: 'UAH'
                            }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: { display: false }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('ru-RU', {
                                style: 'currency',
                                currency: 'UAH',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            elements: {
                point: { radius: 3, hoverRadius: 6 }
            }
        }
    });
    
    console.log('График оборота создан/обновлен с данными:', chartData);
}

// Глобальная функция для обновления графика
window.tryInitRevenueChart = function() {
    setTimeout(createRevenueChart, 100);
};

// Начальная инициализация
setTimeout(createRevenueChart, 100);

// Обработчик событий
document.addEventListener('livewire:init', () => {
    console.log('RevenueChart: Livewire инициализирован, подключаем обработчики');
    
    // Обработчик обновления данных графика
    Livewire.on('revenue-chart-data-updated', (event) => {
        console.log('RevenueChart: ПОЛУЧЕНО СОБЫТИЕ revenue-chart-data-updated', event);
        
        // Данные приходят как массив, берем первый элемент
        const data = Array.isArray(event) ? event[0] : event;
        console.log('Обработанные данные:', data);
        console.log('Новые данные для графика:', data.chartData);
        console.log('Новый оборот:', data.totalRevenue);
        
        // Сразу обновляем график с новыми данными
        createRevenueChart(data.chartData);
    });
    
    // Старый обработчик для совместимости
    Livewire.on('period-changed', (event) => {
        console.log('RevenueChart: получено событие period-changed (совместимость)', event);
    });
});
</script> 