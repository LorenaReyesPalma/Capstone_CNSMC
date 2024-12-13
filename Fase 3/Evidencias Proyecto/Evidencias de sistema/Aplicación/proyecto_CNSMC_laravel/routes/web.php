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
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\EstadisticaController;

use Illuminate\Support\Facades\Password;
use App\Http\Controllers\EntrevistaController;


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

    Route::get('/api/comunas/{regionId}', function($regionId) {
        $response = Http::get("https://apis.digital.gob.cl/dpa/regiones/{$regionId}/comunas");
        return $response->json();
    });
    
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
    Route::get('/buscar-alumnos', [CursoController::class, 'index'])->name('curso.index');
    Route::get('/buscar-alumnos/indexDos', [CursoController::class, 'indexDos'])->name('curso.indexDos');
    Route::get('/buscar-alumnos/buscar', [CursoController::class, 'buscarAlumnos'])->name('curso.buscar');

    // Rutas para los expedientes de alumnos
    Route::get('alumno/expediente/{run}/{dv}', [AlumnoController::class, 'verExpediente'])->name('alumno.expediente');
    Route::post('/alumno/expediente/{run}/{dv}', [AlumnoController::class, 'crearExpediente'])->name('expediente.create'); // Cambiado a POST para crear
    Route::put('/expediente/{run}/{dv}', [AlumnoController::class, 'updateExpediente'])->name('expediente.update');
    Route::get('alumno/expediente/show/{run}/{dv}', [AlumnoController::class, 'showExpediente'])->name('expediente.show');
    Route::get('/comunas/{codigoRegion}', [AlumnoController::class, 'cargarComunas']);

    // derivaciones
    Route::get('/derivaciones/derivacion-crear/{run}/{dv}', [DerivacionController::class, 'create'])->name('derivaciones.derivacion-crear');
    Route::post('/derivaciones/derivacion/store', [DerivacionController::class, 'store'])->name('derivacion.store');
    Route::get('/derivaciones/derivacion/{id}', [DerivacionController::class, 'show'])->name('derivacion.show');
    
    Route::get('/derivaciones/derivacion/{id}/edit', [DerivacionController::class, 'edit'])->name('derivacion.edit');
    Route::put('/derivaciones/derivacion/{id}', [DerivacionController::class, 'update'])->name('derivacion.update');
    Route::put('/derivaciones/{id}/actualizar-indicadores', [DerivacionController::class, 'actualizarIndicadores'])->name('actualizarIndicadores');
    Route::put('/derivaciones/{id}/actualizar-motivo-acciones', [DerivacionController::class, 'actualizarMotivoAcciones'])->name('actualizarMotivoAcciones');
    Route::delete('/derivaciones/derivacion/{id}', [DerivacionController::class, 'destroy'])->name('derivacion.destroy');
    // Ruta para listar todas las derivaciones
    Route::get('/derivaciones/derivaciones-estado', [DerivacionController::class, 'listarDerivaciones'])->name('derivaciones.estado');
    Route::put('/derivaciones/{id}/estado/{nuevoEstado}', [DerivacionController::class, 'cambiarEstado'])->name('derivaciones.cambiarEstado');


        
    // citaciones
    Route::post('/derivaciones/derivaciones/{id}/citacion', [CitacionController::class, 'store'])->name('citaciones.store');
    Route::delete('/citacion/cancelar/{id}', [DerivacionController::class, 'cancelarCitacion'])->name('citacion.cancelar');
    Route::get('buscarAlumnos2', [CitacionController::class, 'buscarAlumnos2'])->name('buscarAlumnos2');

    // entrvista alumno
    // Route::get('/entrevistas/{id}/entrevista-alumno', [DerivacionController::class, 'entrevistaAlumno'])->name('entrevistas.entrevistaAlumno');
    // Route::get('/entrevistas/{id}/entrevista-alumno/{tipo_entrevista}', [DerivacionController::class, 'entrevistaAlumno'])->name('entrevistas.entrevistaAlumno');
    Route::get('/entrevistas/{id}/entrevista-alumno/{tipo_entrevista}/{citacion}', [DerivacionController::class, 'entrevistaAlumno'])->name('entrevistas.entrevistaAlumno');
    Route::get('/entrevistas/{id}/entrevista-apoderado/{tipo_entrevista}/{citacion}', [DerivacionController::class, 'entrevistaApoderado'])->name('entrevistas.entrevistaApoderado');
    Route::get('/entrevistas/{id}/entrevista-compromiso/{tipo_entrevista}/{citacion}', [DerivacionController::class, 'entrevistaCompromiso'])->name('entrevistas.entrevistaCompromiso');
    Route::get('/entrevistas/citacion-apoderado/{tipo_entrevista}/{citacion}', [DerivacionController::class, 'citacionApoderado'])->name('entrevistas.citacionApoderado');
    Route::delete('/entrevistas/{id}', [EntrevistaController::class, 'destroy'])->name('entrevista.destroy');


    Route::get('/entrevistas/create/{id}', [EntrevistaController::class, 'create'])->name('entrevista.create');
    Route::post('/entrevistas/store', [EntrevistaController::class, 'store'])->name('entrevista.store');
    Route::get('/entrevistas', [EntrevistaController::class, 'index'])->name('entrevista.index');
    Route::put('/entrevistas/{id}/entrevista-alumno/{tipo_entrevista}', [EntrevistaController::class, 'update'])->name('entrevista.update');

// citaciones

    Route::get('/citaciones/citacion', [CitacionController::class, 'citacionShow'])->name('citacionShow');

    // Route::get('/profesores-jefes-profile', [ProfesoresJefesController::class, 'index'])->name('profesores-jefes.profile');
    Route::get('/pie-profile', [PieController::class, 'index'])->name('pie.profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Ruta para obtener citaciones (para mostrar en el calendario)
    Route::get('/citaciones/obtener', [CitacionController::class, 'obtenerCitaciones'])->name('citaciones.obtener');
    Route::post('/citaciones/guardar', [CitacionController::class, 'guardarCitacion'])->name('citaciones.guardar');
 
    Route::get('/check-citaciones', [CitacionController::class, 'checkCitaciones']);


    
    // Rutas para Profesor Jefe
  
    Route::post('/profejefe/derivacion/store', [ProfesoresJefesController::class, 'store'])->name('profejefe.derivacion.store');
    Route::get('/profejefe/derivacion/{id}', [ProfesoresJefesController::class, 'show'])->name('profejefe.derivacion.show');

    Route::get('/profesores-jefes/profe-jefeindex', [ProfesoresJefesController::class, 'index'])->name('profesores-jefes.profe-jefeindex');
    // profesor-asignaturas
    Route::get('/profesores-asignatura/profe-asignatura-index', [ProfesoresAsignaturaController::class, 'index'])->name('profesores-asignatura.profe-asignatura-index');
    // PIE
    Route::get('/pie/pie-index', [PieController::class, 'index'])->name('pie.pie-index');
    // convivencia escolar
    Route::get('/convivencia/convivencia-index', [ConvivenciaEscolarController::class, 'index'])->name('convivencia.convivencia-index');
    Route::put('/derivaciones/aceptar/{id}', [ConvivenciaEscolarController::class, 'aceptarDerivacion'])->name('derivaciones.aceptar');

    // asignar curso
  
    Route::post('/asignar-profesor-curso', [CursoController::class, 'asignarProfesorCurso'])->name('asignar.profesor.curso');
    Route::post('/quitar-profesor-curso', [CursoController::class, 'quitarProfesorCurso'])->name('quitar.profesor.curso');

    // carga de bbdd

    Route::post('/cargar-matricula', [MatriculaController::class, 'cargarArchivo'])->name('matricula.cargar');

    //estadisticas
    Route::get('/estadisticas', [EstadisticaController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/estadisticas/getDerivaciones', [EstadisticaController::class, 'getDerivaciones']);
    Route::get('/estadisticas/getDerivacionesEstado', [EstadisticaController::class, 'getDerivacionesEstado']);
    Route::get('/estadisticas/getDatosGraficosCombinados', [EstadisticaController::class, 'getDatosGraficosCombinados']);
    Route::get('/estadisticas/getDatosGraficos', [EstadisticaController::class, 'getDatosGraficos']);
    Route::get('/estadisticas/getDatosGraficosFamiliar', [EstadisticaController::class, 'getDatosGraficosFamiliar']);
    Route::get('/estadisticas/getDatosGraficosSocial', [EstadisticaController::class, 'getDatosGraficosSocial']);
    Route::get('/estadisticas/obtenerCuentaProgramas', [EstadisticaController::class, 'obtenerCuentaProgramas']);
    Route::get('/estadisticas/obtenerEstadoDerivaciones', [EstadisticaController::class, 'obtenerEstadoDerivaciones'])->name('obtenerEstadoDerivaciones');
    Route::get('/estadisticas/exportar-estadisticas', [EstadisticaController::class, 'exportarExcel']);
    Route::get('/estadisticas/promedio-cambio-estado-mes',  [EstadisticaController::class,  'promedioCambioEstadoMes']);


    Route::get('/promedio-cambio-estado',  [DerivacionController::class,  'promedioCambioEstado']);

    
});