@extends('layouts.app')

@section('title', 'Añadir Usuario')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
        <h2>Añadir Nuevo Usuario</h2>
        <form action="{{ route('store-user') }}" method="POST" id="userForm" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <!-- Nombre y Apellido juntos -->
                <div class="form-group col-md-6">
                    <label for="first_name">Nombre</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="last_name">Apellido</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>

            <!-- Email generado automáticamente -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required readonly>
            </div>

            <div class="form-row">
                <!-- Categoría y Rol juntos -->
                <div class="form-group col-md-6">
                    <label for="id_category">Categoría</label>
                    <select class="form-control" id="id_category" name="id_category" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_category }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="role">Rol</label>
                    <input type="text" class="form-control" id="role" name="role">
                </div>
            </div>

            <!-- Campos de contraseña y confirmación -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <!-- Campo de carga de imagen -->
            <div class="form-group">
                <label for="image">Imagen</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Usuario</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    function removeAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    function generateEmail() {
        const firstName = firstNameInput.value.split(' ')[0] || ''; // Primer nombre
        const lastName = lastNameInput.value.split(' ')[0] || ''; // Primer apellido

        // Remover acentos y espacios, y generar el correo en minúsculas
        if (firstName && lastName) {
            const formattedFirstName = removeAccents(firstName).toLowerCase();
            const formattedLastName = removeAccents(lastName).toLowerCase();
            emailInput.value = `${formattedFirstName}.${formattedLastName}@marianistasmelipilla.cl`;
        } else {
            emailInput.value = '';
        }
    }

    firstNameInput.addEventListener('input', generateEmail);
    lastNameInput.addEventListener('input', generateEmail);

    // Validación de la contraseña y confirmación
    const userForm = document.getElementById('userForm');
    userForm.addEventListener('submit', function(event) {
        if (passwordInput.value !== passwordConfirmationInput.value) {
            event.preventDefault();
            alert('Las contraseñas no coinciden. Por favor, verifica e inténtalo de nuevo.');
        }
    });
});
</script>
@endsection
