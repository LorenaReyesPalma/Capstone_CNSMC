@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ficha Derivación</h3>

    <!-- Grilla para los datos del estudiante y los datos de la derivación en un solo contenedor -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Datos del Estudiante y de la Derivación</h5>
            <!-- Botón para abrir el modal de edición de datos del estudiante -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStudentModal"
                title="Editar datos">
                <i class="fas fa-pencil-alt"></i>
            </button>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Indicadores</h5>
            <!-- Botón para abrir el modal de edición de indicadores -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editIndicatorsModal">
                <i class="fas fa-pencil-alt" style="margin-right: 5px;"></i>
            </button>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Motivo y Acciones</h5>
            <!-- Botón para abrir el modal de edición de Motivo y Acciones -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#editMotivoAccionesModal" title="Editar Motivo y Acciones">
                <i class="fas fa-pencil-alt"></i>
            </button>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Motivo de la Derivación:</strong><br>{!! nl2br(e($derivacion->motivo_derivacion)) !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Acciones Realizadas Anteriormente:</strong><br>{!!
                        nl2br(e($derivacion->acciones_realizadas)) !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Sugerencias:</strong><br>{!! nl2br(e($derivacion->sugerencias)) !!}</p>
                </div>

            </div>
        </div>
    </div>



    <!-- CITACIONES -->

    <!-- Mostrar citaciones si existen -->

    <div class="card mt-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Citaciones Realizadas</h5>
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#citacionModal">
                Añadir Citación <i class="fas fa-calendar fa-2x"></i>
            </button>
        </div>


        @if($citaciones->count() > 0)
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha de Citación</th>
                            <th>Hora</th>
                            <th>Tipo de Acción</th>
                            <th>Observaciones</th>
                            <th>Citado por</th>
                            <th>Estado</th>
                            <th>Acción</th> <!-- Nueva columna para enlace -->
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
                            <td>
                                @switch($citacion->estado)
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
                            </td>
                            <td>
                                <!-- Enlaces basados en el tipo de citación -->

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
                                @endif




                                <!-- Formulario para cancelar la citación -->
                                <form action="{{ route('citacion.cancelar', $citacion->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Está seguro de cancelar esta citación?')">
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
    </div>
    @else
    <div class="alert alert-warning mt-4">
        No hay citaciones vigentes para esta derivación.
    </div>
    @endif


    <!-- FIN CITACIONES -->

    <!-- entrevistas -->


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
                            <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEntrevista{{ $entrevista->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('entrevista.destroy', $entrevista->id) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta entrevista?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
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

                                            <div class="row mb-3">
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
                                            </div>
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



    <!-- Modal -->
    <div class="modal fade" id="citacionModal" tabindex="-1" aria-labelledby="citacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="citacionModalLabel">Añadir Citación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para crear citación -->
                    <form action="{{ route('citaciones.store', $derivacion->id) }}" method="POST">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit datos personales -->
    <!-- Modal para editar los datos del estudiante -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Editar Datos del Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para editar los datos del estudiante -->
                    <form action="{{ route('derivacion.update', $derivacion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Campo para teléfono -->
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $derivacion->telefono }}"
                                required>
                        </div>

                        <!-- Campo para adulto responsable -->
                        <div class="form-group mt-3">
                            <label for="adulto_responsable">Adulto Responsable</label>
                            <input type="text" name="adulto_responsable" class="form-control"
                                value="{{ $derivacion->adulto_responsable }}" required>
                        </div>

                        <!-- Campo para programa de retención -->
                        <div class="form-group mt-3">
                            <label for="programa_retencion">Programa de Retención</label>
                            <select name="programa_retencion" class="form-control" required>
                                <option value="1" {{ $derivacion->programa_retencion == 1 ? 'selected' : '' }}>Sí
                                </option>
                                <option value="0" {{ $derivacion->programa_retencion == 0 ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>

                        <!-- Campo para programa de integración -->
                        <div class="form-group mt-3">
                            <label for="programa_integracion">Programa de Integración</label>
                            <select name="programa_integracion" class="form-control" required>
                                <option value="1" {{ $derivacion->programa_integracion == 1 ? 'selected' : '' }}>Sí
                                </option>
                                <option value="0" {{ $derivacion->programa_integracion == 0 ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <!-- edit para los indicadores -->

    <!-- Modal para editar los indicadores -->
    <div class="modal fade" id="editIndicatorsModal" tabindex="-1" aria-labelledby="editIndicatorsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editIndicatorsModalLabel">Editar: Indicadores sugeridos para iniciar
                        intervención</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('actualizarIndicadores', $derivacion->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div>
                            <p>Seleccione aquel indicador que considere relevante para la intervención de
                                convivencia
                                escolar:</p>

                            <h6>Ámbito personal:</h6>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                    value="Repitencia o pre-deserción escolar">
                                <label class="form-check-label">Repitencia o pre-deserción escolar</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                    value="Bajo rendimiento académico">
                                <label class="form-check-label">Bajo rendimiento académico</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                    value="Presentación personal e higiene">
                                <label class="form-check-label">Presentación personal e higiene</label>
                            </div>
                            <div class="form-check d-flex align-items-center mb-3">
                                <input type="checkbox" class="form-check-input me-2" name="indicador_personal[]"
                                    value="Diagnóstico previo" id="diagnostico_previo_checkbox">
                                <label class="form-check-label me-2" for="diagnostico_previo_checkbox">Diagnóstico
                                    previo</label>
                                <input type="text" name="diagnostico_previo" class="form-control flex-grow-1"
                                    placeholder="Especificar diagnóstico previo">
                            </div>
                            <div class="form-check d-flex align-items-center mb-3">
                                <input type="checkbox" class="form-check-input me-2" name="indicador_personal[]"
                                    value="Tratamiento farmacológico" id="tratamiento_farmacologico_checkbox">
                                <label class="form-check-label me-2"
                                    for="tratamiento_farmacologico_checkbox">Tratamiento farmacológico</label>
                                <input type="text" name="tratamiento_farmacologico" class="form-control flex-grow-1"
                                    placeholder="Especificar tratamiento">
                            </div>

                            <h6>Ámbito familiar:</h6>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                    value="Adultos con baja escolaridad">
                                <label class="form-check-label">Adultos con baja escolaridad</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                    value="Precariedad del empleo">
                                <label class="form-check-label">Precariedad del empleo</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                    value="Problemas en el establecimiento de límites y normas">
                                <label class="form-check-label">Problemas en el establecimiento de límites y normas
                                    por
                                    parte del adulto responsable</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                    value="Abandono afectivo">
                                <label class="form-check-label">Abandono afectivo</label>
                            </div>

                            <h6>Ámbito Socio-comunitario:</h6>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_socio_comunitario[]"
                                    value="Sectores con conductas infractoras">
                                <label class="form-check-label">Sectores con conductas infractoras: consumo y
                                    tráfico de
                                    drogas</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="indicador_socio_comunitario[]"
                                    value="Pertenencia a grupos de riesgo">
                                <label class="form-check-label">Pertenencia a grupos con conductas de riesgo</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->

    <!-- edit motivo y acciones -->

    <!-- Modal para editar motivo y acciones -->
    <div class="modal fade" id="editMotivoAccionesModal" tabindex="-1" aria-labelledby="editMotivoAccionesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMotivoAccionesLabel">Editar Motivo y Acciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('actualizarMotivoAcciones', $derivacion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="motivo_derivacion" class="form-label"><strong>Motivo de la
                                    Derivación:</strong></label>
                            <textarea class="form-control" id="motivo_derivacion" name="motivo_derivacion"
                                rows="3">{{ $derivacion->motivo_derivacion }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="acciones_realizadas" class="form-label"><strong>Acciones Realizadas
                                    Anteriormente:</strong></label>
                            <textarea class="form-control" id="acciones_realizadas" name="acciones_realizadas"
                                rows="3">{{ $derivacion->acciones_realizadas }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sugerencias" class="form-label"><strong>Sugerencias:</strong></label>
                            <textarea class="form-control" id="sugerencias" name="sugerencias"
                                rows="3">{{ $derivacion->sugerencias }}</textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection