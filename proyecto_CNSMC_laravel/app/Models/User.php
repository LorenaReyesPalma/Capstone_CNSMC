<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipoDirectivoController;
use App\Http\Controllers\ConvivenciaEscolarController;
use App\Http\Controllers\ProfesoresJefesController;
use App\Http\Controllers\ProfesoresAsignaturaController;
use App\Http\Controllers\PieController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AlumnoController;



Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/equipo-directivo-profile', [EquipoDirectivoController::class, 'index'])->name('equipo-directivo.profile');    
    
    // crud para nuevos usuarios
    Route::get('/equipo-directivo/add-user', [EquipoDirectivoController::class, 'create'])->name('add-user');
    Route::post('/equipo-directivo/store-user', [EquipoDirectivoController::class, 'store'])->name('store-user');
    
    Route::get('/equipo-directivo/list-users', [EquipoDirectivoController::class, 'listUsers'])->name('list-users');
    Route::get('/equipo-directivo/edit-user/{user_id}', [EquipoDirectivoController::class, 'editUser'])->name('edit-user');
// Ruta para actualizar usuario
    Route::put('/equipo-directivo/update-user/{user_id}', [EquipoDirectivoController::class, 'updateUser'])->name('update-user');
    Route::delete('/equipo-directivo/delete-user/{user_id}', [EquipoDirectivoController::class, 'deleteUser'])->name('delete-user');

    //rutas para mostrar alumnos por curso:

    Route::get('buscar-alumnos', [CursoController::class, 'index'])->name('curso.index');

    // Ruta para procesar la búsqueda de alumnos
    Route::post('/buscar-alumnos/buscar', [CursoController::class, 'buscarAlumnos'])->name('curso.buscar');


// Ruta para procesar la selección de alumnos Controller alumnos y expedientes

    Route::get('alumno/expediente/{run}/{dv}', [AlumnoController::class, 'verExpediente'])->name('alumno.expediente');
    Route::get('/alumno/crear-expediente/{run}/{dv}', [AlumnoController::class, 'crearExpediente'])->name('alumno.crear_expediente');


    Route::get('/convivencia-escolar-profile', [ConvivenciaEscolarController::class, 'index'])->name('convivencia-escolar.profile');
    Route::get('/profesores-jefes-profile', [ProfesoresJefesController::class, 'index'])->name('profesores-jefes.profile');
    Route::get('/profesores-asignatura-profile', [ProfesoresAsignaturaController::class, 'index'])->name('profesores-asignatura.profile');
    Route::get('/pie-profile', [PieController::class, 'index'])->name('pie.profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
