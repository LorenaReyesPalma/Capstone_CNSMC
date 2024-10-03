@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ficha de Derivación</h1>

    <!-- Grilla para los datos del estudiante y los datos de la derivación en un solo contenedor -->
    <div class="card">
        <div class="card-header">
            <h3>Datos del Estudiante y de la Derivación</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Datos del Estudiante -->
                <div class="col-md-6">
                    <p><strong>Nombre Estudiante:</strong> {{ $derivacion->nombre_estudiante }}</p>
                    <p><strong>RUT:</strong> {{ $derivacion->run }}-{{ $derivacion->digito_ver }}</p>
                    <p><strong>Edad:</strong> {{ $derivacion->edad }}</p>
                    <p><strong>Curso:</strong> {{ $derivacion->curso }}</p>
                    <p><strong>Teléfono:</strong> {{ $derivacion->telefono }}</p>
                    <p><strong>Adulto Responsable:</strong> {{ $derivacion->adulto_responsable }}</p>
                </div>

                <!-- Datos de la Derivación -->
                <div class="col-md-6">
                    <p><strong>Fecha de Derivación:</strong> {{ $derivacion->fecha_derivacion }}</p>
                    <p><strong>Programa de Integración:</strong> {{ $derivacion->programa_integracion ? 'Sí' : 'No' }}
                    </p>
                    <p><strong>Programa de Retención:</strong> {{ $derivacion->programa_retencion ? 'Sí' : 'No' }}</p>
                    <p><strong>Colaborador que deriva:</strong> {{ $derivacion->colaborador_nombre }}</p>
                    <p><strong>Colaborador que Acepta:</strong> {{ $derivacion->colaborador_nombre }}</p>
                    <p><strong>Estado derivación:</strong>
                        @switch($derivacion->estado_id)
                        @case(1)
                        En espera
                        @break
                        @case(2)
                        Aceptada
                        @break
                        @case(3)
                        Finalizada
                        @break
                        @default
                        Sin registro
                        @endswitch
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicadores -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Indicadores</h3>
        </div>
        <div class="card-body">
            <p><strong>Indicadores Personales:</strong>
                @php
                $indicadores_personal = json_decode($derivacion->indicadores_personal, true);
                @endphp
                {{ is_array($indicadores_personal) ? implode(', ', $indicadores_personal) : 'No hay indicadores personales' }}
            </p>
            <p><strong>Indicadores Familiares:</strong>
                @php
                $indicadores_familiar = json_decode($derivacion->indicadores_familiar, true);
                @endphp
                {{ is_array($indicadores_familiar) ? implode(', ', $indicadores_familiar) : 'No hay indicadores familiares' }}
            </p>
            <p><strong>Indicadores Socio-comunitarios:</strong>
                @php
                $indicadores_socio_comunitario = json_decode($derivacion->indicadores_socio_comunitario, true);
                @endphp
                {{ is_array($indicadores_socio_comunitario) ? implode(', ', $indicadores_socio_comunitario) : 'No hay indicadores socio-comunitarios' }}
            </p>
        </div>
    </div>

    <!-- Grilla para Motivo y Acciones -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Motivo y Acciones</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Motivo de la Derivación:</strong> <br>{{ $derivacion->motivo_derivacion }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Acciones Realizadas Anteriormente:</strong> <br>{{ $derivacion->acciones_realizadas }}
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>Sugerencias:</strong> <br> {{ $derivacion->sugerencias }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CITACIONES -->

    <!-- Mostrar citaciones si existen -->
    @if($citaciones->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h3>Citaciones Realizadas</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha de Citación</th>
                        <th>Hora</th>
                        <th>Tipo de Acción</th>
                        <th>Observaciones</th>
                        <th>Citado por</th>
                        <th>Estado</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($citaciones as $citacion)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($citacion->hora_citacion)->format('H:i') }}</td>
                        <td>{{ $citacion->tipo_accion }}</td>
                        <td>{{ $citacion->observaciones }}</td>
                        <td>{{ $citacion->colaborador_nombre }}</td>
                        <td> @switch($derivacion->estado_id)
                            @case(1)
                            En espera
                            @break
                            @case(2)
                            Aceptada
                            @break
                            @case(3)
                            Finalizada
                            @break
                            @default
                            Sin registro
                            @endswitch</td>



                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-warning mt-4">
        No hay citaciones registradas para esta derivación.
    </div>
    @endif

    <!-- Formulario para crear citación -->
    <form action="{{ route('citaciones.store', $derivacion->id) }}" method="POST" class="mt-4">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="tipo_accion">Tipo de Acción</label>
                <select name="tipo_accion" class="form-control" required>
                    <option value="Entrevista Alumno">Entrevista al Alumno</option>
                    <option value="Entrevista Apoderado">Entrevista al Apoderado</option>
                    <option value="Tomar Acuerdos">Tomar Acuerdos</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="fecha_citacion">Fecha de Citación</label>
                <input type="date" name="fecha_citacion" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="hora_citacion">Hora de Citación</label>
                <input type="time" name="hora_citacion" class="form-control" required>
            </div>
        </div>
        <div class="form-group mt-3">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2">Generar Citación</button>
    </form>

</div>
@endsection