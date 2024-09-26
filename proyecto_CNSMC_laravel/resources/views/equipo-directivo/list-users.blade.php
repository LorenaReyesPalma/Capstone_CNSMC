@extends('layouts.app')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="container">
    <div class="row align-items-end">
        <div class="col-md-7">
            <div class="form-group">
                <h2>Lista de Usuarios</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <form action="{{ route('add-user') }}" method="GET" class="form-inline">
                    <button type="submit" class="btn btn-primary btn-block"> 
                        <i class="fas fa-user-plus"></i> Añadir Usuarios
                    </button>
                </form>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->category->category }}</td>
                <td>
                    <a href="{{ route('edit-user', $user->user_id) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-pencil"></i> Editar
                    </a>
                    <form action="{{ route('delete-user', $user->user_id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Enlaces de paginación -->
   <div class="d-flex justify-content-center">
    {{ $users->links('vendor.pagination.bootstrap-4') }} <!-- Asegúrate de especificar la vista personalizada -->
</div>

</div>

<script>
    function confirmDelete() {
        return confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');
    }
</script>
@endsection
