@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-light">
                        <div class="card-header justify-content-between align-items-center">
                            <h4 class="mb-0 text-center">Dashboard - Desempeño de Soportes y KPIs</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <!-- Total Tickets Card -->
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="card bg-primary text-white shadow-lg rounded">
                                        <div class="card-body text-center">
                                            <h3 class="display-4">{{ $totalTickets }}</h3>
                                            <p>Total Tickets</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Open Tickets Card -->
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="card bg-success text-white shadow-lg rounded">
                                        <div class="card-body text-center">
                                            <h3 class="display-4">{{ $openTickets }}</h3>
                                            <p>Tickets Abiertos</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Closed Tickets Card -->
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="card bg-danger text-white shadow-lg rounded">
                                        <div class="card-body text-center">
                                            <h3 class="display-4">{{ $closedTickets }}</h3>
                                            <p>Tickets Cerrados</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Closed Tickets Percentage Card -->
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="card bg-warning shadow-lg text-white rounded">
                                        <div class="card-body text-center">
                                            <h3 class="display-4">{{ number_format($closedPercentage, 1) }}%</h3>
                                            <p>Porcentaje de Tickets Cerrados</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de Pie -->
                            <div class="row">
                                <div class="col-12 mb-4 border-bottom">
                                    <div class="card shadow-sm rounded mb-3">
                                        <!-- Filtro de Fechas -->
                                        <form method="GET" action="{{ route('admin.home') }}" class="row mb-3">
                                            <div class="col-12 col-sm-6 col-md-4 mb-2">
                                                <input type="date" name="start_date" class="form-control me-2"
                                                    placeholder="Fecha Inicial" value="{{ request('start_date') }}"
                                                    required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 mb-2">
                                                <input type="date" name="end_date" class="form-control me-2"
                                                    placeholder="Fecha Final" value="{{ request('end_date') }}" required>
                                            </div>
                                            <div
                                                class="col-12 col-sm-6 col-md-4 mb-2 d-flex justify-content-center justify-content-sm-start">
                                                <button type="submit"
                                                    class="btn btn-success px-4 py-2 w-100 w-lg-auto">Filtrar</button>
                                            </div>
                                        </form>
                                        <div class="card-header text-center">
                                            <h5>Grafica de Soportes Realizados</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="pieChart" class="pastel"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Soportes por Analista -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card shadow-sm rounded">
                                        <div class="card-header text-center">
                                            <h5>Tabla total de soportes realizados por analista.</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th class="col-4 text-center">Analista</th>
                                                        <th class="col-4 text-center">Categoría</th>
                                                        <th class="col-4 text-center">Total de Soportes Realizados</th>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico Gauge -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card shadow-sm rounded">
                                        <div class="card-header text-center">
                                            <h5>Porcentaje de Tickets Cerrados</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="gaugeChart" class="gauge"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            var ctxPie = document.getElementById('pieChart').getContext('2d');
            var ctxGauge = document.getElementById('gaugeChart').getContext('2d');

            // Datos para el gráfico de pie
            var labels = {!! json_encode($categories) !!};
            var data = {!! json_encode($data) !!};
            var analysts = {!! json_encode($analysts) !!};

            var total = data.reduce((acc, curr) => acc + curr, 0);
            var percentages = data.map(function(value) {
                return ((value / total) * 100).toFixed(1);
            });

            // Colores personalizados
            var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];

            // Gráfico de Pie
            var pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        hoverBackgroundColor: colors.map(color => shadeColor(color, -10)),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#333',
                                padding: 20,
                                font: {
                                    size: 14,
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderWidth: 1,
                            borderColor: '#333',
                            padding: 10,
                            cornerRadius: 4,
                            callbacks: {
                                label: function(context) {
                                    var index = context.dataIndex;
                                    var label = context.label;
                                    var value = context.raw;
                                    var percentage = percentages[index];
                                    var analyst = analysts[index];
                                    return `${label}: ${percentage}% (${value} tickets) | Analista: ${analyst}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 14
                            },
                            formatter: function(value, context) {
                                var percentage = percentages[context.dataIndex];
                                return percentage + '%';
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1600,
                        easing: 'easeOutBounce'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });

            // Gráfico Gauge para porcentaje de tickets cerrados
            var gaugeChart = new Chart(ctxGauge, {
                type: 'doughnut',
                data: {
                    labels: ['Cerrados', 'Pendientes'],
                    datasets: [{
                        data: [{{ $closedPercentage }}, 100 - {{ $closedPercentage }}],
                        backgroundColor: ['#36b9cc', '#e74a3b'],
                        borderWidth: 1
                    }]
                },
                options: {
                    rotation: Math.PI,
                    circumference: Math.PI,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });

            // Función para ajustar el brillo del color
            function shadeColor(color, percent) {
                var f = parseInt(color.slice(1), 16),
                    t = percent < 0 ? 0 : 255,
                    p = percent < 0 ? percent * -1 : percent,
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
        });
    </script>
@endsection
