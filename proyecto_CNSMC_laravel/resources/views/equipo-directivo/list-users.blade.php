@extends('layouts.app')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="container mt-1">
    <div class="row align-items-end mb-1">
        <div class="col-md-8">
            <h4 class="text-primary">Lista de Usuarios</h4>
        </div>

    </div>

    <div class="row mb-3 align-items-center">
        <!-- Formulario para filtrar por categoría a la izquierda -->
        <div class="col-md-8 d-flex align-items-center">
            <form action="{{ route('list-users') }}" method="GET" class="d-flex w-100">
                <select id="categorySelect" name="id_category" class="form-control me-2" onchange="updateHiddenField()">
                    <option value="">Seleccionar Categoría</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id_category }}"
                        {{ request('id_category') == $category->id_category ? 'selected' : '' }}>
                        {{ $category->category }}
                    </option>
                    @endforeach
                </select>
                <input type="hidden" id="selectedCategory" name="selectedCategory" value="{{ request('id_category') }}">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>

        <!-- Botón para añadir usuarios a la derecha -->
        <div class="col-md-4 text-end">
            <form action="{{ route('add-user') }}" method="GET">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Añadir Usuarios
                </button>
            </form>
        </div>
    </div>


    <script>
    function updateHiddenField() {
        const select = document.getElementById('categorySelect');
        const hiddenInput = document.getElementById('selectedCategory');
        hiddenInput.value = select.value; // Actualiza el valor del campo oculto con el valor seleccionado
    }
    </script>



    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm ">
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
                    <td class="d-flex justify-content-evenly">
                        <a href="{{ route('edit-user', $user->user_id) }}" class="text-primary text-decoration-none"
                            title="Editar">
                            <i class="fa fa-pencil fs-5"></i>
                        </a>
                        <form action="{{ route('delete-user', $user->user_id) }}" method="POST"
                            onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-danger" title="Eliminar"
                                style="border: none; background: none; padding: 0;">
                                <i class="fa fa-trash fs-5"></i>
                            </button>
                        </form>
                    </td>


                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
    </div>


</div>

<script>
function confirmDelete() {
    return confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');
}
</script>
@endsection