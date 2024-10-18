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
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
            aria-expanded="false" aria-controls="collapseExample">
            INFORMACIÓN PERSONAL
        </button>
        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2"
            aria-expanded="false" aria-controls="collapseExample2">
            CITACIONES
        </button>

    </p>
    <!-- bloque informacion personal -->

    <div class="collapse" id="collapseExample">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Información Personal</h5>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal">
                <i class="fas fa-edit"></i> Actualizar Información
            </button>
        </div>

        <div class="card p-4 shadow-sm mb-4">
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


    <!--  -->
    <div class="collapse" id="collapseExample2">
        <div class="card card-body">
            aaaa
        </div>
    </div>

    <!-- Sección de Derivaciones -->
    <div class="row align-items-center mb-3">
    <div class="col">
        <h4 class="text-primary">Derivaciones</h4>
    </div>
    <div class="col-auto">
        <button class="btn btn-warning"
            onclick="confirmarDerivacion('{{ route('derivacion.create', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}')">
            <i class="fas fa-plus-circle"></i> Nueva Derivación
        </button>
    </div>
</div>

<!-- Formulario de filtros -->
<!-- Formulario de filtros -->
<form method="GET" action="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}" class="mb-3">
    <div class="row">
        <div class="col">
            <input type="date" name="fecha_desde" value="{{ old('fecha_desde', $fechaDesde) }}" class="form-control" placeholder="Desde">
        </div>
        <div class="col">
            <input type="date" name="fecha_hasta" value="{{ old('fecha_hasta', $fechaHasta) }}" class="form-control" placeholder="Hasta">
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
            <a href="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}" class="btn btn-secondary">
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
                                    <a href="{{ route('derivacion.show', $derivacion->id) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('derivacion.destroy', $derivacion->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta derivación?')" title="Eliminar">
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
                                    <input type="hidden" name="comuna_id" value="{{ $expediente->comuna_id }}">
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
function cargarComunas(regionId) {
    const comunaSelect = document.getElementById('comuna');
    comunaSelect.innerHTML = '<option value="">Seleccione una comuna</option>'; // Resetear las comunas

    if (regionId) {
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