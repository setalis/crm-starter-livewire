<!DOCTYPE html>
<html>
<head>
    <title>Тест Chart.js</title>
    <meta charset="utf-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>
<body>
    <h1>Тест Chart.js</h1>
    <div style="width: 400px; height: 300px;">
        <canvas id="testChart"></canvas>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Chart доступен:', typeof Chart !== 'undefined');
            
            if (typeof Chart === 'undefined') {
                document.body.innerHTML += '<p style="color: red;">Chart.js не загружен!</p>';
                return;
            }
            
            const ctx = document.getElementById('testChart');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Test Data',
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
            
            document.body.innerHTML += '<p style="color: green;">Chart.js загружен и работает!</p>';
        });
    </script>
</body>
</html> 