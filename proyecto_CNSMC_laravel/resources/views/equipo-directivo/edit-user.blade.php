@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
            <h2 class="text-primary mb-4">Editar Usuario</h2>
            <form action="{{ route('update-user', $user->user_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <!-- Nombre y Apellido -->
                    <div class="form-group col-md-6">
                        <label for="first_name">Nombre</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="last_name">Apellido</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Email y Rol -->
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="role">Rol</label>
                        <input type="text" class="form-control" id="role" name="role" value="{{ old('role', $user->role) }}">
                    </div>
                </div>

                <div class="form-row">
                    <!-- Imagen y Carga -->
                    <div class="form-group col-md-6">
                        <label for="image">Imagen del Usuario</label>
                        <div>
                            @if($user->image)
                            <img src="{{ asset($user->image) }}" alt="Imagen del usuario" class="img-fluid" style="max-width: 150px; max-height: 150px;">
                            @else
                            <p>No hay imagen disponible.</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="new_image">Cargar Nueva Imagen</label>
                        <input type="file" class="form-control" id="new_image" name="image">
                        <small class="form-text text-muted">Si deseas cambiar la imagen, selecciona un nuevo archivo.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_category">Categoría</label>
                    <select class="form-control" id="id_category" name="id_category" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_category }}" {{ $category->id_category == $user->id_category ? 'selected' : '' }}>
                                {{ $category->category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
            <a href="{{ route('list-users') }}" class="btn btn-secondary mt-4">Volver</a>
        </div>
    </div>
</div>
@endsection
