<!DOCTYPE html>
<html>

<head>
    <title>Laravel Charts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #ccc;
        }

        #chartjs-tooltip {
            position: absolute;
            background: rgba(255, 255, 255, 1);
            color: rgb(0, 0, 0);
            display: inline-block;
            border-radius: 3px;
            pointer-events: none;
            top: auto;
            bottom: auto;
            transform: translate(-50%, -100%);
            transition: opacity 0.1s ease;
            opacity: 0;
            padding: 5px;
            z-index: 1000;
        }

        .chartpadd {
            background-color: #ffffff;
            padding: 1em;
            margin: 1em;
        }

        #slider {
            position: relative;
            width: 250px;
        }

        #slider .carousel {
            width: 250px;
            height: 250px;
        }

        #slider .carousel-item {
            height: auto;
        }

        #slider .carousel-indicators li {
            width: 10px;
            height: 10px;
            border-radius: 100%;
            background-color: #6a6a6a;
        }

        #slider .carousel-indicators {
            bottom: -15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 d-flex">
        <canvas id="myChart" width="250" height="250" class="chartpadd"></canvas>
        <div id="slider" class="carousel slide chartpadd" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#slider" data-slide-to="0" class="active"></li>
                <li data-target="#slider" data-slide-to="1"></li>
                <li data-target="#slider" data-slide-to="2"></li>
            </ol>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <canvas id="myChart1" width="210" height="210"></canvas>
                </div>
                <div class="carousel-item">
                    <canvas id="myChart1" width="210" height="210"></canvas>
                </div>
                <div class="carousel-item">
                    <canvas id="myChart1" width="210" height="210"></canvas>
                </div>

            </div>
        </div>

        <div id="chartjs-tooltip">
            <table></table>
        </div>
        <canvas id="myChart2" width="250" height="250" class="chartpadd"></canvas>
        <canvas id="myChart3" width="250" height="250" class="chartpadd"></canvas>
    </div>

    <script>
        // Helper function to get or create a tooltip


        function getOrCreateTooltip(chart) {
            let tooltipEl = document.getElementById('chartjs-tooltip');
            if (!tooltipEl) {
                tooltipEl = document.createElement('div');
                tooltipEl.id = 'chartjs-tooltip';
                tooltipEl.innerHTML = '<table></table>';
                document.body.appendChild(tooltipEl);
            }
            return tooltipEl;
        }

        const externalTooltipHandler = (context) => {
            // Tooltip Element
            const {
                chart,
                tooltip
            } = context;
            const tooltipEl = getOrCreateTooltip(chart);

            // Hide if no tooltip
            if (tooltip.opacity === 0) {
                tooltipEl.style.opacity = 0;
                return;
            }

            // Set Text
            if (tooltip.body) {
                const titleLines = tooltip.title || [];
                const bodyLines = tooltip.body.map(b => b.lines.join(''));

                const tableHead = document.createElement('thead');
                const tableBody = document.createElement('tbody');

                // Add title and body lines combined in one row
                bodyLines.forEach((body, i) => {
                    const tr = document.createElement('tr');
                    tr.style.backgroundColor = 'inherit';
                    tr.style.borderWidth = 0;

                    const td = document.createElement('td');
                    td.style.borderWidth = 0;

                    const text = document.createTextNode(`${titleLines[i]}: ${body}`);

                    td.appendChild(text);
                    tr.appendChild(td);
                    tableBody.appendChild(tr);
                });

                const tableRoot = tooltipEl.querySelector('table');

                // Remove old children
                while (tableRoot.firstChild) {
                    tableRoot.firstChild.remove();
                }

                // Add new children
                tableRoot.appendChild(tableHead);
                tableRoot.appendChild(tableBody);
            }

            const {
                offsetLeft: positionX,
                offsetTop: positionY
            } = chart.canvas;

            // Display, position, and set styles for font
            tooltipEl.style.opacity = 1;
            tooltipEl.style.left = positionX + tooltip.caretX + 'px';
            tooltipEl.style.top = positionY + tooltip.caretY + 'px';
            tooltipEl.style.font = tooltip.options.bodyFont.string;
            tooltipEl.style.padding = tooltip.options.padding + 'px ' + tooltip.options.padding + 'px';
        };

        // Helper function to create or get the tooltip element
        



        const backgroundColorPlugin = {
            id: 'backgroundColor',
            beforeDraw: (chart) => {
                const ctx = chart.ctx;
                const {
                    top,
                    left,
                    width,
                    height
                } = chart.chartArea;
                ctx.save();
                ctx.fillStyle = 'rgba(255, 255, 255, 0.2)'; // Change this to your desired background color
                ctx.fillRect(left, top, width, height);
                ctx.restore();
            }
        };

        Chart.register(backgroundColorPlugin);

        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Line Chart
            const chartAreaBorder2 = {
                id: 'chartAreaBorder2',
                beforeDatasetsDraw(chart, args, options) {
                    const {
                        ctx,
                        chartArea: {
                            left,
                            top,
                            bottom,
                            right,
                            width,
                            height
                        }
                    } = chart;
                    ctx.save();
                    ctx.fillStyle = 'white';
                    ctx.fillRect(left, top, width, height);
                    ctx.restore();
                }
            };
            var ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: 'Option 1',
                        data: [300, 325, 320, 200, 90, 200],
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        fill: true
                    }, {
                        label: 'Option 2',
                        data: [450, 475, 450, 380, 200, 300],
                        backgroundColor: 'rgba(137, 8, 5, 1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        backgroundColor: backgroundColorPlugin,
                        legend: {
                            position: 'bottom',
                            align: 'start',
                            labels: {
                                usePointStyle: true,
                            },
                        },
                        title: {
                            display: true,
                            text: 'Chart Title',
                            font: {
                                size: 20,
                            },
                        },
                        subtitle: {
                            display: true,
                            text: '15 April - 21 April',
                            color: 'grey',
                            font: {
                                size: 10,
                                family: 'tahoma',
                                weight: 'normal',
                                style: 'italic'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 500
                        },
                        x: {
                            position: 'bottom'
                        }
                    },
                    elements: {
                        point: {
                            radius: 0.5,
                            hoverRadius: 3,
                            hoverBackgroundColor: 'red'
                        }
                    }
                },
                plugins: [chartAreaBorder2]
            });

            // Chart 2: Doughnut Chart
            const chartAreaBorder1 = {
                id: 'chartAreaBorder1',
                beforeDatasetsDraw(chart, args, options) {
                    const {
                        ctx,
                        chartArea: {
                            left,
                            top,
                            bottom,
                            right,
                            width,
                            height
                        }
                    } = chart;
                    ctx.save();
                    ctx.fillStyle = 'white';
                    ctx.fillRect(left, top, width, height);
                    ctx.restore();
                }
            };
            var ctx1 = document.getElementById('myChart1').getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        label: "my set",
                        data: [700, 200],
                        backgroundColor: ['rgb(54, 162, 235)', 'rgb(174, 174, 174, 1)'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '80%',
                    plugins: {
                        title: {
                            display: true,
                            text: 'Chart Title',
                            font: {
                                size: 20,
                            },
                        },
                        subtitle: {
                            display: true,
                            text: 'Here goes number 90 of total 100',
                            color: 'grey',
                            font: {
                                size: 10,
                                family: 'tahoma',
                                weight: 'normal',
                                style: 'italic'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    }
                },
                plugins: [chartAreaBorder1]
            });

            // Chart 3: Line Chart with Gradient
            const chartAreaBorder = {
                id: 'chartAreaBorder',
                beforeDatasetsDraw(chart, args, options) {
                    const {
                        ctx,
                        chartArea: {
                            left,
                            top,
                            bottom,
                            right,
                            width,
                            height
                        }
                    } = chart;
                    ctx.save();
                    ctx.fillStyle = 'white';
                    ctx.fillRect(left, top, width, height);
                    ctx.restore();
                }
            };
            var ctx2 = document.getElementById('myChart2').getContext('2d');
            var gradient = ctx2.createLinearGradient(0, 0, 0, ctx2.canvas.height);
            gradient.addColorStop(0, 'rgba(210, 0, 0, 0.5)');
            gradient.addColorStop(1, 'rgba(225, 225, 225, 1)');

            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
                    datasets: [{
                        label: 'Option 1',
                        data: [300, 325, 320, 200, 90, 200, 300],
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.5,
                        borderWidth: 0,
                        borderColor: 'transparent'
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {

                        legend: {
                            display: false,
                            position: 'bottom',
                            align: 'start',
                            labels: {
                                usePointStyle: true,
                            },
                        },
                        title: {
                            display: true,
                            text: 'Chart Title',
                            font: {
                                size: 20,
                            },
                        },
                        subtitle: {
                            display: true,
                            text: '15 April - 21 April',
                            color: 'grey',
                            font: {
                                size: 10,
                                family: 'tahoma',
                                weight: 'normal',
                                style: 'italic'
                            },
                            padding: {
                                bottom: 10
                            }
                        },
                        tooltip: {
                            enabled: false,
                            external: externalTooltipHandler
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 500,
                            border: {
                                display: false,
                            },
                            grid: {
                                drawTicks: false,
                                color: (ctx) => {
                                    const value = ctx?.tick?.value;
                                    console.log(value)
                                    return typeof value !== 'undefined' && value === 0 ? 'transparent' :
                                        'rgba(102, 102, 102, 0.2)';
                                }
                            },
                        },
                        x: {
                            position: 'bottom',
                            border: {
                                display: false,
                            },
                            grid: {
                                drawTicks: false,
                                color: (ctx) => {
                                    const value = ctx?.tick?.value;
                                    console.log(value)
                                    return typeof value !== 'undefined' && value === 0 ? 'transparent' :
                                        'rgba(102, 102, 102, 0.2)';
                                },
                            },
                        }
                    },
                    elements: {
                        point: {
                            radius: 0.5,
                            hoverRadius: 4,
                            hoverBackgroundColor: 'red'
                        }
                    }
                },
                plugins: [chartAreaBorder]
            });


        });
    </script>

    <script>
        // Chart 4: Line Chart with Gradient
        const ctx = document.getElementById('myChart3').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        gradient.addColorStop(0, 'rgba(255, 99, 132, 0.2)');
        gradient.addColorStop(1, 'rgba(54, 162, 235, 0.2)');

        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                    type: 'line',
                    label: 'Line',
                    data: [2, 11, 5, 8, 3, 7],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.5,
                    fill: false
                },
                {
                    type: 'bar',
                    label: 'Bar',
                    data: [20, 20, 20, 20, 20, 20],
                    backgroundColor: 'rgba(241, 242, 251, 1)'
                }
            ]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: false,
                plugins: {
                    legend: {

                        position: 'bottom',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                        },
                    },
                    tooltip: {
                        enabled: false,
                        external: (context) => {
                            const tooltipEl = document.getElementById('chartjs-tooltip');

                            // Clear previous content
                            tooltipEl.innerHTML = '';

                            // Build tooltip content
                            if (context.tooltip.body) {
                                const titleLines = context.tooltip.title || [];
                                const bodyLines = context.tooltip.body.map(b => b.lines);

                                const tableContent = `<thead>
                            <tr>
                                <th style="text-align: left;">${titleLines}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bodyLines.map(body => `
                                        <tr>
                                            <td>${body}</td>
                                        </tr>
                                    `).join('')}
                        </tbody>`;

                                const table = document.createElement('table');
                                table.innerHTML = tableContent;
                                tooltipEl.appendChild(table);
                            }

                            if (tooltipEl) {
                                tooltipEl.style.opacity = 1;
                                tooltipEl.style.left = context.chart.canvas.offsetLeft + context.tooltip.caretX +
                                    'px';
                                tooltipEl.style.top = context.chart.canvas.offsetTop + context.tooltip.caretY +
                                    'px';
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 0.5,
                        hoverRadius: 5,
                        hoverBackgroundColor: 'blue'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const myChart = new Chart(ctx, config);
    </script>
    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js'></script>
</body>

</html>
