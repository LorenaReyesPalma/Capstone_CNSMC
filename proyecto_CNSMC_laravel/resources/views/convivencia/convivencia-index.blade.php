@extends('layouts.app')

@section('title', 'Convivencia Escolar')

@section('content')
<div class="container mt-1">
    <h3 class="mb-3 text-start">Perfil Convivencia Escolar</h3>
    <p class="text-start" style="font-size: 0.9rem;">Bienvenido equipo de Convivencia Escolar. Desde aquí puedes
        gestionar las derivaciones, citaciones y consultar las estadísticas.</p>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Tabla de Derivaciones Pendientes -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">Derivaciones Pendientes</h5>

                    <div class="table-responsive" style="max-height: 400px; min-height:400px;  overflow-y: auto;">
                        <table class="table table-hover table-striped table-sm" >
                            <thead class="thead-light">
                                <tr>
                                    <th style="font-size: 0.85rem;">Nombre Estudiante</th>
                                    <th style="font-size: 0.85rem;">Curso</th>
                                    <th style="font-size: 0.85rem;">Fecha Derivación</th>
                                    <th style="font-size: 0.85rem;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($derivaciones2 as $derivacion)
                                @php
                                    $fechaDerivacion = \Carbon\Carbon::parse($derivacion->fecha_derivacion)->startOfDay();
                                    $fechaActual = \Carbon\Carbon::now()->startOfDay();
                                    $diasDiferencia = $fechaDerivacion->diffInDays($fechaActual);
                                @endphp

                                <tr class="{{ $diasDiferencia > 3 && $derivacion->estado_id == 1 ? 'table-warning' : '' }}">
                                    <td style="font-size: 0.85rem;">{{ $derivacion->nombre_estudiante }}</td>
                                    <td style="font-size: 0.85rem;">{{ $derivacion->curso }}</td>
                                    <td style="font-size: 0.85rem;">{{ $fechaDerivacion->format('d/m/Y') }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDerivacion{{ $derivacion->id }}">
                                            Ver
                                        </button>

                                        @if($diasDiferencia > 3 && $derivacion->estado_id == 1)
                                        <span class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Atrasada
                                        </span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="modalDerivacion{{ $derivacion->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $derivacion->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content" style="border: 2px solid white;">
                                            <div class="modal-header" style="background-color: #002A45; color: white;">
                                                <h5 class="modal-title" id="modalLabel{{ $derivacion->id }}">Detalles de la Derivación</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="card" style="background-color: #f8f9fa;">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><i class="fas fa-info-circle"></i> Información General</h5>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p><strong>Estudiante:</strong> {{ $derivacion->nombre_estudiante }} <br>
                                                                           <strong>Fecha Derivación:</strong> {{ $fechaDerivacion->format('d/m/Y') }}<br>
                                                                           <strong>Adulto Responsable:</strong> {{ $derivacion->adulto_responsable }}<br>
                                                                           <strong>Teléfono:</strong> {{ $derivacion->telefono }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p><strong>Curso:</strong> {{ $derivacion->curso }}<br>
                                                                           <strong>Colaborador que deriva:</strong> {{ $derivacion->colaborador_nombre }}<br>
                                                                           <strong>Estado:</strong> {{ $derivacion->estado_id == 1 ? 'Pendiente' : 'Completado' }}<br>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <div class="card" style="background-color: #f8f9fa;">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><i class="fas fa-tasks"></i> Motivo y Acciones</h5>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <p><strong>Motivo Derivación:</strong><br>
                                                                            {{ $derivacion->motivo_derivacion }}</p>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <p><strong>Acciones Realizadas:</strong><br>
                                                                            {{ $derivacion->acciones_realizadas }}</p>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <p><strong>Sugerencias:</strong><br>
                                                                            {{ $derivacion->sugerencias }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                                <!-- Formulario para aceptar la derivación -->
                                                <form action="{{ route('derivaciones.aceptar', $derivacion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas aceptar esta derivación?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-primary">Aceptar Derivación</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- fin modal -->

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Derivaciones -->
        <div class="col-md-3 card p-2 shadow-sm ">
        <div>

<!-- Botón para abrir el modal -->
<button class="btn btn-warning w-100 p-0 shadow-lg" data-toggle="modal" data-target="#derivacionesModal" style="background-color: #002A45; border: none;">
    <div class="card h-100" style="background-color: #002A45; border: none;">
        <div class="card-body text-center text-white">
            <!-- Ícono de Font Awesome -->
            <div class="mb-3">
                <i class="fas fa-eye" style="font-size: 2rem; color: white;"></i>
            </div>
            <h6 class="card-title fw-bold"> Derivaciones Aceptadas</h6>
            <p class="card-text" style="color: #d1e7ff;">Revisa y gestiona las derivaciones aceptadas.</p>
        </div>
    </div>
</button>

<br><br>
</div>
            <div>
                <h5 class="text-center">Mes Actual: {{$derivacionesMesActual}}</h5>
                
                <canvas id="derivacionesChart" width="260" height="150"></canvas>
            </div>
        </div>
    </div>


    <!-- Tarjeta de Citaciones Apoderado -->


        <!-- Botón para abrir el modal -->
       
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="derivacionesModal" tabindex="-1" role="dialog" aria-labelledby="derivacionesModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="derivacionesModalLabel">Derivaciones Aceptadas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
      
                <!-- Tabla con derivaciones -->
                <div class="card mt-3">
                    @if($derivaciones->isEmpty())
                        <p>No hay derivaciones asociadas</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th class="col-lg-2">Alumno</th>
                                        <th class="col-lg-3">Motivo</th>
                                        <th>Responsable</th>
                                        <th>Estado</th>
                                        <th>Acción</th> <!-- Columna de acción -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($derivaciones as $derivacion)
                                    <tr>
                                        <td>{{ $derivacion->fecha_derivacion }}</td>
                                        <td>{{ $derivacion->nombre_estudiante }}</td>
                                        <td class="col-lg-3" style="white-space: normal;">
                                            {{ Str::limit(nl2br(e($derivacion->motivo_derivacion)), 100, '...') }}
                                        </td>
                                        <td>{{ $derivacion->colaborador_nombre }}</td>
                                        <td>
                                            @switch($derivacion->estado_id)
                                                @case(1)
                                                    <span class="dot yellow"></span> En espera
                                                    @break
                                                @case(2)
                                                    <span class="dot green"></span> Aceptada
                                                    @break
                                                @case(3)
                                                    <span class="dot red"></span> Finalizada
                                                    @break
                                                @default
                                                    Desconocido
                                            @endswitch
                                        </td>
                                        <td>
                                            <!-- Botón de acción -->
                                            <a href="{{ route('derivacion.show', $derivacion->id) }}" class="btn btn-primary btn-sm">
                                                Ver Derivación
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<style>
.dot {
    height: 10px;
    width: 10px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.green {
    background-color: #28a745;
}

.yellow {
    background-color: #ffc107;
}

.red {
    background-color: #dc3545;
}
</style>

</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('derivacionesChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Pendientes {{$derivacionesEstado1}}', 'Aceptadas {{$derivacionesEstado2MesActual}}', 'Finalizadas {{$derivacionesEstado3MesActual}}'],
            datasets: [{
                label: 'Derivaciones',
                data: [{{ $derivacionesEstado1 }}, {{ $derivacionesEstado2MesActual }}, {{ $derivacionesEstado3MesActual }}],
                backgroundColor: [
                    'rgba(255, 0, 0, 0.8)', // rojo
                    'rgba(255, 199, 0, 0.8)', // amarillo
                    'rgba(23, 4, 167, 0.8)' // azul
                ],
                borderColor: [
                    'rgba(255, 0, 0, 0.8)', // rojo
                    'rgba(255, 199, 0, 0.8)', // amarillo
                    'rgba(23, 4, 167, 0.8)' // azul
                ],

                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right' // Leyenda a la derecha
                },
                tooltip: {
                    enabled: true
                },
                datalabels: {
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                        const percentage = ((value / total) * 100).toFixed(2) + '%';
                        return percentage;
                    },
                    color: '#fff',
                }
            }
        }
    });
});
</script>
@endsection
