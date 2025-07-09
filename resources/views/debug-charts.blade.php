<!DOCTYPE html>
<html>
<head>
    <title>Отладка графиков</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Отладка графиков CRM</h1>
        
        <!-- Проверка Chart.js -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Статус Chart.js</h2>
            <div id="chartjs-status"></div>
        </div>
        
        <!-- Простой тест графика -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Простой тест графика</h2>
            <div style="height: 300px;">
                <canvas id="simpleChart"></canvas>
            </div>
        </div>
        
        <!-- Тест Livewire компонентов -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Тест Livewire компонентов</h2>
            
            <!-- График оборота -->
            <div class="mb-4">
                <h3 class="text-lg font-medium mb-2">График оборота</h3>
                <div style="height: 300px;">
                    <livewire:admin.dashboard.revenue-chart />
                </div>
            </div>
            
            <!-- График операций -->
            <div class="mb-4">
                <h3 class="text-lg font-medium mb-2">График операций</h3>
                <div style="height: 300px;">
                    <livewire:admin.dashboard.operations-sales-chart />
                </div>
            </div>
        </div>
        
        <!-- Консольные логи -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Консольные логи</h2>
            <div id="console-logs" class="bg-gray-100 p-4 rounded text-sm font-mono h-40 overflow-y-auto"></div>
        </div>
    </div>

    <script>
        // Перехватываем console.log
        const originalConsoleLog = console.log;
        const originalConsoleError = console.error;
        const logContainer = document.getElementById('console-logs');
        
        function addLogMessage(message, type = 'log') {
            const div = document.createElement('div');
            div.className = type === 'error' ? 'text-red-600' : 'text-gray-700';
            div.textContent = new Date().toLocaleTimeString() + ': ' + message;
            logContainer.appendChild(div);
            logContainer.scrollTop = logContainer.scrollHeight;
        }
        
        console.log = function(...args) {
            originalConsoleLog.apply(console, args);
            addLogMessage(args.join(' '), 'log');
        };
        
        console.error = function(...args) {
            originalConsoleError.apply(console, args);
            addLogMessage(args.join(' '), 'error');
        };

        // Проверяем статус Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const statusEl = document.getElementById('chartjs-status');
            
            if (typeof Chart !== 'undefined') {
                statusEl.innerHTML = '<div class="text-green-600">✓ Chart.js загружен (версия: ' + Chart.version + ')</div>';
                console.log('Chart.js успешно загружен');
                
                // Создаем простой тестовый график
                const ctx = document.getElementById('simpleChart');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн'],
                        datasets: [{
                            label: 'Тестовые данные',
                            data: [12, 19, 3, 5, 2, 3],
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                
            } else {
                statusEl.innerHTML = '<div class="text-red-600">✗ Chart.js не загружен</div>';
                console.error('Chart.js не загружен');
            }
            
            // Проверяем Livewire
            if (typeof Livewire !== 'undefined') {
                console.log('Livewire доступен');
            } else {
                console.error('Livewire не доступен');
            }
        });
    </script>
    
    @livewireScripts
</body>
</html> 