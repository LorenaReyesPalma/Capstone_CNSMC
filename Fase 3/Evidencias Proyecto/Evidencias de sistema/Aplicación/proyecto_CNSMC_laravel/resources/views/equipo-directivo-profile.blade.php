<!-- resources/views/equipo-directivo-profile.blade.php -->
@extends('layouts.app')

@section('title', 'Equipo Directivo')

@section('content')
<div class="container mt-1">
    <h2>Perfil Equipo Directivo</h2>
    <p style="font-size: 0.9rem; " >Bienvenido Equipo directivo. 
        Desde aquí puedes gestionar las derivaciones, citaciones
        y consultar las estadísticas.
    </p>
    <div class="row">
        <!-- Tarjeta de cursos profesores -->
        <div class="col-md-3 mb-1">
            <button class="btn btn-light w-100 shadow-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#cursosModal"
                style="border: none; background-color: #002A45; color: white;">
                <div class="card" style="background-color: #002A45; border: none; overflow: hidden;">
                    <div class="card-body text-center" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <!-- Ícono de Font Awesome -->
                        <div>
                            <i class="fas fa-chalkboard-teacher" style="font-size: 2rem; color: white;"></i>
                        </div>
                        <!-- Título -->
                        <h5 class="card-title fw-bold" style="color: white; font-size: 1.1rem;">Cursos y Profesores</h5>
                        <!-- Texto -->
                        <!-- <p class="card-text" style="color: #d1e7ff; font-size: 0.9rem; text-align: center;">Gestiona y visualiza las asignaciones de docentes en los distintos cursos.</p> -->
                    </div>
                </div>
            </button>
        </div>

        <!-- Tarjeta de carga de bbdd -->
        <div class="col-md-3 mb-1">
            <button type="button" class="btn btn-light w-100 shadow-sm d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#cargarArchivoModal"
                style="border: none; background-color: #002A45; color: white;">
                <div class="card" style="background-color: #002A45; border: none;overflow: hidden;">
                    <div class="card-body text-center" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <!-- Ícono de Font Awesome -->
                        <div>
                            <i class="fas fa-upload" style="font-size: 2rem; color: white;"></i>
                        </div>
                        <!-- Título -->
                        <h5 class="card-title fw-bold" style="color: white; font-size: 1.1rem;">Carga de Matrícula</h5>
                        <!-- Texto -->
                        <!-- <p class="card-text" style="color: #d1e7ff; font-size: 0.9rem; text-align: center;">Actualiza y gestiona la base de datos escolar con los registros de matrícula.</p> -->
                    </div>
                </div>
            </button>
        </div>


        <!-- Tarjeta de Citaciones Apoderado -->
        <div class="col-md-3 mb-1">
            <a href="{{ route('citacionShow') }}" class="btn btn-light w-100 shadow-sm d-flex align-items-center justify-content-center" style="background-color: #002A45; border: none;">
                <div class="card" style="background-color: #002A45; border: none; overflow: hidden;">
                    <div class="card-body text-center text-white" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <!-- Ícono de Font Awesome -->
                        <div>
                            <i class="fas fa-users" style="font-size: 2rem; color: white;"></i>
                        </div>
                        <h5 class="card-title fw-bold" style="font-size: 1.1rem;">Citaciones</h5>
                        <!-- <p class="card-text" style="color: #d1e7ff; font-size: 0.9rem; text-align: center;">Consulta las citaciones vigentes con los apoderados de los estudiantes.</p> -->
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta de Estadísticas -->
        <div class="col-md-3 mb-1">
            <a href="{{ route('estadisticas') }}" class="w-100 btn btn-light shadow-sm d-flex align-items-center justify-content-center" style="background-color: #002A45; border: none;">
                <div class="card" style="background-color: #002A45; border: none;  overflow: hidden;">
                    <div class="card-body text-center text-white" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <!-- Ícono de Font Awesome -->
                        <div>
                            <i class="fas fa-chart-line" style="font-size: 2rem; color: white;"></i>
                        </div>
                        <h5 class="card-title fw-bold" style="font-size: 1.1rem;">Estadísticas</h5>
                        <!-- <p class="card-text" style="color: #d1e7ff; font-size: 0.9rem; text-align: center;">Consulta las estadísticas de gestión y rendimiento de la institución.</p> -->
                    </div>
                </div>
            </a>
        </div>


    </div>

    <!-- derivaciones pendientes: -->

            <!-- Tabla de Derivaciones Pendientes -->
            <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-start">Derivaciones Pendientes</h5>

                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
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
        
    <!--  -->
</div>


<!-- Modal 1: Cursos -->
<div class="modal fade" id="cursosModal" tabindex="-1" aria-labelledby="cursosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header" style="background-color: #002A45; color: white;">
                <h5 class="modal-title" id="cursosModalLabel">Cursos y Profesores Asignados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="color: white;"></button>
            </div>
            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <!-- Tabla Responsiva -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Profesor</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursos as $curso)
                                <tr>
                                    <td>{{ $curso->desc_grado }} - {{ $curso->letra_curso }}</td>
                                    <td>
                                        @if ($curso->profesores()->exists())
                                            {{ $curso->profesores->first()->first_name }}
                                            {{ $curso->profesores->first()->last_name }}
                                        @else
                                            <em>Sin profesor asignado</em>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Contenedor para los botones en fila -->
                                        <div class="d-flex flex-row justify-content-start gap-2">
                                            <!-- Botón para asignar un profesor con icono -->
                                            <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                                                data-bs-target="#asignarProfesorModal" data-curso-id="{{ $curso->id }}"
                                                data-curso-nombre="{{ $curso->desc_grado }} - {{ $curso->letra_curso }}"
                                                data-profesor-id="{{ $curso->profesores()->exists() ? $curso->profesores->first()->user_id : '' }}">
                                                <i class="fas fa-user-plus" style="font-size: 20px; margin-right: 5px;"></i>
                                                <span class="text-truncate fs-6">Asignar o Cambiar</span>
                                            </button>
                                            <!-- Botón para quitar el profesor asignado con icono -->
                                            @if ($curso->profesores()->exists())
                                                <form action="{{ route('quitar.profesor.curso') }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                    <input type="hidden" name="user_id" value="{{ $curso->profesores->first()->user_id }}">
                                                    <button type="submit" class="btn btn-danger d-flex align-items-center">
                                                        <i class="fas fa-user-times" style="font-size: 20px; margin-right: 5px;"></i>
                                                        <span class="text-truncate fs-6">Quitar</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Footer del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal 2: Asignar o Quitar Profesor -->
<div class="modal fade" id="asignarProfesorModal" tabindex="-1" aria-labelledby="asignarProfesorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #002A45; color: white;">
        <h5 class="modal-title" id="asignarProfesorModalLabel">Asignar o Cambiar Profesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para asignar o cambiar profesor -->
                <form action="{{ route('asignar.profesor.curso') }}" method="POST">
                    @csrf
                    <input type="hidden" name="curso_id" id="curso_id_modal"> <!-- Campo oculto para el curso -->
                    <label for="curso">Seleccionar Curso:</label>
                    <input type="text" id="curso_nombre_modal" class="form-control" readonly>
                    <!-- Muestra el nombre del curso -->

                    <label for="profesor">Seleccionar Profesor:</label>
                    <select name="user_id" id="profesor" class="form-select">
                        @foreach ($profesores as $profesor)
                        <option value="{{ $profesor->user_id }}">{{ $profesor->first_name }} {{ $profesor->last_name }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary mt-3">Asignar o Cambiar Profesor</button>
                </form>

                <hr>

                <!-- Formulario para quitar profesor -->
                <form action="{{ route('quitar.profesor.curso') }}" method="POST" id="quitarProfesorCurso">
                    @csrf
                    <input type="hidden" name="curso_id" id="curso_id_quitar">
                    <input type="hidden" name="user_id" id="user_id_quitar">
                    <button type="submit" class="btn btn-danger mt-3">Quitar Profesor</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cargarArchivoModal" tabindex="-1" aria-labelledby="cargarArchivoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background-color: #002A45; color: white;">
                <h5 class="modal-title" id="cargarArchivoModalLabel"><i class="bi bi-cloud-arrow-up-fill"></i> Cargar Archivo XLS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <div class="mt-2 mb-2" style="border: 1px solid #007bff; background-color: #e9f5ff; padding: 15px; border-radius: 5px; display: flex; align-items: center;">
                    <span style="font-size: 24px; color: #007bff; margin-right: 10px;">&#9432;</span>
                    <p style="margin: 0;">Te recomendamos cargar este archivo periódicamente para mantener actualizada la nómina de alumnos actuales en la institución educativa. Por ejemplo, cada vez que un alumno ingrese o se retire del establecimiento.</p>
                </div>


                <form action="{{ route('matricula.cargar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 text-center">
                            <label for="archivo" class="form-label fs-5" style="color: #002A45;">Selecciona el archivo XLS</label>
                            <input type="file" class="form-control" name="archivo" id="archivo" accept=".xls,.xlsx" required>
                         </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="background-color: #002A45; color: white;">
                                <i class="bi bi-cloud-upload"></i> Subir Archivo
                            </button>
                        </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <small class="text-muted">Formato permitido: .xls, .xlsx | Tamaño máximo: 5MB</small>
            </div>
        </div>
    </div>
</div>

@endsection


<!-- JavaScript para pasar los datos del primer modal al segundo -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Asignar un evento al abrir el segundo modal
    $('#asignarProfesorModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // El botón que activó el modal
        var cursoId = button.data('curso-id'); // Extraer el ID del curso
        var cursoNombre = button.data('curso-nombre'); // Extraer el nombre del curso
        var profesorId = button.data('profesor-id'); // Extraer el ID del profesor (si existe)

        // Rellenar los campos en el segundo modal
        var modal = $(this);
        modal.find('#curso_id_modal').val(cursoId); // Asignar el ID del curso al campo oculto
        modal.find('#curso_nombre_modal').val(
            cursoNombre); // Asignar el nombre del curso al campo de texto
        modal.find('#profesor').val(
            profesorId); // Asignar el ID del profesor al campo select (si es necesario)

        // Actualizar los campos ocultos en el formulario de quitar si es necesario
        document.getElementById('curso_id_quitar').value = cursoId;
        document.getElementById('user_id_quitar').value = profesorId;
    });
});
</script>

