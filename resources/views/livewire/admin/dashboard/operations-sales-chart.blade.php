<div class="h-full flex flex-col bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
    <!-- Заголовок -->
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <i class="bi bi-arrow-left-right text-base text-green-600"></i>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Продажи vs Покупки</h3>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Продаж: {{ $salesCount }} | Покупок: {{ $purchasesCount }}
        </div>
    </div>

    <!-- График -->
    <div class="flex-1 p-3" style="min-height: 280px;">
        <canvas id="operationsSalesChart" width="400" height="280"></canvas>
        <!-- Отладка -->
        <div class="text-xs text-gray-500 mt-2">
            Период: {{ ($periodData = $this->getPeriodDates()) ? $periodData['label'] : 'не задан' }} | 
            Маржа: {{ number_format($profitMargin, 1) }}% | 
            Прибыль: {{ \App\Helpers\Settings::formatPrice($salesAmount - $purchasesAmount) }}
        </div>
    </div>
</div>

<script>
let operationsSalesChartInstance = null;

// Функция создания/обновления графика
function createOperationsSalesChart(chartData) {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js не загружен');
        return;
    }
    
    const ctx = document.getElementById('operationsSalesChart');
    if (!ctx) {
        console.error('Canvas не найден');
        return;
    }

    // Уничтожаем старый график
    if (operationsSalesChartInstance) {
        operationsSalesChartInstance.destroy();
    }

    // Если данные не переданы - используем статические
    if (!chartData) {
        chartData = @json($chartData);
    }
    
    // Если нет данных - показываем пустой график
    if (!chartData || !chartData.labels || chartData.labels.length === 0) {
        const emptyData = {
            labels: ['Нет данных за период'],
            datasets: [
                {
                    label: 'Продажи',
                    data: [0],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: false
                },
                {
                    label: 'Покупки',
                    data: [0],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: false
                }
            ]
        };
        
        operationsSalesChartInstance = new Chart(ctx, {
            type: 'line',
            data: emptyData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });
        console.log('График операций: показан пустой график');
        return;
    }

    // Создаем график с реальными данными
    operationsSalesChartInstance = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: { usePointStyle: true }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = new Intl.NumberFormat('ru-RU', {
                                style: 'currency',
                                currency: 'UAH'
                            }).format(context.parsed.y);
                            return label + ': ' + value;
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
    
    console.log('График операций создан/обновлен с данными:', chartData);
}

// Глобальная функция для обновления графика
window.tryInitOperationsSalesChart = function() {
    setTimeout(createOperationsSalesChart, 100);
};

// Начальная инициализация
setTimeout(createOperationsSalesChart, 100);

// Обработчик событий
document.addEventListener('livewire:init', () => {
    console.log('OperationsSalesChart: Livewire инициализирован, подключаем обработчики');
    
    // Обработчик обновления данных графика
    Livewire.on('operations-sales-chart-data-updated', (event) => {
        console.log('OperationsSalesChart: ПОЛУЧЕНО СОБЫТИЕ operations-sales-chart-data-updated', event);
        
        // Данные приходят как массив, берем первый элемент
        const data = Array.isArray(event) ? event[0] : event;
        console.log('Обработанные данные:', data);
        console.log('Новые данные для графика:', data.chartData);
        
        // Сразу обновляем график с новыми данными
        createOperationsSalesChart(data.chartData);
    });
    
    // Старый обработчик для совместимости
    Livewire.on('period-changed', (event) => {
        console.log('OperationsSalesChart: получено событие period-changed (совместимость)', event);
    });
});
</script> 