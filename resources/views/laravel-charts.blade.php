<!DOCTYPE html>
<html>
<head>
    <title>Laravel Charts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Laravel Charts</h1>
        <canvas id="chart1"></canvas>
    </div>
    <script>
        const ctx1 = document.getElementById('chart1').getContext('2d');
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Point 1', 'Point 2'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [12, 19],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                onClick: (e, activeEls) => {
                    if (activeEls.length > 0) {
                        const index = activeEls[0].index;
                        const label = chart1.data.labels[index];
                        alert(`You clicked on ${label}`);
                    }
                }
            }
        });
    </script>
</body>
</html>
