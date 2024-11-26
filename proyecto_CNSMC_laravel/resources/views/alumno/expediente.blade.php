@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Expediente de {{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <p class="d-inline-flex gap-1">
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#infoModal" onchange="cargarComunas(this.value)">
            <i class="fas fa-edit"></i> EDITAR INFORMACION PERSONAL
        </button> -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#infoModal">
            <i class="fas fa-edit"></i> EDITAR INFORMACION PERSONAL
        </button>

        <!-- <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2"
            aria-expanded="false" aria-controls="collapseExample2">
            CITACIONES
        </button> -->
        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2"
            aria-expanded="false" aria-controls="collapseExample2">
            CITACIONES
        </button>

        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample3"
            aria-expanded="false" aria-controls="collapseExample3">
            Entrevistas
        </button>

    </p>

    <!-- Contenido colapsable con la tabla -->
    <div class="collapse mt-2 mb-2" id="collapseExample2" >
        <div class="card-header">
            <h5>Citaciones Realizadas</h5>
        </div>
        <div class="card card-body">
            <!-- Tabla de citaciones -->
            <table class="table table-striped">
                <thead>
                    <tr>

                        <th scope="col">Tipo Acción</th>
                        <th scope="col">Fecha Citación</th>
                        <th scope="col">Hora Citación</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th> <!-- Nueva columna para enlace -->

                    </tr>
                </thead>
                <tbody>
                    <!-- Recorremos las citaciones -->
                    @foreach($citaciones as $citacion)
                    <tr>
                        <td>{{ $citacion->tipo_accion }}</td>
                        <td>{{ \Carbon\Carbon::parse($citacion->fecha_citacion)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($citacion->hora_citacion)->format('H:i') }}</td>
                        <td>{{ $citacion->observaciones }}</td>
                        <td>{{ $citacion->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <!-- Enlaces basados en el tipo de citación -->
                            @if($citacion->estado == 1)
                                <!-- Si la citación está activa, muestra el ícono de ver -->
                                @if($citacion->tipo_accion == 'Entrevista Alumno')
                                    <a href="{{ route('entrevistas.entrevistaAlumno', ['id' => $citacion->derivacion_id, 'tipo_entrevista' => 1 , $citacion->id ] ) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> <!-- Ícono de ver -->
                                    </a>
                                @elseif($citacion->tipo_accion == 'Entrevista Apoderado')
                                    <a href="{{ route('entrevistas.entrevistaApoderado', ['id' => $citacion->derivacion_id, 'tipo_entrevista' => 2, $citacion->id ]) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> <!-- Ícono de ver -->
                                    </a>
                                @elseif($citacion->tipo_accion == 'Tomar Acuerdos')
                                    <a href="{{ route('entrevistas.entrevistaCompromiso', ['id' => $citacion->derivacion_id, 'tipo_entrevista' => 3, $citacion->id ]) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> <!-- Ícono de ver -->
                                    </a>
                                @elseif($citacion->tipo_accion == 'Citacion Apoderado')
                                    <a href="{{ route('entrevistas.citacionApoderado', ['tipo_entrevista' => 4, $citacion->id ]) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> <!-- Ícono de ver -->
                                    </a>
                                @endif
                            @else
                                <!-- o -->
                                <a href=""
                                       class="btn btn-primary btn-sm" style="visibility: hidden">
                                        <i class="fas fa-eye"></i> <!-- Ícono de ver -->
                                </a>
                            @endif

                            <!-- Formulario para cancelar la citación -->
                            <form action="{{ route('citacion.cancelar', $citacion->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de cancelar esta citación?')">
                                    <i class="fas fa-trash"></i> <!-- Ícono de basurero -->
                                </button>
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- collapse entrevistas -->
        <!-- entrevistas -->

    <div class="collapse mt-2 mb-2" id="collapseExample3" >

    <!-- Mostrar entrevistas si existen -->
    @if($entrevistas->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Entrevistas Realizadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Entrevista</th>
                            <th>Citador por</th>
                            <th>Entrevistado</th>
                            <th>Entrevistador</th>
                            <th>Motivo</th>
                            <th>Acciones</th>

                            <!-- <th>Acuerdos</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrevistas as $entrevista)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($entrevista->fecha)->format('d-m-Y') }}</td>

                            <td>
                                @switch($entrevista->tipo_entrevista)
                                @case(1)
                                Entrevista Alumno
                                @break
                                @case(2)
                                Entrevista Apoderado
                                @break
                                @case(3)
                                Carta de Comprimiso
                                @break
                                @default
                                Sin registro
                                @endswitch
                            </td>
                            <td>{{ $entrevista->citado_por }}</td>
                            <td style="width: 25%;">{{ $entrevista->nombre_entrevistado }}</td>
                            <td>{{ $entrevista->entrevistador }}</td>
                            <td>{{ $entrevista->motivos_entrevista }}</td>
                            <td class="d-flex justify-content-start align-items-center">
    <!-- Botón de ver (modal) -->
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEntrevista{{ $entrevista->id }}">
        <i class="fas fa-eye"></i>
    </button>

    <!-- Formulario para eliminar -->
    <form action="{{ route('entrevista.destroy', $entrevista->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta entrevista?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm ms-2"> <!-- ms-2 agrega un margen entre los botones -->
            <i class="fas fa-trash"></i> <!-- Ícono de eliminar -->
        </button>
    </form>
</td>

                        </tr>

                        <!-- Modal para ver la ficha completa de la entrevista -->
                        <div class="modal fade" id="modalEntrevista{{ $entrevista->id }}" tabindex="-1"
                            aria-labelledby="modalEntrevistaLabel{{ $entrevista->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <div class="d-flex justify-content-between w-100">
                                            <h5 class="modal-title fw-bold"
                                                id="modalEntrevistaLabel{{ $entrevista->id }}">
                                                Ficha de Entrevista
                                            </h5>
                                            <div class="d-flex align-items-center ms-3">
                                                <i class="bi bi-calendar"
                                                    style="font-size: 1.2rem; margin-right: 5px;"></i>
                                                <span class="text-muted" style="font-size: 0.9rem;">
                                                    Fecha: {{ $entrevista->fecha ?? 'Sin fecha' }}
                                                </span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="nombre_estudiante">Nombre del Entrevistado:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->nombre_entrevistado }}" disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="curso">Curso:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->curso }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="motivo">Motivo de la Entrevista:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->motivo->motivo ?? 'Sin motivo' }}"
                                                        disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="entrevistador">Entrevistador:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->entrevistador }}" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="desarrollo_entrevista">Desarrollo de la Entrevista:</label>
                                                <div>
                                                    @if($entrevista->tipo_entrevista == 3)
                                                    <p>EL APODERADO/A SE COMPROMETE A:</p>
                                                    <ul class="list-unstyled"
                                                        style="list-style-type: circle; padding-left: 20px;">
                                                        @foreach(explode("\n", $apoderadoCompromisos) as $linea)
                                                        <li style="margin-bottom: 8px;"> {{ $linea }}</li>
                                                        @endforeach
                                                    </ul>

                                                    <p>EL ESTUDIANTE SE COMPROMETE A:</p>
                                                    <ul class="list-unstyled"
                                                        style="list-style-type: circle; padding-left: 20px;">
                                                        @foreach(explode("\n", $estudianteCompromisos) as $linea)
                                                        <li style="margin-bottom: 8px;"> {{ $linea }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @else
                                                    <textarea class="form-control" rows="6"
                                                        disabled>{{ $entrevista->desarrollo_entrevista }}</textarea>
                                                    @endif
                                                </div>
                                            </div>


                                            <label>Acuerdos con el/la Estudiante:</label>
                                            @if(is_array($entrevista->acuerdos))
                                            @foreach($entrevista->acuerdos as $acuerdo)
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2"
                                                    value="{{ $acuerdo['acuerdo'] }}" disabled>
                                                <input type="date" class="form-control" value="{{ $acuerdo['plazo'] }}"
                                                    disabled>
                                            </div>
                                            @endforeach
                                            @else
                                            @foreach(json_decode($entrevista->acuerdos, true) as $acuerdo)
                                            <div class="d-flex mb-2">
                                                <input type="text" class="form-control me-2"
                                                    value="{{ $acuerdo['acuerdo'] }}" disabled>
                                                <input type="date" class="form-control" value="{{ $acuerdo['plazo'] }}"
                                                    disabled>
                                            </div>
                                            @endforeach
                                            @endif

                                            <!-- <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label>Firma del Entrevistador:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->firma_entrevistador }}" disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Firma del Estudiante:</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $entrevista->firma_estudiante }}" disabled>
                                                </div>
                                            </div> -->
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    @else
    <div class="alert alert-warning mt-4">
        No hay Entrevistas vigentes para esta derivación.
    </div>
    @endif
    </div>
    
    <!--  -->

    <div class="card p-4 shadow-sm" style="background-color: rgba(236, 241, 245, 0.8);">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>RUN:</strong> <span
                    class="text-muted">{{ $expediente->run }}-{{ $expediente->digito_ver }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Curso:</strong> <span class="text-muted">{{ $expediente->curso }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Género:</strong> <span
                    class="text-muted">{{ $expediente->genero == 'M' ? 'Masculino' : 'Femenino' }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Fecha de Nacimiento:</strong> <span
                    class="text-muted">{{ $expediente->fecha_nacimiento }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Dirección:</strong> <span class="text-muted">{{ $expediente->direccion }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Región:</strong> <span class="text-muted">{{ $nombreRegion }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Comuna:</strong> <span class="text-muted">{{ $nombreComuna }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Email:</strong> <span class="text-muted">{{ $expediente->email }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Teléfono:</strong> <span class="text-muted">{{ $expediente->telefono }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Adulto Responsable:</strong> <span
                    class="text-muted">{{ $expediente->adulto_responsable }}</span>
            </div>
        </div>
    </div>

    <!-- Botón para abrir el modal -->



    <!-- Modal para actualizar información -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Actualizar Información Personal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('expediente.update', [$expediente->run, $expediente->digito_ver]) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="card p-4 shadow-sm" style="background-color: rgba(236, 241, 245, 0.8);">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="run"><strong>RUN:</strong></label>
                                    <input type="text" id="run" name="run" class="form-control" value="{{ $expediente->run }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="curso"><strong>Curso:</strong></label>
                                    <input type="text" id="curso" name="curso" class="form-control" value="{{ $expediente->curso }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="genero"><strong>Género:</strong></label>
                                    <select id="genero" name="genero" class="form-control">
                                        <option value="M" {{ $expediente->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ $expediente->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_nacimiento"><strong>Fecha de Nacimiento:</strong></label>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ $expediente->fecha_nacimiento }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="direccion"><strong>Dirección:</strong></label>
                                    <input type="text" id="direccion" name="direccion" class="form-control" value="{{ $expediente->direccion }}">
                                </div>
                                  <!-- Select de Región -->
                                  <div class="col-md-6 mb-3">
                                    <label for="region"><strong>Región:</strong></label>
                                    <select id="region" name="region" class="form-control" onchange="cargarComunas(this.value)">
                                        <option value="">Seleccione una región</option>
                                        <!-- Aquí agregamos las opciones de regiones de forma dinámica -->
                                        @foreach($regiones as $region)
                                        <option value="{{ $region['codigo'] }}"
                                                {{ $region['codigo'] == $expediente->region ? 'selected' : '' }}>
                                                {{ $region['nombre'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                     <!-- Select de Comuna -->
                                     <div class="col-md-6 mb-3">
                                    <label for="comuna"><strong>Comuna:</strong></label>
                                    <select id="comuna" name="comuna" class="form-control" onchange="updateComunaData(this)">
                                    <option value="">Seleccionar Comuna</option>
                                            @foreach($comunas as $comuna)
                                            <option value="{{ $comuna['codigo'] }}"
                                                {{ $comuna['codigo'] == $expediente->comuna_id ? 'selected' : '' }}>
                                                {{ $comuna['nombre'] }}
                                            </option>
                                            @endforeach
                                        <!-- Las opciones se llenarán dinámicamente mediante la función cargarComunas -->
                                    </select>
                                </div>
                                          <!-- Campo oculto para almacenar el ID de la comuna seleccionada -->
                                <input type="hidden" id="comunaInput" name="comuna_id" value="{{ $expediente->comuna_id }}">
                                <input type="hidden" id="comuna_residencia" name="comuna_residencia" value="{{ $expediente->comuna_residencia }}">


                                <div class="col-md-6 mb-3">
                                    <label for="email"><strong>Email:</strong></label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ $expediente->email }}" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono"><strong>Teléfono:</strong></label>
                                    <input type="text" id="telefono" name="telefono" class="form-control" value="{{ $expediente->telefono }}" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="adulto_responsable"><strong>Adulto Responsable:</strong></label>
                                    <input type="text" id="adulto_responsable" name="adulto_responsable" class="form-control" value="{{ $expediente->adulto_responsable }}" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- bloque informacion personal -->

    <div class="collapse" id="collapseExample">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Información Personal</h5>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal">
                <i class="fas fa-edit"></i> Actualizar Información
            </button>
        </div>

        <div class="card p-4 shadow-sm mb-4" style="background-color: rgba(236, 241, 245, 0.8);">
            <!-- Añadido mb-4 para margen inferior -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>RUN:</strong> <span
                        class="text-muted">{{ $expediente->run }}-{{ $expediente->digito_ver }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Curso:</strong> <span class="text-muted">{{ $expediente->curso }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Género:</strong> <span
                        class="text-muted">{{ $expediente->genero == 'M' ? 'Masculino' : 'Femenino' }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Fecha de Nacimiento:</strong> <span
                        class="text-muted">{{ $expediente->fecha_nacimiento }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Dirección:</strong> <span class="text-muted">{{ $expediente->direccion }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Región:</strong> <span class="text-muted">{{ $nombreRegion }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Comuna:</strong> <span class="text-muted">{{ $nombreComuna }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong> <span class="text-muted">{{ $expediente->email }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Teléfono:</strong> <span class="text-muted">{{ $expediente->telefono }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Adulto Responsable:</strong> <span
                        class="text-muted">{{ $expediente->adulto_responsable }}</span>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Sección de Derivaciones -->
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="text-primary">Derivaciones</h4>
        </div>
        <div class="col-auto">
            <button class="btn btn-warning"
                onclick="confirmarDerivacion('{{ route('derivaciones.derivacion-crear', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}')">
                <i class="fas fa-plus-circle"></i> Nueva Derivación
            </button>
        </div>
    </div>

    <!-- Formulario de filtros -->
    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}"
        class="mb-3">
        <div class="row">
            <div class="col">
                <input type="date" name="fecha_desde" value="{{ old('fecha_desde', $fechaDesde) }}" class="form-control"
                    placeholder="Desde">
            </div>
            <div class="col">
                <input type="date" name="fecha_hasta" value="{{ old('fecha_hasta', $fechaHasta) }}" class="form-control"
                    placeholder="Hasta">
            </div>
            <div class="col">
                <select name="estado_id" class="form-control">
                    <option value="">Seleccionar Estado</option>
                    <option value="1" {{ $estadoId == 1 ? 'selected' : '' }}>En espera</option>
                    <option value="2" {{ $estadoId == 2 ? 'selected' : '' }}>Aceptada</option>
                    <option value="3" {{ $estadoId == 3 ? 'selected' : '' }}>Finalizada</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <!-- Botón para restablecer filtros con icono -->
                <a href="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}"
                    class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Restablecer
                </a>
            </div>
        </div>
    </form>


    <div class="card mt-3">
        @if($derivaciones->isEmpty())
        <p>No hay derivaciones asociadas a este alumno.</p>
        @else
        <!-- Tabla con clase responsive -->
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Fecha</th>
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
                        <td class="col-lg-3" style="white-space: normal;">
                            {!! nl2br(e($derivacion->motivo_derivacion)) !!}
                        </td>
                        <td class="col-lg-3" style="white-space: normal;">
                            {!! nl2br(e($derivacion->acciones_realizadas)) !!}
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
                                <a href="{{ route('derivacion.show', $derivacion->id) }}" class="btn btn-info btn-sm"
                                    title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('derivacion.destroy', $derivacion->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Está seguro de eliminar esta derivación?')"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
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


</div>

<!-- modal edit informacion personal -->

<!-- Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Información Personal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('expediente.update', [$expediente->run, $expediente->digito_ver]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Primera fila: Curso y Género -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="curso">Curso:</label>
                                    <select name="curso" id="curso" class="form-control">
                                        @foreach($cursos as $curso)
                                        <option value="{{ $curso->desc_grado }}"
                                            {{ $curso->desc_grado == $expediente->curso ? 'selected' : '' }}>
                                            {{ $curso->desc_grado }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="genero">Género:</label>
                                    <select name="genero" id="genero" class="form-control">
                                        <option value="M" {{ $expediente->genero == 'M' ? 'selected' : '' }}>Masculino
                                        </option>
                                        <option value="F" {{ $expediente->genero == 'F' ? 'selected' : '' }}>Femenino
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda fila: Fecha de Nacimiento y Dirección -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                        class="form-control" value="{{ $expediente->fecha_nacimiento }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección:</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control"
                                        value="{{ $expediente->direccion }}">
                                </div>
                            </div>
                        </div>

                        <!-- Tercera fila: Región y Comuna -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="region">Región:</label>
                                    <select name="region" id="region" class="form-control"
                                        onchange="cargarComunas(this.value)">
                                        <option value="">Seleccionar Región</option>
                                        @foreach($regiones as $region)
                                        <option value="{{ $region['codigo'] }}"
                                            {{ $region['codigo'] == $expediente->region ? 'selected' : '' }}>
                                            {{ $region['nombre'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comuna">Comuna:</label>
                                    <select name="comuna" id="comuna" class="form-control">
                                        <option value="">Seleccionar Comuna</option>
                                        @foreach($comunas as $comuna)
                                        <option value="{{ $comuna['codigo'] }}"
                                            {{ $comuna['codigo'] == $expediente->comuna_id ? 'selected' : '' }}>
                                            {{ $comuna['nombre'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="" name="comuna_id" value="{{ $expediente->comuna_id }}">
                                </div>
                            </div>
                        </div>

                        <!-- Cuarta fila: Email y Teléfono -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $expediente->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control"
                                        value="{{ $expediente->telefono }}">
                                </div>
                            </div>
                        </div>

                        <!-- Quinta fila: Adulto Responsable -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="adulto_responsable">Adulto Responsable:</label>
                                    <input type="text" name="adulto_responsable" id="adulto_responsable"
                                        class="form-control" value="{{ $expediente->adulto_responsable }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--  -->

<script>
// function cargarComunas(regionId) {
//     const comunaSelect = document.getElementById('comuna');
//     comunaSelect.innerHTML = '<option value="">Seleccione una comuna</option>'; // Resetear las comunas

//     if (regionId) {
//         console.log("Cargando comunas para la región ID:", regionId); // Log para verificar el ID de la región

//         // Usar la URL completa para obtener comunas
//         const apiUrl = `https://cmvapp.cl/proyecto_capston_laravel/public/api/comunas/${regionId}`;
//         fetch(apiUrl)
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error("Error en la respuesta de la API");
//                 }
//                 return response.json();
//             })
//             .then(data => {
//                 console.log("Comunas recibidas:", data); // Log para verificar los datos recibidos
//                 data.forEach(comuna => {
//                     comunaSelect.innerHTML += `<option value="${comuna.codigo}">${comuna.nombre}</option>`;
//                 });
//             })
//             .catch(error => {
//                 console.error('Error al cargar comunas:', error);
//                 comunaSelect.innerHTML += '<option value="">Error al cargar comunas</option>'; // Mensaje de error
//             });
//     }
// }

function cargarComunas(regionId) {
    const comunaSelect = document.getElementById('comuna');
    comunaSelect.innerHTML = '<option value="">Seleccione una comuna</option>'; // Resetear las comunas

    // Validar que regionId sea un número, de lo contrario establecerlo como 0
    regionId = isNaN(regionId) || regionId === null || regionId === '' ? 0 : parseInt(regionId, 10);

    if (regionId > 0) {
        regionId = regionId < 10 ? `0${regionId}` : regionId;
        console.log("Cargando comunas para la región ID:", regionId); // Log para verificar el ID de la región

        // Usar la URL completa para obtener comunas
        const apiUrl = `https://cmvapp.cl/proyecto_capston_laravel/public/api/comunas/${regionId}`;
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta de la API");
                }
                return response.json();
            })
            .then(data => {
                console.log("Comunas recibidas:", data); // Log para verificar los datos recibidos
                data.forEach(comuna => {
                    comunaSelect.innerHTML += `<option value="${comuna.codigo}">${comuna.nombre}</option>`;
                });
            })
            .catch(error => {
                console.error('Error al cargar comunas:', error);
                comunaSelect.innerHTML += '<option value="">Error al cargar comunas</option>'; // Mensaje de error
            });
    } else {
        console.log("Región inválida, no se cargaron comunas.");
        comunaSelect.innerHTML += '<option value="">Región no válida</option>'; // Mensaje si la región es inválida
    }
}


function updateComunaData(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const comunaId = selectedOption.value;
    const comunaNombre = selectedOption.text;

    // Actualizar los inputs ocultos
    document.getElementById('comunaInput').value = comunaId;

    // Mostrar en consola
    console.log("Comuna ID:", comunaId);
    console.log("Comuna Nombre:", comunaNombre);
}


function updateComunaData(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const comunaId = selectedOption.value;
    const comunaNombre = selectedOption.text;

    // Actualizar los inputs ocultos
    document.getElementById('comunaInput').value = comunaId;
    document.getElementById('comuna_residencia').value = comunaNombre;

    // Mostrar en consola
    console.log("Comuna ID:", comunaId);
    console.log("Comuna Nombre:", comunaNombre);
}

function confirmarDerivacion(url) {
    if (confirm('¿Estás seguro de que deseas crear una nueva derivación?')) {
        window.location.href = url; // Redirige si el usuario confirma
    }
}
</script>

@endsection