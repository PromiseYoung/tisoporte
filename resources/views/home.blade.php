@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <!-- TÍTULO PRINCIPAL -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="text-center text-success fw-bold">Dashboard - Desempeño de Soportes y KPIs</h3>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row mb-4">
                @php
                    $kpiCards = [
                        ['title' => 'Total Tickets', 'value' => $totalTickets, 'class' => 'bg-primary'],
                        ['title' => 'Tickets Abiertos', 'value' => $openTickets, 'class' => 'bg-success'],
                        ['title' => 'Tickets Cerrados', 'value' => $closedTickets, 'class' => 'bg-danger'],
                        [
                            'title' => 'Porcentaje de Tickets Cerrados',
                            'value' => number_format($closedPercentage, 1) . '%',
                            'class' => 'bg-warning',
                        ],
                    ];
                @endphp
                @foreach ($kpiCards as $card)
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card text-white shadow-lg rounded {{ $card['class'] }}">
                            <div class="card-body text-center">
                                <h3 class="display-4">{{ $card['value'] }}</h3>
                                <p>{{ $card['title'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FILTRO DE FECHAS -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('admin.home') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="date" name="start_date" class="form-control" placeholder="Fecha Inicial"
                                value="{{ request('start_date') }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="end_date" class="form-control" placeholder="Fecha Final"
                                value="{{ request('end_date') }}" required>
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-success">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GRÁFICO DE PIE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center">
                            <h5>Gráfica de Soportes Realizados por Categoría</h5>
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
                            <h5>Total de Soportes Realizados por Analista</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead class="table-success">
                                    <tr>
                                        <th>Analista</th>
                                        <th>Categoría</th>
                                        <th>Total de Soportes</th>
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

            <!-- GRÁFICO DE GAUGE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center">
                            <h5>Porcentaje de Tickets Cerrados</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="gaugeChart"></canvas>
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

            // === CONFIGURACIÓN GENERAL ===
            Chart.defaults.font.size = 14;
            Chart.defaults.font.family = "'Roboto', sans-serif";
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            Chart.defaults.plugins.tooltip.titleColor = '#fff';
            Chart.defaults.plugins.tooltip.bodyColor = '#fff';
            Chart.defaults.plugins.legend.labels.color = '#444';

            // === DATOS DINÁMICOS DESDE BACKEND ===
            var labels = {!! json_encode($categories) !!};
            var data = {!! json_encode($data) !!};
            var analysts = {!! json_encode($analysts) !!};
            var total = data.reduce((acc, curr) => acc + curr, 0);
            var percentages = data.map(function(value) {
                return ((value / total) * 100).toFixed(1);
            });

            // === COLORES DINÁMICOS ===
            var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];
            var hoverColors = colors.map(color => shadeColor(color, -10));

            // === GRÁFICO DE PIE ===
            var ctxPie = document.getElementById('pieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
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
                                padding: 20
                            }
                        },
                        tooltip: {
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
                        duration: 1500,
                        easing: 'easeOutBounce'
                    }
                }
            });

            // === GRÁFICO GAUGE PARA TICKETS CERRADOS ===
            var ctxGauge = document.getElementById('gaugeChart').getContext('2d');
            new Chart(ctxGauge, {
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
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });

            // === FUNCIÓN PARA AJUSTAR EL BRILLO DE LOS COLORES ===
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
