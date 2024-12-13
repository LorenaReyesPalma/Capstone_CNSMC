@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Resultados de la Búsqueda</h1>

    @if($alumnos->isEmpty())
    <p>No se encontraron alumnos para el curso seleccionado.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <!-- Agrega más columnas según tus necesidades -->
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $alumno)
            <tr>
                <td>{{ $alumno->nombre }}</td>
                <td>{{ $alumno->apellido }}</td>
                <!-- Agrega más columnas según tus necesidades -->
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection