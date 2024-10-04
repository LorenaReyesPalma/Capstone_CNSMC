<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Enlace a Bootstrap desde CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Enlace a FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlace al archivo CSS local -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container col-lg-5 col-md-6 col-sm-11 col-11 mt-5">
        <h5 class="text-center">
            Colegio Nuestra Señora y Madre del Carmen
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="width: 50px; height: 50px;">
        </h5>

        <h1 class="text-center">Bienvenido/a !</h1>
        <p class="text-center mt-3">Ingresa tus credenciales de acceso para acceder al sistema de derivaciones escolares</p>
        <form method="post" action="{{ route('login.post') }}">
            @csrf
            <!-- Campo de Email con ícono de usuario -->
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="Correo Institucional" autocomplete="email">
                </div>
            </div>

            <!-- Campo de Contraseña con ícono para mostrar la contraseña -->
            <div class="form-group mt-3">
                <label for="password">Password:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Ingresa tu contraseña" autocomplete="current-password">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 w-100">Login</button>
        </form>

        <!-- Botón para abrir el modal de recuperación de contraseña -->
        <div class="text-center mt-3">
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#forgotPasswordModal">
                ¿Olvidaste tu contraseña?
            </button>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Modal de Recuperación de Contraseña -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar Contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('password.email') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recovery-email">Ingresa tu correo electrónico para recibir un enlace de recuperación:</label>
                            <input type="email" name="email" id="recovery-email" class="form-control" required placeholder="Correo Institucional" autocomplete="email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery para el modal -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script para alternar la visibilidad de la contraseña -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Alternar el tipo de input entre 'password' y 'text'
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Cambiar el icono entre ojo abierto y cerrado
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
