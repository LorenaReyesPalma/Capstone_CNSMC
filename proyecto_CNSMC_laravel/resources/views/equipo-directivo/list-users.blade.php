@extends('layouts.app')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="container mt-2">
    <div class="row align-items-end mb-2">
        <div class="col-md-8">
            <h2 class="text-primary">Lista de Usuarios</h2>
        </div>
        <div class="col-md-4">
            <form action="{{ route('add-user') }}" method="GET" class="form-inline">
                <button type="submit" class="btn btn-primary btn-block"> 
                    <i class="fas fa-user-plus"></i> Añadir Usuarios
                </button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
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
    </div>

    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center
    ">
        {{ $users->links('vendor.pagination.bootstrap-4') }}
    </div>

</div>

<script>
    function confirmDelete() {
        return confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');
    }
</script>
@endsection
