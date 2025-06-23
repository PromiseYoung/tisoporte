@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <!-- TÍTULO PRINCIPAL -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="text-center text-success fw-bold">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard - Desempeño de Soportes y KPIs
                    </h3>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md-3 mb-4">
                    <div class="card bg-primary text-white shadow-sm rounded">
                        <div class="card-body text-center">
                            <h3 class="display-4">{{ $totalTickets }}</h3>
                            <p class="mb-0">Total Tickets</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-4">
                    <div class="card bg-success text-white shadow-sm rounded">
                        <div class="card-body text-center">
                            <h3 class="display-4">{{ $openTickets }}</h3>
                            <p class="mb-0">Tickets Abiertos</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-4">
                    <div class="card bg-danger text-white shadow-sm rounded">
                        <div class="card-body text-center">
                            <h3 class="display-4">{{ $closedTickets }}</h3>
                            <p class="mb-0">Tickets Cerrados</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-4">
                    <div class="card bg-warning text-dark shadow-sm rounded">
                        <div class="card-body text-center">
                            <h3 class="display-4">{{ number_format($closedPercentage, 1) }}%</h3>
                            <p class="mb-0">Porcentaje Cerrados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO DE PIE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center">
                            <h5><i class="fas fa-chart-pie me-2"></i>Soportes por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE SOPORTES POR ANALISTA -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center">
                            <h5><i class="fas fa-users me-2"></i>Soportes por Analista</h5>
                            <form method="GET" action="{{ route('admin.home') }}"
                                class="form-inline d-flex flex-wrap justify-content-center">
                                <div class="form-group m-2">
                                    <label for="analyst_id" class="mr-2">Analista:</label>
                                    <select name="analyst_id" id="analyst_id" class="form-control">
                                        <option value="">Seleccione</option>
                                        @foreach ($analystsList as $analyst)
                                            <option value="{{ $analyst->id }}"
                                                {{ request('analyst_id') == $analyst->id ? 'selected' : '' }}>
                                                {{ $analyst->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group m-2">
                                    <label for="start_date" class="mr-2">Inicio:</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date', $startDate) }}">
                                </div>

                                <div class="form-group m-2">
                                    <label for="end_date" class="mr-2">Fin:</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date', $endDate) }}">
                                </div>

                                <div class="form-group m-2">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Analista</th>
                                            <th>Categoría</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoriesByAnalyst as $item)
                                            <tr>
                                                <td>{{ $item['analyst'] }}</td>
                                                <td class="text-center">{{ $item['category'] }}</td>
                                                <td class="text-center">{{ $item['count'] }} Soportes</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <th colspan="2" class="text-right">Total:</th>
                                            <th class="text-center">
                                                {{ array_sum(array_column($categoriesByAnalyst, 'count')) }} Soportes</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO DE GAUGE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center">
                            <h5><i class="fas fa-tachometer-alt me-2"></i>Porcentaje de Tickets Cerrados</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="gaugeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICO DE LÍNEAS -->
            <div class="row mb-4">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card shadow-lg rounded">
                        <div class="card-header text-center">
                            <h5 class="font-weight-bold"><i class="fas fa-chart-line me-2"></i>Soportes por Mes</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <script>
        // == GLOBAL DATA (from Laravel) ==
        const labels = {!! json_encode($categories) !!};
        const data = Array.isArray({!! json_encode($data) !!}) ? {!! json_encode($data) !!} : [];
        const analysts = {!! json_encode($analysts) !!};
        const closedPercentage = {{ $closedPercentage ?? 0 }};
        const analystsData = {!! json_encode($analystsData) !!};
        const total = data.reduce((acc, curr) => acc + curr, 0);
        const percentages = data.map(val => ((val / total) * 100).toFixed(1));

        // == UTILS ==
        function shadeColor(color, percent) {
            if (!/^#[0-9A-F]{6}$/i.test(color)) return color;
            let f = parseInt(color.slice(1), 16),
                t = percent < 0 ? 0 : 255,
                p = Math.abs(percent),
                R = f >> 16,
                G = f >> 8 & 0x00FF,
                B = f & 0x0000FF;
            return "#" + (
                0x1000000 +
                (Math.round((t - R) * p) + R) * 0x10000 +
                (Math.round((t - G) * p) + G) * 0x100 +
                (Math.round((t - B) * p) + B)
            ).toString(16).slice(1);
        }

        function generateColors(count) {
            const base = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];
            return Array.from({
                length: count
            }, (_, i) => base[i % base.length]);
        }

        // == PIE CHART ==
        function renderPieChart() {
            const ctx = document.getElementById('pieChart').getContext('2d');
            const colors = generateColors(data.length);
            const hoverColors = colors.map(c => shadeColor(c, -0.1));

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: colors,
                        hoverBackgroundColor: hoverColors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                color: '#78909c'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.9)',
                            titleColor: '#546e7a',
                            bodyColor: '#455a64',
                            callbacks: {
                                label: ctx =>
                                    `${ctx.label}: ${percentages[ctx.dataIndex]}% (${ctx.raw} tickets) | Analista: ${analysts[ctx.dataIndex] ?? 'Por Asignar'}`
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            borderRadius: 6,
                            padding: 8,
                            align: 'end',
                            anchor: 'end',
                            font: {
                                weight: 'bold',
                                size: 14,
                                family: "'Poppins', sans-serif"
                            },
                            formatter: (val, ctx) => `${percentages[ctx.dataIndex]}% (${val})`
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // == GAUGE CHART ==
        function renderGaugeChart() {
            const ctx = document.getElementById('gaugeChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cerrados', 'Pendientes'],
                    datasets: [{
                        data: [closedPercentage, 100 - closedPercentage],
                        backgroundColor: ['#36b9cc', '#e74a3b'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    rotation: Math.PI,
                    circumference: Math.PI,
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            enabled: false
                        },
                        afterDatasetsDraw: chart => {
                            const ctx = chart.ctx;
                            const {
                                left,
                                right,
                                top,
                                bottom
                            } = chart.chartArea;
                            const centerX = left + (right - left) / 2;
                            const centerY = top + (bottom - top) / 2;
                            ctx.save();
                            ctx.font = 'bold 20px Arial';
                            ctx.fillStyle = '#333';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(`${closedPercentage}%`, centerX, centerY);
                            ctx.restore();
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // == LINE CHART ==
        function renderLineChart() {
            const ctx = document.getElementById('lineChart').getContext('2d');
            const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ];
            const lineColors = generateColors(analystsData.length);

            const datasets = analystsData.map((a, i) => ({
                label: a.name,
                data: a.data,
                borderColor: lineColors[i],
                backgroundColor: lineColors[i] + '33',
                borderWidth: 3,
                tension: 0.6,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                cubicInterpolationMode: 'monotone'
            }));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#333',
                                padding: 20,
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderWidth: 1,
                            borderColor: '#333',
                            padding: 10,
                            cornerRadius: 4,
                            displayColors: false
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Soportes',
                                font: {
                                    size: 16
                                }
                            },
                            ticks: {
                                stepSize: 5,
                                color: '#444',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Meses',
                                font: {
                                    size: 16
                                }
                            },
                            ticks: {
                                color: '#444',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // == INIT ==
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font = {
                size: 16,
                family: "'Poppins', 'Roboto', sans-serif",
                weight: '500',
                lineHeight: 1.4
            };
            renderPieChart();
            renderGaugeChart();
            renderLineChart();
        });
    </script>
@endsection
