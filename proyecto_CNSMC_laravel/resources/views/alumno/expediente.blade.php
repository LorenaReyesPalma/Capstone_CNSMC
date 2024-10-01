@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Expediente de {{ $expediente->nombres }} {{ $expediente->apellido_paterno }} {{ $expediente->apellido_materno }}</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="expedienteForm" action="{{ route('expediente.update', ['run' => $expediente->run, 'dv' => $expediente->digito_ver]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="accordion mt-4" id="expedienteAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h4 class="mb-0">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            INFORMACIÓN GENERAL
                        </button>
                    </h4>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#expedienteAccordion">
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>RUN:</strong></td>
                                    <td>{{ $expediente->run }}-{{ $expediente->digito_ver }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Curso:</strong>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="curso" id="curso" value="{{ $expediente->curso }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Género:</strong>
                                    </td>
                                    <td>
                                        <select class="form-control" name="genero" id="genero">
                                            <option value="M" {{ $expediente->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ $expediente->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Fecha de Nacimiento:</strong>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ $expediente->fecha_nacimiento }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Dirección:</strong>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="direccion" id="direccion" value="{{ $expediente->direccion }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Región:</strong>
                                    </td>
                                    <td>
                                        <select id="region" class="form-control" onchange="cargarComunas(this.value); document.getElementById('regionInput').value = this.value;">
                                            <option value="">Seleccione una región</option>
                                            @foreach($regiones as $region)
                                            <option value="{{ $region['codigo'] }}" {{ $expediente->region == $region['codigo'] ? 'selected' : '' }}>
                                                {{ $region['nombre'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="region" id="regionInput" value="{{ $expediente->region }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Comuna:</strong>
                                    </td>
                                    <td>
                                        <select id="comuna" name="comuna_residencia" class="form-control">
                                            <option value="">Seleccione una comuna</option>
                                            @foreach($comunas as $comuna)
                                            <option value="{{ $comuna['nombre'] }}" {{ $expediente->comuna_residencia == $comuna['nombre'] ? 'selected' : '' }}>
                                                {{ $comuna['nombre'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Email:</strong>
                                    </td>
                                    <td>
                                        <input type="email" class="form-control" name="email" id="email" value="{{ $expediente->email }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Teléfono:</strong>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="telefono" id="telefono" value="{{ $expediente->telefono }}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="{{ route('curso.index') }}" class="btn btn-secondary">Volver a la Búsqueda de Alumnos</a>
        </div>
    </form>
</div>

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
                    comunaSelect.innerHTML += `<option value="${comuna.nombre}">${comuna.nombre}</option>`;
                });
            })
            .catch(error => {
                console.error('Error al cargar comunas:', error);
                comunaSelect.innerHTML += '<option value="">Error al cargar comunas</option>'; // Mensaje de error
            });
    }
}
</script>

@endsection
