    @php
        use Carbon\Carbon;
    @endphp

    @extends('layouts.app')

    @section('content')

    <div class="container">

        <div class="row">

            <div class="col-lg-3">
                <h1>Estadísticas</h1>

                <div class="row">
                    <div class="col-md-6">
                        <select id="monthSelect" class="form-control">
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>
                                    {{ Carbon::createFromFormat('!m', $month)->locale('es')->monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select id="yearSelect" class="form-control">
                            @foreach(range(now()->year - 5, now()->year + 5) as $year)
                                <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                        <!-- Botón de exportación -->
            <div class="col-lg-12">
            <!-- Botón de exportación -->
                <div class="row mt-1">
                    <div class="col-md-12 text-end"> <!-- Cambié 'text-start' por 'text-end' -->
                        <button type="button" id="exportButton" class="btn btn-primary">
                            <i class="fas fa-file-excel me-2"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
                <!-- <p class="col-12 col-md-auto text-center text-md-start mt-3" style="font-size: 0.9rem;">
                Elige un mes y un año para visualizar los diferentes períodos disponibles.
                </p> -->
            </div>

            <div class="col-lg-3 h-100" >
                <div class="card text-center mb-3" style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); background-color: rgba(214, 228, 240, 0.6); margin:1% ; padding:1%" >
                    <div class="card-body" style="background-color: white">
                    <canvas id="graficoCircular" width="220" height="100"></canvas>

                    </div>
                </div>
            </div>

            <div class="col-lg-3 h-100" >
                <div class="card text-center mb-3" style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); background-color: rgba(214, 228, 240, 0.6); padding:1%" >
                    <div class="card-body" style="background-color: white">
                    <canvas id="graficoBarras2" width="100" height="100"></canvas>

                    </div>
                </div>
            </div>

            <div class="col-lg-3 h-100" >
                <div class="card text-center mb-3" style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); background-color: rgba(214, 228, 240, 0.6);padding:1%" >
                    <div class="card-body" style="background-color: white">
                    <canvas id="graficoBarras3" width="100" height="100"></canvas>

                    </div>
                </div>
            </div>


        </div>

        <!-- Selectores para mes y año -->
        <div class="row">

            <div class="col-md-12" style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); background-color: rgba(214, 228, 240, 0.6); margin:1% ; padding:1%" >
                <!-- Gráfico 1 -->
            @if($derivaciones->isNotEmpty())
                <canvas id="estadisticasChart" style="background-color: white"></canvas>
            @else
                <p>No se encontraron registros para el mes y año especificados.</p>
            @endif
        
            </div>

            <div class="col-md-12" style="box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); background-color: rgba(214, 228, 240, 0.6); margin:1% ; padding:1%" >
            <!-- Gráfico 2 -->
                <canvas id="estadisticasChart2" style="background-color: white"></canvas>
            </div>

        </div>


    </div>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Función para inicializar gráficos de barras
            function crearGraficoBarras(id, labels, data, label) {
                var ctx = document.getElementById(id).getContext('2d');
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: [
                                'rgba(255, 182, 193, 0.6)', // Rosa pastel
                                'rgba(176, 224, 230, 0.6)', // Azul pastel
                                'rgba(255, 228, 181, 0.6)', // Amarillo pastel
                                'rgba(144, 238, 144, 0.6)', // Verde pastel
                                'rgba(255, 222, 173, 0.6)', // Naranja pastel
                                'rgba(255, 218, 185, 0.6)', // Durazno pastel
                                'rgba(221, 160, 221, 0.6)', // Lavanda pastel
                                'rgba(176, 224, 255, 0.6)', // Azul claro pastel
                                'rgba(255, 240, 245, 0.6)', // Rosa claro pastel
                                'rgba(240, 255, 240, 0.6)', // Verde claro pastel
                            ],
                            borderColor: [
                                'rgba(255, 182, 193, 0.6)', // Rosa pastel
                                'rgba(176, 224, 230, 0.6)', // Azul pastel
                                'rgba(255, 228, 181, 0.6)', // Amarillo pastel
                                'rgba(144, 238, 144, 0.6)', // Verde pastel
                                'rgba(255, 222, 173, 0.6)', // Naranja pastel
                                'rgba(255, 218, 185, 0.6)', // Durazno pastel
                                'rgba(221, 160, 221, 0.6)', // Lavanda pastel
                                'rgba(176, 224, 255, 0.6)', // Azul claro pastel
                                'rgba(255, 240, 245, 0.6)', // Rosa claro pastel
                                'rgba(240, 255, 240, 0.6)', // Verde claro pastel
                            ],                        
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Función para inicializar gráficos de líneas
            function crearGraficoLineas(id, labels, datasets) {
                var ctx = document.getElementById(id).getContext('2d');
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                }
                            }
                        }
                    }
                });
            }
            // 
          // Función para inicializar gráficos circulares
function crearGraficoCircular(id, labels, data) {
    var ctx = document.getElementById(id).getContext('2d');
    return new Chart(ctx, {
        type: 'doughnut', // También puedes usar 'pie'
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Rojo
                    'rgba(54, 162, 235, 0.6)', // Azul
                    'rgba(255, 206, 86, 0.6)', // Amarillo
                    'rgba(75, 192, 192, 0.6)', // Verde
                    'rgba(153, 102, 255, 0.6)', // Morado
                    'rgba(255, 159, 64, 0.6)', // Naranja
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'left',
                    labels: {
                        font: {
                            size: 10, // Tamaño de las etiquetas de la leyenda
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const label = tooltipItem.label || '';
                            const value = tooltipItem.raw || 0;
                            return `${label}: ${value}`;
                        }
                    },
                    bodyFont: {
                        size: 10 // Tamaño de las etiquetas del tooltip
                    }
                }
            }
        }
    });
}


            // 

            // Inicializar el gráfico circular
            var graficoCircular = crearGraficoCircular('graficoCircular', [], []);
            
            // Función para actualizar el gráfico circular
            function actualizarGraficoCircular() {
                var mesSeleccionado = document.getElementById('monthSelect').value;
                var añoSeleccionado = document.getElementById('yearSelect').value;
            
                fetch('https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/getDerivacionesEstado?month=' + mesSeleccionado + '&year=' + añoSeleccionado)
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar datos del gráfico
                        graficoCircular.data.labels = ['Pendiente', 'Aceptado', 'Finalizado'];
                        graficoCircular.data.datasets[0].data = [data.estado1, data.estado2, data.estado3];
                        graficoCircular.update();
                    })
                    .catch(error => console.error('Error al cargar el gráfico circular:', error));
            }
        
            // Asignar evento de actualización al cambiar el mes o año
            document.getElementById('monthSelect').addEventListener('change', actualizarGraficoCircular);
            document.getElementById('yearSelect').addEventListener('change', actualizarGraficoCircular);
        
            // Cargar los datos iniciales
            actualizarGraficoCircular();

            // 


            var graficoBarras2 = crearGraficoBarras2('graficoBarras2', [], []); 

function crearGraficoBarras2(id, labels, data, label) {
    var ctx = document.getElementById(id).getContext('2d');
    return new Chart(ctx, {
        type: 'bar', // Tipo 'bar' para gráfico de barras
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: [
                    'rgba(255, 182, 193, 0.6)', // Rosa pastel
                    'rgba(176, 224, 230, 0.6)', // Azul pastel
                    'rgba(255, 228, 181, 0.6)', // Amarillo pastel
                
                ],
                borderColor: [
                    'rgba(255, 182, 193, 0.6)', // Rosa pastel
                    'rgba(176, 224, 230, 0.6)', // Azul pastel
                    'rgba(255, 228, 181, 0.6)', // Amarillo pastel
            
                ],                        
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Esto hace que las barras sean horizontales
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10, // Tamaño de la letra del eje X
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10, // Tamaño de la letra del eje Y
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Programas', // Título que deseas mostrar
                    font: {
                        size: 12, // Tamaño del texto del título
                        weight: 'bold' // Estilo del texto (opcional)
                    },
                    padding: {
                        top: 1, // Espaciado superior
                        bottom: 1 // Espaciado inferior
                    }
                },
                legend: {
                    display: false // Elimina la leyenda que muestra la "cosita para marcar/desmarcar"
                }
            }
        }
    });
}


// Función para actualizar el gráfico de barras
function actualizarGraficoBarras() {
    var mesSeleccionado = document.getElementById('monthSelect').value;
    var añoSeleccionado = document.getElementById('yearSelect').value;

    fetch('https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/obtenerCuentaProgramas?month=' + mesSeleccionado + '&year=' + añoSeleccionado)
        .then(response => response.json())
        .then(data => {
            // Procesar los datos y actualizar el gráfico
            var programas = data.map(item => item.valor);
            var frecuencias = data.map(item => item.frecuencia);

            // Actualizar datos del gráfico de barras
            graficoBarras2.data.labels = programas;
            graficoBarras2.data.datasets[0].data = frecuencias;
            graficoBarras2.update();
        })
        .catch(error => console.error('Error al cargar el gráfico de barras:', error));
}

// Asignar evento de actualización al cambiar el mes o año
document.getElementById('monthSelect').addEventListener('change', actualizarGraficoBarras);
document.getElementById('yearSelect').addEventListener('change', actualizarGraficoBarras);

// Cargar los datos iniciales
actualizarGraficoBarras();

// Grafico 2 sobre promedio derivaciones


// Inicializar el gráfico de barras
// Inicializar el gráfico de barras
var graficoBarras3 = crearGraficoBarras3('graficoBarras3', [], []); 

function crearGraficoBarras3(id, labels, data, label) {
    var ctx = document.getElementById(id).getContext('2d');
    return new Chart(ctx, {
        type: 'bar', // Tipo 'bar' para gráfico de barras
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: [
          
                    'rgba(255, 218, 185, 0.6)', // Durazno pastel
                    'rgba(221, 160, 221, 0.6)', // Lavanda pastel
                    'rgba(176, 224, 255, 0.6)', // Azul claro pastel
                    'rgba(255, 240, 245, 0.6)', // Rosa claro pastel
                    'rgba(240, 255, 240, 0.6)', // Verde claro pastel
                ],
                borderColor: [
                    'rgba(255, 218, 185, 0.6)', // Durazno pastel
                    'rgba(221, 160, 221, 0.6)', // Lavanda pastel
                    'rgba(176, 224, 255, 0.6)', // Azul claro pastel
                    'rgba(255, 240, 245, 0.6)', // Rosa claro pastel
                    'rgba(240, 255, 240, 0.6)', // Verde claro pastel
                ],                        
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Esto hace que las barras sean horizontales
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10, // Tamaño de la letra del eje X
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 10, // Tamaño de la letra del eje Y
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Tiempo Promedio Cambio Estado', // Título que deseas mostrar
                    font: {
                        size: 12, // Tamaño del texto del título
                        weight: 'bold' // Estilo del texto (opcional)
                    },
                    padding: {
                        top: 1, // Espaciado superior
                        bottom: 1 // Espaciado inferior
                    }
                },
                legend: {
                    display: false // Elimina la leyenda que muestra la "cosita para marcar/desmarcar"
                }
            }
        }
    });
}



// Función para actualizar el gráfico de barras
function actualizarGraficoBarras3() {
    var mesSeleccionado = document.getElementById('monthSelect').value;
    var añoSeleccionado = document.getElementById('yearSelect').value;

    fetch('https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/promedio-cambio-estado-mes?month=' + mesSeleccionado + '&year=' + añoSeleccionado)
        .then(response => response.json())
        .then(data => {
            // Procesar los datos del JSON recibido
            var estados = ['Aceptada', 'Finalizada'];  // Etiquetas de los estados
            var frecuencias = [data.Aceptada, data.Finalizada];  // Datos para las barras

            // Actualizar los datos del gráfico
            graficoBarras3.data.labels = estados;
            graficoBarras3.data.datasets[0].data = frecuencias;
            graficoBarras3.update();
        })
        .catch(error => console.error('Error al cargar el gráfico de barras:', error));
}

// Asignar evento de actualización al cambiar el mes o año
document.getElementById('monthSelect').addEventListener('change', actualizarGraficoBarras3);
document.getElementById('yearSelect').addEventListener('change', actualizarGraficoBarras3);

// Cargar los datos iniciales
actualizarGraficoBarras3();


            // 

            
            // Inicializar gráficos
            var cursos = {!! json_encode($derivaciones->pluck('curso')) !!};
            var totales = {!! json_encode($derivaciones->pluck('total')) !!};
            var estadisticasChart = crearGraficoBarras('estadisticasChart', cursos, totales, 'Total de Derivaciones');
            var estadisticasChart2 = crearGraficoLineas('estadisticasChart2', [], []);

            // Función para actualizar ambos gráficos
            function actualizarGraficos() {
                var mesSeleccionado = document.getElementById('monthSelect').value;
                var añoSeleccionado = document.getElementById('yearSelect').value;

                // Actualizar gráfico de barras (primer gráfico)
                fetch('https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/getDerivaciones?month=' + mesSeleccionado + '&year=' + añoSeleccionado)
                
                    .then(response => response.json())
                    .then(data => {
                        estadisticasChart.data.labels = data.cursos;
                        estadisticasChart.data.datasets[0].data = data.totales;
                        estadisticasChart.update();
                    })
                    .catch(error => console.error('Error al cargar el primer gráfico:', error));

                // Actualizar gráfico de líneas (segundo gráfico)
                // Actualizar gráfico de líneas (segundo gráfico)
                Promise.all([
                    fetch('https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/getDatosGraficosCombinados?month=' + mesSeleccionado + '&year=' + añoSeleccionado)
                ])
                .then(responses => Promise.all(responses.map(response => response.json())))
                .then(data => {
                    var dataGraficosCombinados = data[0]; // Datos combinados
                
                    // Excluir los valores "null" antes de procesar los datos combinados
                    var filteredData = dataGraficosCombinados.filter(item => item.valor !== "null");
                
                    // Mapear los valores y frecuencias combinadas
                    var labels2 = filteredData.map(item => item.valor);
                    var frecuencias2 = filteredData.map(item => parseInt(item.frecuencia));
                
                    // Actualizar el gráfico de líneas
                    estadisticasChart2.data.labels = labels2;
                    estadisticasChart2.data.datasets = [{
                        label: 'Frecuencia de Indicadores',
                        data: frecuencias2,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.4)',
                        fill: true,
                    }];
                    estadisticasChart2.update();
                })
                .catch(error => console.error('Error al cargar el segundo gráfico:', error));
            }

            // Actualizar los gráficos y obtener los datos de los programas cuando cambian el mes o el año
            document.getElementById('monthSelect').addEventListener('change', function() {
                actualizarGraficos();
                // obtenerCuentaProgramas();
            });
            document.getElementById('yearSelect').addEventListener('change', function() {
                actualizarGraficos();
                // obtenerCuentaProgramas();
            });

            // Cargar los datos iniciales
            actualizarGraficos();
            // obtenerCuentaProgramas();


///////////////////// 

// // Inicializar el gráfico de barras



    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Asignar el evento al botón de exportación
        document.getElementById('exportButton').addEventListener('click', function() {
            // Obtener los valores seleccionados en los selects
            var mesSeleccionado = document.getElementById('monthSelect').value;
            var añoSeleccionado = document.getElementById('yearSelect').value;
            
            // Verificar los valores en la consola
            console.log('mes seleccionado: ' + mesSeleccionado);  // Aquí verás el valor del mes
            console.log('año seleccionado: ' + añoSeleccionado);  // Aquí verás el valor del año

            // Asignar los valores seleccionados a la URL completa
            var url = 'https://cmvapp.cl/proyecto_capston_laravel/public/estadisticas/exportar-estadisticas?month=' + mesSeleccionado + '&year=' + añoSeleccionado;
            console.log(url);  // Aquí verás la URL generada en la consola

            // Redirigir a la URL con los parámetros
            window.location.href = url;  // Esto llevará al usuario a la URL
        });
    });
</script>


    @endsection
