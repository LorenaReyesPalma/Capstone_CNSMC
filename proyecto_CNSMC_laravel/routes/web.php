<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipoDirectivoController;
use App\Http\Controllers\ConvivenciaEscolarController;
use App\Http\Controllers\ProfesoresJefesController;
use App\Http\Controllers\ProfesoresAsignaturaController;
use App\Http\Controllers\PieController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DerivacionController;
use App\Http\Controllers\CitacionController;
use Illuminate\Support\Facades\Password;

// Rutas para el inicio de sesión
Route::get('/login', function () {
    return view('login');
})->name('login');

// Ruta para procesar el inicio de sesión
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    // Rutas para restablecimiento de contraseñas
    Route::get('password/reset', function () {
        return view('auth.passwords.email');  // Vista de solicitud de restablecimiento
    })->name('password.request');
    
    Route::post('password/email', [AuthController::class, 'enviarRecuperacion'])->name('password.email');
    
    Route::get('password/reset/{token}', function ($token) {
        return view('auth.passwords.reset', ['token' => $token]); // Vista para restablecer la contraseña
    })->name('password.reset');
    
    Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');


// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
   // CRUD y demás rutas ya definidas

    Route::get('/equipo-directivo-profile', [EquipoDirectivoController::class, 'index'])->name('equipo-directivo.profile');

    // CRUD para nuevos usuarios
    Route::get('/equipo-directivo/add-user', [EquipoDirectivoController::class, 'create'])->name('add-user');
    Route::post('/equipo-directivo/store-user', [EquipoDirectivoController::class, 'store'])->name('store-user');
    
    Route::get('/equipo-directivo/list-users', [EquipoDirectivoController::class, 'listUsers'])->name('list-users');
    Route::get('/equipo-directivo/edit-user/{user_id}', [EquipoDirectivoController::class, 'editUser'])->name('edit-user');
    Route::put('/equipo-directivo/update-user/{user_id}', [EquipoDirectivoController::class, 'updateUser'])->name('update-user');
    Route::delete('/equipo-directivo/delete-user/{user_id}', [EquipoDirectivoController::class, 'deleteUser'])->name('delete-user');

    // Rutas para mostrar alumnos por curso
    Route::get('buscar-alumnos', [CursoController::class, 'index'])->name('curso.index');
    Route::post('/buscar-alumnos/buscar', [CursoController::class, 'buscarAlumnos'])->name('curso.buscar');

    // Rutas para los expedientes de alumnos
    Route::get('alumno/expediente/{run}/{dv}', [AlumnoController::class, 'verExpediente'])->name('alumno.expediente');
    Route::post('/alumno/expediente/{run}/{dv}', [AlumnoController::class, 'crearExpediente'])->name('expediente.create'); // Cambiado a POST para crear
    Route::put('/expediente/{run}/{dv}', [AlumnoController::class, 'updateExpediente'])->name('expediente.update');
    Route::get('alumno/expediente/show/{run}/{dv}', [AlumnoController::class, 'showExpediente'])->name('expediente.show');
    Route::get('/comunas/{codigoRegion}', [AlumnoController::class, 'cargarComunas']);

    // derivaciones
    Route::get('/derivacion/{run}/{dv}', [DerivacionController::class, 'create'])->name('derivacion.create');
    Route::post('/derivacion/store', [DerivacionController::class, 'store'])->name('derivacion.store');
    Route::get('/derivacion/{id}', [DerivacionController::class, 'show'])->name('derivacion.show');

    // citaciones
    Route::post('/derivaciones/{id}/citacion', [CitacionController::class, 'store'])->name('citaciones.store');

    Route::get('/convivencia-escolar-profile', [ConvivenciaEscolarController::class, 'index'])->name('convivencia-escolar.profile');
    Route::get('/profesores-jefes-profile', [ProfesoresJefesController::class, 'index'])->name('profesores-jefes.profile');
    Route::get('/profesores-asignatura-profile', [ProfesoresAsignaturaController::class, 'index'])->name('profesores-asignatura.profile');
    Route::get('/pie-profile', [PieController::class, 'index'])->name('pie.profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas para Profesor Jefe
    Route::middleware('auth')->group(function () {
    Route::get('/profejefe/profile', [ProfeJefeController::class, 'index'])->name('profejefe.profile');
    Route::post('/profejefe/derivacion/store', [ProfeJefeController::class, 'store'])->name('profejefe.derivacion.store');
    Route::get('/profejefe/derivacion/{id}', [ProfeJefeController::class, 'show'])->name('profejefe.derivacion.show');
});

});
