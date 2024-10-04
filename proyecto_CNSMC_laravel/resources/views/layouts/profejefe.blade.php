<!-- resources/views/profejefe.blade.php -->
@extends('layouts.app')

@section('title', 'Perfil Profe Jefe')

@section('content')
<div class="container">
    <h2>Perfil Profesor Jefe</h2>

    <!-- Formulario para crear una derivación -->
    <h4>Crear Derivación</h4>
    <form action="{{ route('profejefe.derivacion.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="motivo_derivacion">Motivo de Derivación</label>
            <textarea class="form-control" id="motivo_derivacion" name="motivo_derivacion" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Crear Derivación</button>
    </form>

    <hr>

    <!-- Lista de derivaciones creadas por el usuario -->
    <h4>Mis Derivaciones</h4>
    @if($derivaciones->isEmpty())
        <p>No has creado derivaciones.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($derivaciones as $derivacion)
                <tr>
                    <td>{{ $derivacion->fecha_derivacion }}</td>
                    <td>{{ $derivacion->motivo_derivacion }}</td>
                    <td>
                        <a href="{{ route('profejefe.derivacion.show', $derivacion->id) }}" class="btn btn-info btn-sm">Ver Derivación</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
