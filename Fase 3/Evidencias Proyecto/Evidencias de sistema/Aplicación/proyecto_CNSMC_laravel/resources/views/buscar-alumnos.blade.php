@extends('layouts.app')

@section('content')

<div class="container">

<div class="row">
    <div class="col-3">
        <h3 class="mb-3 text-primary" style="color: #002A45;">Buscar Alumnos</h3>
    </div>

  
</div>



    <!-- Formulario para seleccionar el curso y buscar por RUT o nombre -->
    <form action="{{ route('curso.buscar') }}" method="GET">
    @csrf
    <div class="row align-items-end">
        <!-- Campo para seleccionar curso -->
        <div class="col-md-4 mb-2">
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
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label for="search">Buscar por RUT o Nombre:</label>
                <input type="text" name="search" class="form-control" placeholder="Ingresa RUT o nombre del alumno">
            </div>
        </div>

        <!-- Fila para los botones -->
        <div class="col-md-4 mb-2 d-flex justify-content-end">
            <!-- Mostrar el botón para seleccionar el curso rápido si el usuario tiene id_category 3 -->
            @if(auth()->user()->id_category == 3)
            <div class="mr-2">
                <button type="button" class="btn btn-warning"
                        onclick="seleccionarCurso('{{ $codTipoEnsenanza }}', '{{ $codGrado }}', '{{ $letraCurso }}')">
                    <strong>Mi Curso</strong>
                </button>
            </div>
            @endif

            <!-- Botón de búsqueda -->
            <div>
                <button type="submit" id="btnBuscarAlumnos" class="btn btn-primary" style="background-color: #002A45;">
                    Buscar Alumnos
                </button>
            </div>
        </div>
    </div>
</form>


    <!-- Mostrar resultados en una tabla si hay alumnos -->
    @isset($alumnos)
    <div class="table-responsive" style="max-height: 50vh;">
        <table class="table table-striped table-bordered table-sm ">
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
                    <td class="text-center">
                        @if($alumno->expediente_existe)
                        <a href="{{ route('alumno.expediente', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}" class="btn btn-primary btn-sm" title="Ver Expediente">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        @else
                        <a href="#" onclick="confirmarCreacionExpediente('{{ $alumno->run }}', '{{ $alumno->digito_ver }}')" class="btn btn-outline-secondary btn-sm" title="Crear Expediente">
                            <i class="fas fa-eye-slash"></i> Ver
                        </a>
                        @endif
                        <a href="#" style="background-color: #002A45; color: white" onclick="confirmarDerivacion('{{ route('derivaciones.derivacion-crear', ['run' => $alumno->run, 'dv' => $alumno->digito_ver]) }}')" class="btn btn-warning btn-sm" title="Nueva Derivación">
                        <i class="fa-solid fa-file-alt"></i> <i class="fa-solid fa-plus"></i>Derivación

                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No se encontraron alumnos para el criterio de búsqueda seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endisset
</div>

<script>
     // Función para seleccionar el curso rápido
    function seleccionarCurso(codTipo, codGrado, letraCurso) {
    // Establecer el valor del curso en el campo de selección
    const selectCurso = document.querySelector('select[name="cod_tipo_ensenanza"]');
    selectCurso.value = `${codTipo}_${codGrado}_${letraCurso}`;

    // Hacer clic en el botón de búsqueda de alumnos utilizando el id
    const botonBuscar = document.getElementById('btnBuscarAlumnos');
        if (botonBuscar) {
            botonBuscar.click();
        }
    }
    function confirmarCreacionExpediente(run, dv) {
        if (confirm('Este alumno no tiene expediente. ¿Deseas crearlo?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('expediente.create', ['run' => ':run', 'dv' => ':dv']) }}`.replace(':run', run).replace(':dv', dv);

            const token = document.createElement('input');
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
            window.location.href = url;
        }
    }
</script>

<style>
    /* Estilo para el scroll */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .table-responsive::-webkit-scrollbar-track {
        background: transparent;
    }
</style>

@endsection
