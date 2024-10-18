<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" style="height: 100vh;
    background-color: #002A45;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>@yield('title', 'Derivaciones Escolares')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body sstyle="height:100%; background-color: #002A45;">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #002A45; mt-2;">
        <div class="container ps-10">
            <a class="navbar-brand" style="color: white;" href="#">Derivaciones Escolares</a>
            <button class="navbar-toggler" type="button"
                style="color: white; background-color: transparent; border: none;" data-toggle="collapse"
                data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="
        background-image: url('data:image/svg+xml,%3Csvg xmlns%3D%27http://www.w3.org/2000/svg%27 viewBox%3D%270 0 30 30%27%3E%3Cpath stroke%3D%27rgba%28255,%20255,%20255,%201%29%27 stroke-width%3D%272%27 d%3D%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E');
    "></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('profesores-jefe.profe-jefeindex') }}">Profesor Jefe</a>

                    </li>
                    <li class="nav-item  d-lg-none">
                        <a class="nav-link" style="color: white;" href="{{ route('derivaciones.estado') }}">
                            <i class="fas fa-exchange-alt"></i> Derivaciones
                        </a>
                    </li>
                    <li class="nav-item  d-lg-none ">
                        <a class="nav-link" href="#" style="color: white;">
                            <i class="fas fa-calendar-alt"></i> Citaciones Apoderado
                        </a>
                    </li>
                    <li class="nav-item  d-lg-none">
                        <a class="nav-link" style="color: white;" href="{{ route('list-users') }}">
                            <i class="fas fa-user"></i> Colaboradores
                        </a>
                    </li>
                    <li class="nav-item  d-lg-none">
                        <a class="nav-link" href="{{ route('curso.index') }}" style="color: white;">
                            <i class="fas fa-search"></i> Expedientes
                        </a>
                    </li>
                    <li class="nav-item  d-lg-none">
                        <a class="nav-link" href="#" style="color: white;">
                            <i class="fas fa-chart-bar"></i> Estadísticas
                        </a>
                    </li>
                    <!-- Agrega más enlaces de navegación aquí -->
                </ul>
                <ul class="navbar-nav ml-auto">
                    @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="form-inline">
                            @csrf
                            <button style="color: white; background-color: transparent; border: none;" type="submit"
                                class="btn">
                                <i class="fas fa-sign-out-alt" style="color: white;"></i> Logout
                            </button>
                        </form>
                    </li>

                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Barra lateral -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar d-none shadow">

                <div class="sidebar-sticky" style=" height: 90vh; ">
                    @auth
                    <div class="user-info text-center p-3 position-relative">
                        <!-- Ícono de editar para abrir el modal de edición de usuario -->
                        <button type="button" class="btn btn-link position-absolute" style="top: 10px; right: 10px;"
                            data-toggle="modal" data-target="#editUserModal">
                            <i class="fas fa-edit" style="font-size: 1.5rem; color: #007bff;"></i>
                            <!-- Cambia el color según sea necesario -->
                        </button>

                        <!-- Imagen de perfil del usuario -->
                        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('storage/images/default.jpg') }}"
                            class="rounded-circle img-fluid mb-3"
                            style="width: 180px; height: 180px; box-shadow: 0 3px 5px rgba(0, 0, 0, 0.3); object-fit: cover; object-position: center;"
                            alt="Imagen de perfil">

                        <h5>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        <p>{{ Auth::user()->role }}</p>
                    </div>

                    @endauth
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" style="color: #002A45;" href="{{ route('derivaciones.estado') }}">
                                <i class="fas fa-exchange-alt"></i> Derivaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('citacionShow') }}" style="color: #002A45;">
                                <i class="fas fa-calendar-alt"></i> Citaciones Apoderado
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('list-users') }}" style="color: #002A45;">
                                <i class="fas fa-user"></i> Colaboradores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('curso.index') }}" style="color: #002A45;">
                                <i class="fas fa-search"></i> Expedientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="color: #002A45;">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </a>
                        </li>
                        <!-- Agrega más secciones aquí -->
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ml-sm-auto col-lg-10 mt-4 shadow d-flex flex-column">
            <div class="flex-grow-1">

                    @yield('content')

                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </div>
                <footer class="bg-light text-center py-3 mt-1" style="margin-top: auto;">
                    <div class="container">
                        <p>&copy; {{ date('Y') }} Derivaciones Escolares. Todos los derechos reservados.</p>
                    </div>
                </footer>

            </main>
        </div>
    </div>



    <!-- Modal para editar información del usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <!-- Modal más grande -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Información de Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @auth
                    <form id="editUserForm" method="POST" action="{{ route('update-user', auth()->user()->user_id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Primer grupo de columnas -->
                                <!-- Campo para el nombre -->
                                <div class="form-group">
                                    <label for="first_name">Nombre</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{{ Auth::user()->first_name }}" required>
                                </div>

                                <!-- Campo para el apellido -->
                                <div class="form-group">
                                    <label for="last_name">Apellido</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="{{ Auth::user()->last_name }}" required>
                                </div>

                                <!-- Campo para el correo electrónico -->
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ Auth::user()->email }}" required>
                                </div>

                                <!-- Campo para la contraseña (opcional) -->
                                <div class="form-group">
                                    <label for="password">Contraseña (opcional)</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Dejar en blanco para mantener la misma contraseña">
                                </div>

                                <!-- Campo para confirmar la contraseña -->
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Repetir la contraseña">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Segundo grupo de columnas -->
                                <!-- Campo para seleccionar la categoría -->
                                <input type="hidden" id="id_category" name="id_category"
                                    value="{{ Auth::user()->id_category }}">

                                <!-- Campo para seleccionar el rol (opcional) -->
                                <div class="form-group">
                                    <label for="role">Rol (opcional)</label>
                                    <input type="text" class="form-control" id="role" name="role"
                                        value="{{ Auth::user()->role }}">
                                </div>

                                <!-- Campo para subir la imagen -->
                                <div class="form-group">
                                    <label for="image">Imagen de Perfil</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                    <small class="form-text text-muted">Tamaño máximo de 2MB. Formatos permitidos: jpeg,
                                        png,
                                        jpg, gif.</small>

                                    <!-- Mostrar la imagen actual si existe -->
                                    @if(Auth::user()->image)
                                    <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Imagen del usuario"
                                        class="img-fluid mt-2" width="100">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>



    <!-- JS de Bootstrap -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>