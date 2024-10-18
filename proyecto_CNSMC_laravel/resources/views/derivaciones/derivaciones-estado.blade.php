@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-3">
    <div class="col">
        <h4 class="text-primary" style="color: #002A45;">Derivaciones</h4>
    </div>
    <div class="col-auto">
        <a class="btn btn-warning" href="{{ route('curso.index') }}">
            <i class="fas fa-plus-circle"></i> Nueva Derivación
        </a>
    </div>
</div>

<!-- Formulario de filtros -->
<form method="GET" action="{{ route('derivaciones.estado') }}" class="mb-3">
    <div class="row">
        <div class="col-6 col-md-3 mb-2"> <!-- Columna que ocupa todo el ancho en pantallas pequeñas -->
            <input type="date" name="fecha_desde" value="{{ old('fecha_desde', $fechaDesde) }}" class="form-control" placeholder="Desde">
        </div>
        <div class="col-6 col-md-3 mb-2"> <!-- Columna que ocupa todo el ancho en pantallas pequeñas -->
            <input type="date" name="fecha_hasta" value="{{ old('fecha_hasta', $fechaHasta) }}" class="form-control" placeholder="Hasta">
        </div>
        <div class="col-6 col-md-3 mb-2"> <!-- Columna que ocupa todo el ancho en pantallas pequeñas -->
            <select name="estado_id" class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="1" {{ $estadoId == 1 ? 'selected' : '' }}>En espera</option>
                <option value="2" {{ $estadoId == 2 ? 'selected' : '' }}>Aceptada</option>
                <option value="3" {{ $estadoId == 3 ? 'selected' : '' }}>Finalizada</option>
            </select>
        </div>
        <div class="col-6 col-md-3 mb-2"> <!-- Columna que ocupa auto en pantallas medianas y más grandes -->
            <button  type="submit" class="btn btn-primary col-5 col-md-5">Filtrar</button>
            <!-- Botón para restablecer filtros con icono -->
            <a href="{{ route('derivaciones.estado') }}" class="btn btn-secondary col-5 col-md-5">
                <i class="fas fa-undo"></i> 
            </a>
        </div>
    </div>
</form>


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
                    <th class="col-lg-3">Acciones</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
                    <td class="col-lg-3" style="white-space: normal;">
                        {{ Str::limit(nl2br(e($derivacion->acciones_realizadas)), 100, '...') }}
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
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <a href="{{ route('derivacion.show', $derivacion->id) }}" class="btn btn-icon-only"
                                title="Ver">
                                <i class="fas fa-eye text-info fa-1x"></i> <!-- Icono más grande -->
                            </a>
                            <form action="{{ route('derivacion.destroy', $derivacion->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon-only"
                                    onclick="return confirm('¿Está seguro de eliminar esta derivación?')"
                                    title="Eliminar">
                                    <i class="fas fa-trash text-danger fa-1x"></i> <!-- Icono más grande -->
                                </button>
                            </form>
                        </div>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
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
    /* Color verde */
}

.yellow {
    background-color: #ffc107;
    /* Color amarillo */
}

.red {
    background-color: #dc3545;
    /* Color rojo */
}
</style>
@endsection