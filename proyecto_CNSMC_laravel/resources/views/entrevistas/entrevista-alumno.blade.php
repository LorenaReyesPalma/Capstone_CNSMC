@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4" style="color: #002A45;">Entrevista con Estudiante</h2>

    <form method="POST" action="{{ route('entrevista.store') }}">
        @csrf

        <!-- Campos ocultos para derivacion_id y tipo_entrevista -->
        <input type="hidden" name="derivacion_id" value="{{ $derivacion->id }}">
        <input type="hidden" name="tipo_entrevista" value="{{ $tipo_entrevista }}">
        <input type="hidden" name="citacionId" value="{{ $citacionId}}">

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="nombre_estudiante">Nombre del Estudiante:</label>
                <input type="text" class="form-control" id="nombre_entrevistado" name="nombre_entrevistado"
                    value="{{ $derivacion->nombre_estudiante }}" required>
            </div>
            <div class="col-md-3">
                <label for="curso">Curso:</label>
                <input type="text" class="form-control" id="curso" name="curso" value="{{ $derivacion->curso }}"
                    required>
            </div>
            <div class="col-md-3">
                <label for="entrevistador">Entrevistador/a:</label>
                <input type="text" class="form-control" id="entrevistador" name="entrevistador"
                    value="{{ $derivacion->colaborador_nombre }}" required>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="motivo_id">Motivo de la Entrevista:</label>
            <select class="form-control" id="motivo_id" name="motivo_id" required>
                <option value="">Seleccione un motivo</option>
                @foreach($motivos as $motivo)
                <option value="{{ $motivo->id }}">{{ $motivo->motivo }}</option>
                @endforeach
                <option value="otro">Otro (especificar)</option>
            </select>
            <input type="text" class="form-control mt-2" id="nuevo_motivo" name="nuevo_motivo"
                placeholder="Ingrese un nuevo motivo" style="display: none;">
        </div>

        <div class="form-group mb-3">
            <label for="desarrollo_entrevista">Desarrollo de la Entrevista:</label>
            <textarea class="form-control" id="desarrollo_entrevista" name="desarrollo_entrevista" rows="4"
                required></textarea>
        </div>

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

        <button type="submit" class="btn btn-primary">Guardar Entrevista</button>
    </form>
</div>

<script>
document.getElementById('motivo_id').addEventListener('change', function() {
    const nuevoMotivoInput = document.getElementById('nuevo_motivo');
    if (this.value === 'otro') {
        nuevoMotivoInput.style.display = 'block';
    } else {
        nuevoMotivoInput.style.display = 'none';
    }
});

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
</script>
@endsection