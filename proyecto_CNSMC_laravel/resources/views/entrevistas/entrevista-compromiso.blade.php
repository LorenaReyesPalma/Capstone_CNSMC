@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4" style="color: #002A45;">Carta de Compromiso Conductual / Pedagógico / Familiar</h2>

    <form method="POST" action="{{ route('entrevista.store') }}">
        @csrf

        <!-- Campos ocultos para derivacion_id y tipo_entrevista -->
        <input type="hidden" name="derivacion_id" value="{{ $derivacion->id }}">
        <input type="hidden" name="tipo_entrevista" value="{{ $tipo_entrevista }}">
        <input type="hidden" name="citacionId" value="{{ $citacionId}}">


        <!-- Datos del Apoderado -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre_apoderado">Nombre Apoderado/a:</label>
                <input type="text" class="form-control" id="nombre_apoderado" name="nombre_apoderado" required>
            </div>
            <div class="col-md-3">
                <label for="curso">Curso:</label>
                <input type="text" class="form-control" id="curso" name="curso" value="{{ $derivacion->curso }}"
                    required>
            </div>
        </div>

        <!-- Datos del Estudiante -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre_estudiante">Nombre del Estudiante:</label>
                <input type="text" class="form-control" id="nombre_estudiante" name="nombre_estudiante"
                    value="{{ $derivacion->nombre_estudiante }}" required>
            </div>
            <div class="col-md-3">
                <label for="entrevistador">Entrevistador/a:</label>
                <input type="text" class="form-control" id="entrevistador" name="entrevistador"
                    value="{{ $derivacion->colaborador_nombre }}" required>
            </div>
        </div>

        <!-- Campo oculto para nombre_entrevistado -->
        <input type="hidden" id="nombre_entrevistado" name="nombre_entrevistado">



        <input type="hidden" id="motivo_id" name="motivo_id" value="7">


        <!-- Desarrollo de la Entrevista (Solo lectura) -->
        <div class="form-group mb-3">
            <label for="desarrollo_entrevista">Desarrollo de la Entrevista:</label>
            <div class="border p-3" style="background-color: #f9f9f9; border-radius: 5px;">
                <p>
                    El apoderado/a se compromete a:
                <ul>
                    <li>Fomentar una conducta adecuada dentro y fuera del colegio, basada en los valores y principios
                        del proyecto educativo y el Reglamento Interno Escolar.</li>
                    <li>Velar por el cumplimiento del deber de estudio, garantizando la asistencia y puntualidad del
                        estudiante.</li>
                    <li>Justificar inasistencias por enfermedad con antecedentes médicos, entregados en recepción o a la
                        inspectora de ciclo.</li>
                    <li>Asegurar que el estudiante se ponga al día en tareas académicas tras una inasistencia por fuerza
                        mayor.</li>
                    <li>Acercarse al colegio para conversar cualquier situación relevante y establecer acuerdos de
                        mejora.</li>
                </ul>
                El estudiante se compromete a:
                <ul>
                    <li>Respetar a todos los miembros de la comunidad educativa a través de un trato cordial.</li>
                    <li>Resolver conflictos mediante el diálogo y con disposición a la mejora personal y comunitaria.
                    </li>
                    <li>Buscar a un adulto para mediar y resolver cualquier conflicto.</li>
                    <li>Cumplir las normas del colegio.</li>
                    <li>Dedicar esfuerzo y atención a sus asignaturas y deberes escolares.</li>
                </ul>
                </p>
            </div>
        </div>

        <!-- Campo oculto para enviar el desarrollo de la entrevista -->
        <input type="hidden" name="desarrollo_entrevista" value="El apoderado/a se compromete a:
            Fomentar una conducta adecuada dentro y fuera del colegio, basada en los valores y principios del proyecto educativo y el Reglamento Interno Escolar.
            Velar por el cumplimiento del deber de estudio, garantizando la asistencia y puntualidad del estudiante.
            Justificar inasistencias por enfermedad con antecedentes médicos, entregados en recepción o a la inspectora de ciclo.
            Asegurar que el estudiante se ponga al día en tareas académicas tras una inasistencia por fuerza mayor.
            Acercarse al colegio para conversar cualquier situación relevante y establecer acuerdos de mejora.
            
            El estudiante se compromete a:
            Respetar a todos los miembros de la comunidad educativa a través de un trato cordial.
            Resolver conflictos mediante el diálogo y con disposición a la mejora personal y comunitaria.
            Buscar a un adulto para mediar y resolver cualquier conflicto.
            Cumplir las normas del colegio.
            Dedicar esfuerzo y atención a sus asignaturas y deberes escolares.">

        <!-- Acuerdos con el/la Estudiante -->
        <div class="form-group mb-3">
            <label>Acuerdos con el/la Estudiante:</label>
            <div id="acuerdos-container">
                <div class="d-flex mb-2">
                    <input type="text" class="form-control me-2" name="acuerdos[]" placeholder="Ingrese un acuerdo"
                        required>
                    <input type="date" class="form-control" name="plazos[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" id="add-acuerdo">Agregar Acuerdo</button>
            <small class="form-text text-muted">Incluir fecha de revisión para cada acuerdo.</small>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Compromiso</button>
    </form>
</div>

<script>
document.getElementById('add-acuerdo').addEventListener('click', function() {
    const acuerdosContainer = document.getElementById('acuerdos-container');
    const newAcuerdo = document.createElement('div');
    newAcuerdo.classList.add('d-flex', 'mb-2');
    newAcuerdo.innerHTML = `
            <input type="text" class="form-control me-2" name="acuerdos[]" placeholder="Ingrese un acuerdo" required>
            <input type="date" class="form-control" name="plazos[]" required>
        `;
    acuerdosContainer.appendChild(newAcuerdo);
});

// Función para concatenar el nombre del estudiante y el nombre del apoderado
function actualizarNombreEntrevistado() {
    const nombreEstudiante = document.getElementById('nombre_estudiante').value;
    const nombreApoderado = document.getElementById('nombre_apoderado').value;

    // Concatenar los valores y actualizar el campo oculto
    const nombreEntrevistado = nombreEstudiante + ' - ' + nombreApoderado;
    document.getElementById('nombre_entrevistado').value = nombreEntrevistado;
}

// Agregar eventos para cuando cambie el valor de los campos
document.getElementById('nombre_estudiante').addEventListener('input', actualizarNombreEntrevistado);
document.getElementById('nombre_apoderado').addEventListener('input', actualizarNombreEntrevistado);

// Inicializar el campo oculto al cargar la página
window.onload = actualizarNombreEntrevistado;
</script>
@endsection