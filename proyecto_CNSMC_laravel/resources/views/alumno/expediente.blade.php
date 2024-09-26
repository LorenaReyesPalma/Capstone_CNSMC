@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Expediente de {{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</h1>

    <div class="accordion mt-4" id="expedienteAccordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h4 class="mb-0">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        I  INFORMACÍON GENERAL
                    </button>
                </h4>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#expedienteAccordion">
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>RUN:</strong></td>
                                <td>{{ $alumno->run }}-{{ $alumno->digito_ver }}</td>
                            </tr>
                            <tr>
                                <td><strong>Curso:</strong></td>
                                <td>{{ $alumno->desc_grado }} {{ $alumno->letra_curso }}</td>
                            </tr>
                            <tr>
                                <td><strong>Género:</strong></td>
                                <td>{{ $alumno->genero }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de Nacimiento:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dirección:</strong></td>
                                <td>{{ $alumno->direccion }}</td>
                            </tr>
                            <tr>
                                <td><strong>Comuna de Residencia:</strong></td>
                                <td>{{ $alumno->comuna_residencia }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $alumno->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>{{ $alumno->telefono }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Historial Académico</h4>
    <!-- Aquí puedes agregar más información relacionada con el expediente, como notas, asistencias, etc. -->

    <a href="{{ route('curso.index') }}" class="btn btn-secondary mt-4">Volver a la Búsqueda de Alumnos</a>
</div>
@endsection
