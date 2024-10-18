@extends('layouts.app')

@section('content')

<div class="container ">
    <h3 class="mb-2" style="color: #002A45;" >Buscar Alumnos</h3>

    <!-- Formulario para seleccionar el curso y buscar por RUT o nombre -->
    <form action="{{ route('curso.buscar') }}" method="POST">
        @csrf
        <div class="row align-items-end">
            <!-- Campo para seleccionar curso -->
            <div class="col-md-5 mb-1">
                <div class="form-group">
                    <label for="curso">Selecciona un Curso:</label>
                    <select name="cod_tipo_ensenanza" class="form-control">
                        <option value="">-- Selecciona un curso --</option>
                        @foreach($cursos as $curso)
                        <option value="{{ $curso->cod_tipo_ensenanza }}_{{ $curso->cod_grado }}_{{ $curso->letra_curso }}">
                            {{ $curso->desc_grado }} {{ $curso->letra_curso }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Campo para buscar por RUT o Nombre -->
            <div class="col-md-5 mb-1">
                <div class="form-group">
                    <label for="search">Buscar por RUT o Nombre:</label>
                    <input type="text" name="search" class="form-control" placeholder="Ingresa RUT o nombre del alumno">
                </div>
            </div>

            <!-- Botón de búsqueda -->
            <div class="col-md-2 mb-1">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #002A45;">Buscar Alumnos</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Mostrar resultados en una tabla si hay alumnos -->
    @isset($alumnos)
    <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
        <table class="table table-striped table-bordered table-sm" >
            <thead class="thead-dark">
                <tr>
                    <th>RUN</th>
                    <th>Nombre Completo</th>
                    <th>Curso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->formatted_rut }}</td>
                    <td>{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</td>
                    <td>{{ $alumno->desc_grado }} {{ $alumno->letra_curso }}</td>
                    <td>
                        @if($alumno->expediente_existe)
                        <a href="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}" class="btn btn-info btn-sm">Ver Expediente</a>
                        @else
                        <a href="#"
                           onclick="confirmarCreacionExpediente('{{ $alumno->run }}', '{{ $alumno->digito_ver }}')"
                           class="btn btn-success btn-sm">Crear Expediente</a>
                        @endif
                        <a href="#" onclick="confirmarDerivacion('{{ route('derivacion.create', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}')" class="btn btn-warning btn-sm">Nueva Derivación</a>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No se encontraron alumnos para el criterio de búsqueda seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endisset

</div>

<script>
function confirmarCreacionExpediente(run, dv) {
    if (confirm('¿Estás seguro de que deseas crear el expediente para el alumno?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("expediente.create", ['run' => ':run', 'dv' => ':dv']) }}'.replace(':run', run).replace(':dv', dv);

        var token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        form.appendChild(token);

        document.body.appendChild(form);
        form.submit();
    }
}

function confirmarDerivacion(url) {
        if (confirm('¿Estás seguro de que deseas crear una nueva derivación?')) {
            window.location.href = url; // Redirige si el usuario confirma
        }
    }
</script>

<style>
    /* Estilo para el scroll */
.table-responsive::-webkit-scrollbar {
    width: 8px; /* Ancho del scrollbar */
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2); /* Color del scrollbar */
    border-radius: 4px; /* Bordes redondeados del scrollbar */
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.5); /* Color al pasar el ratón sobre el scrollbar */
}

.table-responsive::-webkit-scrollbar-track {
    background: transparent; /* Fondo del track del scrollbar */
}
</style>

@endsection
