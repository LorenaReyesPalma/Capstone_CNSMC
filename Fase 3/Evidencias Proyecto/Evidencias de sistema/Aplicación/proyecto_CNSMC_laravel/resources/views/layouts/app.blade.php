<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html5 lang="es" style="height: 100vh;
    background-color: #002A45;">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <title>@yield('title', 'Derivaciones Escolares')</title>

        <!-- Enlaces a las bibliotecas jQuery y FullCalendar -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.10.0/locales/es.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>


        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

    </head>



    <body sstyle="height:100%; background-color: #002A45;">

        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #002A45; mt-2;">
            <div class="container ps-10">
                <a class="navbar-brand" style="color: white;">Derivaciones Escolares</a>
                <button class="navbar-toggler" type="button"
                    style="color: white; background-color: transparent; border: none;" data-toggle="collapse"
                    data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3Csvg xmlns%3D%27http://www.w3.org/2000/svg%27 viewBox%3D%270 0 30 30%27%3E%3Cpath stroke%3D%27rgba%28255,%20255,%20255,%201%29%27 stroke-width%3D%272%27 d%3D%27M4 7h22M4 15h22M4 23h22%27/%3E%3C/svg%3E');"></span>
                </button>

                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
                <ul class="navbar-nav mr-auto">


                        @auth
                        @if (Auth::user()->id_category == 1) {{-- Equipo Directivo --}}
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;"
                                href="{{ route('equipo-directivo.profile') }}">Equipo
                                Directivo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-lg-none" style="color: white;"
                                href="{{ route('derivaciones.estado') }}">
                                <i class="fas fa-exchange-alt"></i> Derivaciones
                            </a>
                        </li>

                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('list-users') }}" style="color: white;">
                                <i class="fas fa-user"></i> Colaboradores
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('estadisticas') }}" style="color: white;">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </a>
                        </li>

                        @elseif (Auth::user()->id_category == 2) {{-- Convivencia Escolar --}}
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;"
                                href="{{ route('convivencia.convivencia-index') }}">Convivencia Escolar</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('estadisticas') }}" style="color: white;">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </a>
                        </li>



                        @elseif (Auth::user()->id_category == 3) {{-- Profesores Jefes --}}
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;"
                                href="{{ route('profesores-jefes.profe-jefeindex') }}">Profesores Jefes</a>
                        </li>

                        @elseif (Auth::user()->id_category == 4) {{-- Profesores Asignatura --}}
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;"
                                href="{{ route('profesores-asignatura.profe-asignatura-index') }}">Profesores Asignatura</a>
                        </li>
                        @elseif (Auth::user()->id_category == 5) {{-- PIE --}}
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="{{ route('pie.pie-index') }}">Equipo PIE</a>
                        </li>
                        @endif
                        @endauth

                        <!-- Enlaces comunes -->
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('derivaciones.estado') }}" style="color: white;">
                                <i class="fas fa-exchange-alt"></i> Derivaciones
                            </a>
                        </li>

                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('curso.index') }}" style="color: white;">
                                <i class="fas fa-search"></i> Expedientes
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="#" style="color: white;">
                                <i class="fas fa-calendar-alt"></i> Citaciones Apoderado
                            </a>
                        </li>


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
            <div class="row cont">
                <!-- Barra lateral -->
                <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar d-none shadow">

                    <div class="sidebar-sticky" style=" height: 90vh; ">
                        @auth
                        <div class="user-info text-center p-3 position-relative">
                            <!-- Ícono de editar para abrir el modal de edición de usuario -->
                            <button type="button" class="btn btn-link position-absolute" style="top: 10px; right: 10px;"
                                data-toggle="modal" data-target="#editUserModal">
                                <i class="fas fa-edit" style="font-size: 1.5rem; color: #007bff;"></i>
                            </button>



                            <!-- Imagen de perfil del usuario -->
                            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('storage/images/perfil.jpg') }}"
                                class="rounded-circle img-fluid mb-3"
                                style="width: 180px; height: 180px; box-shadow: 0 3px 5px rgba(0, 0, 0, 0.3); object-fit: cover; object-position: center;"
                                alt="Imagen de perfil">

                            <h5>{{ strtoupper(Auth::user()->first_name) }} {{ strtoupper(Auth::user()->last_name) }}
                            </h5>
                            <p>{{ Auth::user()->role }}</p>
                        </div>

                        @endauth
                        <ul class="nav flex-column">

                            <!-- Enlace común a todas las categorías -->
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
                                <a class="nav-link" href="{{ route('curso.index') }}" style="color: #002A45;">
                                    <i class="fas fa-search"></i> Expedientes
                                </a>
                            </li>
                            <!-- Agrega más secciones aquí -->
                            @auth
                            @if (Auth::user()->id_category == 1) {{-- Equipo Directivo --}}

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('list-users') }}" style="color: #002A45;">
                                    <i class="fas fa-user"></i> Colaboradores
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('estadisticas') }}" style="color: #002A45;">
                                    <i class="fas fa-chart-bar"></i> Estadísticas
                                </a>
                            </li>

                            @elseif (Auth::user()->id_category == 2) {{-- Convivencia Escolar --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('estadisticas') }}" style="color: #002A45;">
                                    <i class="fas fa-chart-bar"></i> Estadísticas
                                </a>
                            </li>
                          

                            @endif
                            @endauth


                        </ul>

                    </div>
                </nav>

                <!-- Contenido principal -->
                <main class="col-md-9 ml-sm-auto col-lg-10 mt-4  d-flex flex-column">
                <div class="flex-grow-1">
    <!-- Mostrar alertas arriba del contenido -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-3" role="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-3" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
        {{ session('success') }}
        <button type="button" class="close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-3" role="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
        {{ session('error') }}
        <button type="button" class="close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Contenido principal -->
    @yield('content')
</div>


                    <footer class="bg-light text-center" style="margin-top: auto;">
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Información de Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @auth
                        <form id="editUserForm" method="POST" action="{{ route('update-user', Auth::user()->user_id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Primer grupo de columnas -->
                                    <div class="form-group">
                                        <label for="first_name">Nombre</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="{{ Auth::user()->first_name }}" required>
                                        @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Apellido</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="{{ Auth::user()->last_name }}" required>
                                        @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <!-- Checkbox para cambiar contraseña -->
                                    <div class="form-group">
                                        <input type="checkbox" id="changePassword" name="changePassword">
                                        <label for="changePassword">Cambiar Contraseña</label>
                                    </div>

                                    <!-- Campo para la contraseña actual -->
                                    <div class="form-group d-none" id="currentPasswordGroup">
                                        <label for="current_password">Contraseña Actual</label>
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password">
                                        @error('current_password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Campo para la nueva contraseña -->
                                    <div class="form-group d-none" id="newPasswordGroup">
                                        <label for="password">Nueva Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Mínimo 8 caracteres">
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                     <!-- Campo para confirmar la nueva contraseña -->
                                     <div class="form-group d-none" id="passwordConfirmationGroup">
                                        <br>
                                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                        @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>

                                <div class="col-md-6">
                                    <!-- Segundo grupo de columnas -->
                                    <input type="hidden" id="id_category" name="id_category"
                                        value="{{ Auth::user()->id_category }}">

                                    <div class="form-group">
                                        <label for="image">Imagen de Perfil</label>
                                        <input type="file" class="form-control-file" id="image" name="image">
                                        <small class="form-text text-muted">Tamaño máximo de 2MB. Formatos permitidos:
                                            jpeg,
                                            png, jpg, gif.</small>
                                        @if(Auth::user()->image)
                                        <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                            alt="Imagen del usuario" class="img-fluid mt-2" width="100">
                                        @endif
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" form="editUserForm">Guardar
                                    Cambios</button>
                            </div>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Mostrar/ocultar campos de contraseña según el checkbox
        document.getElementById('changePassword').addEventListener('change', function() {
            const currentPasswordGroup = document.getElementById('currentPasswordGroup');
            const newPasswordGroup = document.getElementById('newPasswordGroup');
            const passwordConfirmationGroup = document.getElementById('passwordConfirmationGroup');

            if (this.checked) {
                currentPasswordGroup.classList.remove('d-none');
                newPasswordGroup.classList.remove('d-none');
                passwordConfirmationGroup.classList.remove('d-none');
            } else {
                currentPasswordGroup.classList.add('d-none');
                newPasswordGroup.classList.add('d-none');
                passwordConfirmationGroup.classList.add('d-none');

                // Limpiar los campos de contraseña
                document.getElementById('current_password').value = '';
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
            }
        });
        </script>




        <!-- JS de Bootstrap -->
        <script src="{{ asset('js/app.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

</html5>