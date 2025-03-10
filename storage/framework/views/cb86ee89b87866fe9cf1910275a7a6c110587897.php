<?php $__env->startSection('content'); ?>
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
                <?php
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
                ?>
                <?php $__currentLoopData = $kpiCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card text-white shadow-lg rounded <?php echo e($card['class']); ?>">
                            <div class="card-body text-center">
                                <h3 class="display-4"><?php echo e($card['value']); ?></h3>
                                <p><?php echo e($card['title']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <form method="GET" action="<?php echo e(route('admin.home')); ?>" class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="analyst_id" class="mr-2">Filtrar por Analista:</label>
                                    <select name="analyst_id" id="analyst_id" class="form-control">
                                        <option value="">Seleccione un Analista</option>
                                        <?php $__currentLoopData = $analystsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analyst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($analyst->id); ?>"
                                                <?php echo e(request('analyst_id') == $analyst->id ? 'selected' : ''); ?>>
                                                <?php echo e($analyst->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group mr-2">
                                    <label for="start_date" class="mr-2">Fecha de Inicio:</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="<?php echo e(request('start_date', $startDate)); ?>">
                                </div>

                                <div class="form-group mr-2">
                                    <label for="end_date" class="mr-2">Fecha de Fin:</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="<?php echo e(request('end_date', $endDate)); ?>">
                                </div>

                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </form>
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
                                    <?php $__currentLoopData = $categoriesByAnalyst; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['analyst']); ?></td>
                                            <td class="text-center"><?php echo e($item['category']); ?></td>
                                            <td class="text-center"><?php echo e($item['count']); ?> Soportes</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <th colspan="2" class="text-right">Total de Soportes:</th>
                                        <th class="text-center">
                                            <?php echo e(array_sum(array_column($categoriesByAnalyst, 'count'))); ?> Soportes</th>
                                    </tr>
                                </tfoot>
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
            <div class="row mb-4">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card shadow-lg rounded">
                        <div class="card-header text-center">
                            <h5 class="font-weight-bold">Gráfico Lineal - Soportes por Mes</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // === CONFIGURACIÓN GENERAL ===
            Chart.defaults.font = {
                size: 16,
                family: "'Poppins', 'Roboto', sans-serif",
                weight: '500',
                lineHeight: 1.4
            };
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(255, 255, 255, 0.9)'; // Blanco suave
            Chart.defaults.plugins.tooltip.titleColor = '#546e7a'; // Gris azulado
            Chart.defaults.plugins.tooltip.bodyColor = '#455a64'; // Azul grisáceo
            Chart.defaults.plugins.legend.labels.color = '#78909c'; // Gris pastel

            // === DATOS DINÁMICOS DESDE BACKEND ===
            var labels = <?php echo json_encode($categories); ?>;
            var data = <?php echo json_encode($data); ?>;
            var analysts = <?php echo json_encode($analysts); ?>;
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
                                    if (!context.dataIndex) return '';
                                    var index = context.dataIndex;
                                    var label = context.label || '';
                                    var value = context.raw || 0;
                                    var percentage = percentages[index] || 0;
                                    var analyst = analysts[index] || 'Desconocido';
                                    return `${label}: ${percentage}% (${value} tickets) | Analista TI: ${analyst}`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff', // Color del texto
                            backgroundColor: 'rgba(0, 0, 0, 0.7)', // Fondo semi-transparente para mejor contraste
                            borderRadius: 6, // Bordes redondeados
                            padding: 8, // Espacio alrededor del texto
                            align: 'end', // Alinear las etiquetas al final de la barra/punto
                            anchor: 'end', // Fijar la etiqueta en la punta del elemento
                            font: {
                                weight: 'bold',
                                size: 14,
                                family: "'Poppins', sans-serif" // Tipografía más moderna y profesional
                            },
                            formatter: function(value, context) {
                                var percentage = percentages[context.dataIndex];
                                return percentage + '% (' + value + ')'; // Mostrar % y valor real
                            }
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
            // === GRÁFICO GAUGE PARA TICKETS CERRADOS ===
            var ctxGauge = document.getElementById('gaugeChart').getContext('2d');
            new Chart(ctxGauge, {
                type: 'doughnut',
                data: {
                    labels: ['Cerrados', 'Pendientes'],
                    datasets: [{
                        data: [<?php echo e($closedPercentage ?? 0); ?>, 100 - <?php echo e($closedPercentage ?? 0); ?>],
                        backgroundColor: ['#36b9cc', '#e74a3b'],
                        borderWidth: 3, // Aumentar el grosor del borde
                        borderColor: '#fff', // Color de borde blanco para mejorar el contraste
                    }]
                },
                options: {
                    rotation: Math.PI, // Para mostrar la gráfica a la mitad de la circunferencia
                    circumference: Math.PI, // Mostrar solo la mitad
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%', // Dejar un centro vacío más grande para mostrar el porcentaje
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14, // Aumentar el tamaño de la fuente en la leyenda
                                    weight: 'bold' // Hacer la fuente en negrita
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            enabled: false // Desactivar el tooltip por defecto
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1000, // Duración de la animación
                        easing: 'easeOutQuart' // Efecto de suavizado
                    },
                    // Usar el método afterDatasetsDraw para dibujar texto
                    plugins: {
                        afterDatasetsDraw: function(chart) {
                            var ctx = chart.ctx;
                            var centerX = chart.chartArea.left + (chart.chartArea.right - chart
                                .chartArea.left) / 2;
                            var centerY = chart.chartArea.top + (chart.chartArea.bottom - chart
                                .chartArea.top) / 2;

                            ctx.save();
                            ctx.font = 'bold 20px Arial';
                            ctx.fillStyle = '#333';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText('<?php echo e($closedPercentage ?? 0); ?>%', centerX, centerY);
                            ctx.restore();
                        }
                    }
                }
            });
            // === FUNCIÓN PARA AJUSTAR EL BRILLO DE LOS COLORES ===
            function shadeColor(color, percent) {
                if (!/^#[0-9A-F]{6}$/i.test(color)) return color; // Si no es un color válido, devolver sin cambios
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

            var ctxLine = document.getElementById('lineChart').getContext('2d');

            // Datos de meses
            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ];

            // Datos dinámicos desde el backend
            var analystsData = <?php echo json_encode($analystsData); ?>;

            // Paleta de colores mejorada
            var lineColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6c757d'];

            // Crear los datasets para cada analista
            var datasets = analystsData.map((analyst, index) => {
                return {
                    label: analyst.name,
                    data: analyst.data,
                    borderColor: lineColors[index % lineColors.length],
                    backgroundColor: lineColors[index % lineColors.length] +
                        '33', // Color con transparencia
                    borderWidth: 3,
                    borderCapStyle: 'round', // Añadir un borde redondeado a las líneas
                    tension: 0.6, // Suaviza las líneas
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    cubicInterpolationMode: 'monotone', // Mejora la suavidad en las curvas
                };
            });

            // Configurar el gráfico de líneas
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: datasets
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderWidth: 1,
                            borderColor: '#333',
                            padding: 10,
                            cornerRadius: 4,
                            displayColors: false // Desactivar los colores de los cuadros en el tooltip
                        }
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
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/home.blade.php ENDPATH**/ ?>