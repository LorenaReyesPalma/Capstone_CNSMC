<!-- resources/views/equipo-directivo-profile.blade.php -->
@extends('layouts.app')

@section('title', 'Equipo Directivo')

@section('content')
<div class="container mt-4">
    <h1>Perfil del Equipo Directivo</h1>
    <p>Bienvenido a la sección del perfil del equipo directivo. Desde aquí puedes gestionar las derivaciones, citaciones
        y consultar las estadísticas.</p>

        <div class="row d-flex align-items-stretch">

<!-- Tarjeta de cursos profesores -->
<div class="col-md-3 mb-4">
    <button class="btn w-100 p-0 shadow-lg" data-bs-toggle="modal" data-bs-target="#cursosModal"
        style="border: none; background-color: #002A45; color: white;">
        <div class="card h-100" style="background-color: #002A45; border: none;">
            <div class="card-body text-center">
                <!-- Ícono de Font Awesome -->
                <div class="mb-3">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 2rem; color: white;"></i>
                </div>
                <!-- Título -->
                <h5 class="card-title fw-bold" style="color: white;">Cursos y Profesores</h5>
                <!-- Texto -->
                <p class="card-text" style="color: #d1e7ff;">Gestiona y visualiza las asignaciones de docentes en los distintos cursos.</p>
            </div>
        </div>
    </button>
</div>

<!-- Tarjeta de carga de bbdd -->
<div class="col-md-3 mb-4">
    <button type="button" class="btn w-100 p-0 shadow-lg" data-bs-toggle="modal" data-bs-target="#cargarArchivoModal"
        style="border: none; background-color: #002A45; color: white;">
        <div class="card h-100" style="background-color: #002A45; border: none;">
            <div class="card-body text-center">
                <!-- Ícono de Font Awesome -->
                <div class="mb-3">
                    <i class="fas fa-upload" style="font-size: 2rem; color: white;"></i>
                </div>
                <!-- Título -->
                <h5 class="card-title fw-bold" style="color: white;">Carga de Matrícula</h5>
                <!-- Texto -->
                <p class="card-text" style="color: #d1e7ff;">Actualiza y gestiona la base de datos escolar con los registros de matrícula.</p>
            </div>
        </div>
    </button>
</div>

<!-- Tarjeta de Citaciones Apoderado -->
<div class="col-md-3 mb-4">
    <a href="{{ route('citacionShow') }}" class="btn w-100 p-0 shadow-lg" style="background-color: #002A45; border: none;">
        <div class="card h-100" style="background-color: #002A45; border: none;">
            <div class="card-body text-center text-white">
                <!-- Ícono de Font Awesome -->
                <div class="mb-3">
                    <i class="fas fa-users" style="font-size: 2rem; color: white;"></i>
                </div>
                <h5 class="card-title fw-bold">Citaciones Apoderado</h5>
                <p class="card-text" style="color: #d1e7ff;">Consulta las citaciones <br> con los apoderados de los estudiantes.</p>
            </div>
        </div>
    </a>
</div>


<!-- Tarjeta de Estadísticas -->
<div class="col-md-3 mb-4">
    <button type="button" class="btn w-100 p-0 shadow-lg" style="background-color: #002A45; border: none;" data-bs-toggle="modal" data-bs-target="#estadisticasModal">
        <div class="card h-100" style="background-color: #002A45; border: none;">
            <div class="card-body text-center text-white">
                <!-- Ícono de Font Awesome -->
                <div class="mb-3">
                    <i class="fas fa-chart-line" style="font-size: 2rem; color: white;"></i>
                </div>
                <h5 class="card-title fw-bold">Estadísticas</h5>
                <p class="card-text" style="color: #d1e7ff;">Consulta las estadísticas de gestión y rendimiento de la institución.</p>
            </div>
        </div>
    </button>
</div>

</div>


<!-- Modal 1: Cursos -->
<div class="modal fade" id="cursosModal" tabindex="-1" aria-labelledby="cursosModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content">
        <div class="modal-header" style="background-color: #002A45; color: white;">
        <h5 class="modal-title" id="cursosModalLabel">Cursos y Profesores Asignados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="color: white;"></button>
            </div>
            <div class="modal-body">
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
                                        <!-- Icono de añadir usuario -->
                                        <span class="text-truncate fs-6">Asignar o Cambiar</span>
                                    </button>

                                    <!-- Botón para quitar el profesor asignado con icono -->
                                    @if ($curso->profesores()->exists())
                                    <form action="{{ route('quitar.profesor.curso') }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                        <input type="hidden" name="user_id"
                                            value="{{ $curso->profesores->first()->user_id }}">
                                        <button type="submit" class="btn btn-danger d-flex align-items-center">
                                            <i class="fas fa-user-times"
                                                style="font-size: 20px; margin-right: 5px;"></i>
                                            <!-- Icono de quitar usuario -->
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


@endsection