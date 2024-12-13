<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Asegúrate de importar Carbon para manejar fechas
use App\Models\Derivacion;
use App\Models\User;
use App\Models\Matricula;
use App\Models\Expediente;
use App\Models\Citacion;
use App\Models\Entrevista;
use App\Models\MotivoEntrevista;
use Illuminate\Support\Facades\Log;
use App\Models\CambioEstadoDerivacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Http\Controllers\AlumnoController;



class DerivacionController extends Controller
{

    protected $alumnoController;

    public function __construct(AlumnoController $alumnoController)
    {
        $this->alumnoController = $alumnoController;
    }


    public function create($run, $dv)
    {
        Log::info('Iniciando creación de derivación', ['run' => $run, 'dv' => $dv]);
    
        // Obtener el alumno de la base de datos
        $alumno = Matricula::where('run', $run)->where('digito_ver', $dv)->first();
        Log::info('Resultado de búsqueda de alumno', ['alumno' => $alumno]);
    
        // Verificar que el alumno existe
        if (!$alumno) {
            Log::warning('Alumno no encontrado', ['run' => $run, 'dv' => $dv]);
            abort(404, 'Alumno no encontrado');
        }
    
        // Obtener el expediente relacionado con el alumno
        $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();
        Log::info('Resultado de búsqueda de expediente', ['expediente' => $expediente]);
    
        // Verificar que el expediente existe
        if (!$expediente) {
            Log::warning('Expediente no encontrado, creando uno nuevo', ['run' => $run, 'dv' => $dv]);
    
            // Crear el expediente
            $this->alumnoController->crearExpediente($run, $dv);
    
            // Buscar nuevamente el expediente
            $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();
            Log::info('Resultado de búsqueda de expediente tras creación', ['expediente' => $expediente]);
    
            if (!$expediente) {
                Log::error('No se pudo crear el expediente', ['run' => $run, 'dv' => $dv]);
                abort(404, 'No se pudo crear el expediente');
            }
        }
    
        // Obtener el teléfono y adulto responsable
        $telefono = $expediente->telefono ?? 'Sin teléfono';
        $adulto = $expediente->adulto_responsable ?? 'Sin adulto responsable';
        Log::info('Datos del expediente', ['telefono' => $telefono, 'adulto_responsable' => $adulto]);
    
        // Obtener la fecha actual
        $fechaActual2 = Carbon::now(); // Fecha actual
        $fechaActual = now()->format('Y-m-d');
    
        // Validar la fecha de nacimiento
        $fechaNacimiento = Carbon::parse($alumno->fecha_nacimiento ?? '1900-01-01');
        $nombre_completo = $alumno->nombres . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno;
        Log::info('Datos del alumno', ['nombre_completo' => $nombre_completo, 'fecha_nacimiento' => $fechaNacimiento]);
    
        // Calcular la edad
        if ($fechaNacimiento > $fechaActual2) {
            $edad = 0;
            Log::warning('Fecha de nacimiento futura detectada', ['fecha_nacimiento' => $fechaNacimiento]);
        } else {
            $edad = (int) $fechaNacimiento->diffInYears($fechaActual2);
        }
    
        Log::info('Edad calculada', ['edad' => $edad]);


    
        // Retornar la vista y pasar los datos del alumno
        Log::info('Adulto responsable enviado a la vista', ['adulto' => $adulto]);
        return view('derivaciones.derivacion-crear', compact('run', 'dv', 'alumno', 'edad', 'nombre_completo', 'fechaActual', 'telefono', 'expediente', 'adulto'));
    }
    
    public function store(Request $request)
{
    // Validar los datos
    $validatedData = $request->validate([
        'nombre_estudiante' => 'required|string|max:255',
        'edad' => 'required|integer',
        'curso' => 'required|string|max:255',
        'adulto_responsable' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        // Añade más validaciones según tus necesidades
    ]);

    try {
        // Obtener el usuario autenticado
        $colaborador = Auth::user(); // Obtenemos el usuario autenticado

        // Obtener la fecha actual en el formato deseado
        $fechaActual = Carbon::now()->format('Y-m-d');

        // Crear la derivación con el colaborador asignado, el estado_id predeterminado y la fecha actual
        $derivacion = Derivacion::create([
            'run' => $request->run,
            'digito_ver' => $request->digito_ver,
            'nombre_estudiante' => $request->nombre_estudiante,
            'edad' => $request->edad,
            'curso' => $request->curso,
            'fecha_derivacion' => $fechaActual, // Guardar la fecha actual
            'adulto_responsable' => $request->adulto_responsable,
            'telefono' => $request->telefono,
            'programa_integracion' => $request->has('programa_integracion'),
            'programa_retencion' => $request->has('programa_retencion'),
            'indicadores_personal' => json_encode($request->indicador_personal),
            'indicadores_familiar' => json_encode($request->indicador_familiar),
            'indicadores_socio_comunitario' => json_encode($request->indicador_socio_comunitario),
            'motivo_derivacion' => $request->motivo_derivacion,
            'acciones_realizadas' => $request->acciones_realizadas,
            'sugerencias' => $request->sugerencias,
            'estado_id' => 1, // Valor predeterminado para estado_id
            'colaborador' => $colaborador->user_id, // Asignar el usuario autenticado como colaborador
        ]);

        // Registrar el cambio de estado en cambio_estado_derivacion
        CambioEstadoDerivacion::create([
            'derivacion_id' => $derivacion->id,
            'estado_id' => 1, // Estado inicial
            'fecha_actualizacion' => now(),
            'colaborador_acepta' => $colaborador->user_id, // Agregar el ID del usuario logueado


        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('curso.index', [$request->run, $request->digito_ver])
            ->with('success', 'Derivación creada exitosamente.');
    
    } catch (\Exception $e) {
        // Registrar el error para depuración
        logger()->error('Error al crear la derivación: ' . $e->getMessage());

        // Redirigir con un mensaje de error
        return redirect()->route('derivaciones.derivacion-crear', [$request->run, $request->digito_ver])
            ->with('error', 'Hubo un problema al crear la derivación. Por favor, intente nuevamente.');
    }
}


// public function show($id)
// {
//     // Obtener los filtros de fecha y estado desde la solicitud
//     $estadoId = request()->get('estado_id', null);
//     $fechaDesde = request()->get('fecha_desde', null);
//     $fechaHasta = request()->get('fecha_hasta', null);
    
//     // Registro de información en el log
//     Log::info('Función show invocada', [
//         'id' => $id,
//         'estado_id' => $estadoId,
//         'fecha_desde' => $fechaDesde,
//         'fecha_hasta' => $fechaHasta
//     ]);
    
//     $entrevistas = [];
//     $entrevista = null; 

//     // Obtener la derivación por su id
//     $derivacion = Derivacion::findOrFail($id);

//     // Obtener las citaciones relacionadas con la derivación
//     $citacionesConEntrevista = Entrevista::whereNotNull('citacion_id')
//                                      ->pluck('citacion_id')
//                                      ->toArray();

//     $citaciones = Citacion::where('derivacion_id', $id)
//                         ->whereNotIn('id', $citacionesConEntrevista)
//                         ->orderBy('fecha_citacion', 'asc') // Reemplaza 'fecha_citacion' con el nombre real de tu columna de fecha
//                         ->get();


//     Log::info($citacionesConEntrevista);

//     // Obtener las entrevistas relacionadas a cada citación o derivación
//     $entrevistas = Entrevista::where('derivacion_id', $id)
//     ->orderBy('fecha', 'desc') 
//     ->get();

//     // Obtener el nombre completo del colaborador principal de la derivación
//     $colaborador = User::where('user_id', $derivacion->colaborador)->first();
//     $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

//     foreach ($citaciones as $citacion) {
//         // Obtener el colaborador asociado a la citación
//         $colaborador2 = User::where('user_id', $citacion->colaborador)->first();
//         $citacion->colaborador_nombre = $colaborador2 ? $colaborador2->first_name . ' ' . $colaborador2->last_name : 'Desconocido';
//     }

//     if (!empty($entrevistas)) {
//         foreach ($entrevistas as $entrevista) {
//             // Obtener el colaborador asociado a la citación
//             $motivo2 = MotivoEntrevista::where('id', $entrevista->motivo_id)->first();
//             $entrevista->motivos_entrevista = $motivo2 ? $motivo2->motivo : 'Desconocido';

//             // Obtener la citación asociada a la entrevista
//             if ($entrevista->citacion_id) {
//                 $citacion = Citacion::find($entrevista->citacion_id);
//                 if ($citacion) {
//                     $colaboradorCitado = User::where('user_id', $citacion->colaborador)->first();
//                     $entrevista->citado_por = $colaboradorCitado ? $colaboradorCitado->first_name . ' ' . $colaboradorCitado->last_name : 'Desconocido';
//                 } else {
//                     $entrevista->citado_por = 'Desconocido';
//                 }
//             } else {
//                 $entrevista->citado_por = 'No asociado';
//             }
//         }
//     } else {
//         // Manejar el caso en que no haya entrevistas
//         Log::info('No hay entrevistas asociadas a esta derivación.');
//         $entrevistas = [];  // Asignar un arreglo vacío para evitar errores en la vista
//         $entrevista = null; // Si necesitas esta variable en otro lugar, la puedes definir como null
//     }
    
//     // Obtener todas las derivaciones y aplicar los filtros
//     $derivaciones = Derivacion::query();

//     // Aplicar filtro por fecha desde
//     if ($fechaDesde) {
//         $derivaciones->where('fecha_derivacion', '>=', $fechaDesde);
//     }

//     // Aplicar filtro por fecha hasta
//     if ($fechaHasta) {
//         $derivaciones->where('fecha_derivacion', '<=', $fechaHasta);
//     }

//     // Aplicar filtro por estado
//     if ($estadoId) {
//         $derivaciones->where('estado_id', $estadoId);
//     }

//     // Obtener las derivaciones relacionadas, ordenadas por fecha_derivacion (más reciente primero) y luego por estado_id (1, 2, 3)
//     $derivaciones = $derivaciones->orderByDesc('fecha_derivacion')
//         ->orderByRaw('FIELD(estado_id, 1, 2, 3)')
//         ->get();

//     $apoderadoCompromisos = "FOMENTAR UNA CONDUCTA ADECUADA EN SU HIJO/A.\n"
//         . "VELAR POR EL CUMPLIMIENTO DE LAS NORMAS ESCOLARES.\n"
//         . "JUSTIFICAR LAS INASISTENCIAS DE SU HIJO/A OBTENIENDO LA CORRESPONDIENTE DOCUMENTACIÓN.\n"
//         . "PARTICIPAR EN LAS REUNIONES QUE SE LE CONVOQUEN.\n"
//         . "COLABORAR EN EL SEGUIMIENTO DE LAS ACTIVIDADES ESCOLARES DE SU HIJO/A.";

//     $estudianteCompromisos = "RESPETAR A TODOS LOS MIEMBROS DE LA COMUNIDAD ESCOLAR.\n"
//           . "RESOLVER CONFLICTOS DE MANERA PACÍFICA.\n"
//           . "CUMPLIR CON LAS NORMAS Y REGLAMENTOS ESTABLECIDOS EN EL COLEGIO.\n"
//           . "COMPARTIR CON SUS COMPAÑEROS DE MANERA RESPETUOSA.\n"
//           . "PARTICIPAR ACTIVAMENTE EN CLASE Y EN LAS ACTIVIDADES DEL COLEGIO.";

//     // Devolver la vista con la derivación y las citaciones
//     return view('derivaciones.ficha-derivacion', [
//         'derivacion' => $derivacion,
//         'citaciones' => $citaciones,
//         'derivaciones' => $derivaciones,
//         'estadoId' => $estadoId,
//         'fechaDesde' => $fechaDesde,
//         'fechaHasta' => $fechaHasta,
//         'entrevistas' => $entrevistas,
//         'entrevista' => $entrevista,
//         'estudianteCompromisos' => $estudianteCompromisos,
//         'apoderadoCompromisos' => $apoderadoCompromisos
//     ]);
// }

public function show($id)
{
    // Obtener los filtros de fecha y estado desde la solicitud
    $estadoId = request()->get('estado_id', null);
    $fechaDesde = request()->get('fecha_desde', null);
    $fechaHasta = request()->get('fecha_hasta', null);
    
    // Registro de información en el log
    Log::info('Función show invocada', [
        'id' => $id,
        'estado_id' => $estadoId,
        'fecha_desde' => $fechaDesde,
        'fecha_hasta' => $fechaHasta
    ]);
    
    $entrevistas = [];
    $entrevista = null; 

    // Obtener la derivación por su id
    $derivacion = Derivacion::findOrFail($id);

    // Obtener el colaborador que dejó el derivacion_id con estado_id 2
    $cambioEstado = CambioEstadoDerivacion::where('derivacion_id', $derivacion->id)
        ->where('estado_id', 2)
        ->first();

    // Si se encuentra el cambio de estado con estado_id 2, obtener el colaborador
    if ($cambioEstado) {
// Encuentra el colaborador que tiene el estado_id = 2
                $colaborador = User::where('user_id', $cambioEstado->colaborador_acepta)
                   ->whereHas('estado', function ($query) {
                       $query->where('estado_id', 2); // Filtra por el estado_id = 2
                   })
                   ->first();        
                $derivacion->colaborador_acepta_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';
    } else {
        $derivacion->colaborador_acepta_nombre = 'Desconocido';
    }

    // Obtener las citaciones relacionadas con la derivación
    $citacionesConEntrevista = Entrevista::whereNotNull('citacion_id')
                                     ->pluck('citacion_id')
                                     ->toArray();

    $citaciones = Citacion::where('derivacion_id', $id)
                        ->whereNotIn('id', $citacionesConEntrevista)
                        ->orderBy('fecha_citacion', 'asc')
                        ->get();


    Log::info($citacionesConEntrevista);

    // Obtener las entrevistas relacionadas a cada citación o derivación
    $entrevistas = Entrevista::where('derivacion_id', $id)
    ->orderBy('fecha', 'desc') 
    ->get();

    // Obtener el nombre completo del colaborador principal de la derivación
    $colaborador = User::where('user_id', $derivacion->colaborador)->first();
    $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

    foreach ($citaciones as $citacion) {
        // Obtener el colaborador asociado a la citación
        $colaborador2 = User::where('user_id', $citacion->colaborador)->first();
        $citacion->colaborador_nombre = $colaborador2 ? $colaborador2->first_name . ' ' . $colaborador2->last_name : 'Desconocido';
    }

    if (!empty($entrevistas)) {
        foreach ($entrevistas as $entrevista) {
            // Obtener el colaborador asociado a la citación
            $motivo2 = MotivoEntrevista::where('id', $entrevista->motivo_id)->first();
            $entrevista->motivos_entrevista = $motivo2 ? $motivo2->motivo : 'Desconocido';

            // Obtener la citación asociada a la entrevista
            if ($entrevista->citacion_id) {
                $citacion = Citacion::find($entrevista->citacion_id);
                if ($citacion) {
                    $colaboradorCitado = User::where('user_id', $citacion->colaborador)->first();
                    $entrevista->citado_por = $colaboradorCitado ? $colaboradorCitado->first_name . ' ' . $colaboradorCitado->last_name : 'Desconocido';
                } else {
                    $entrevista->citado_por = 'Desconocido';
                }
            } else {
                $entrevista->citado_por = 'No asociado';
            }
        }
    } else {
        // Manejar el caso en que no haya entrevistas
        Log::info('No hay entrevistas asociadas a esta derivación.');
        $entrevistas = [];  // Asignar un arreglo vacío para evitar errores en la vista
        $entrevista = null; // Si necesitas esta variable en otro lugar, la puedes definir como null
    }
    
    // Obtener todas las derivaciones y aplicar los filtros
    $derivaciones = Derivacion::query();

    // Aplicar filtro por fecha desde
    if ($fechaDesde) {
        $derivaciones->where('fecha_derivacion', '>=', $fechaDesde);
    }

    // Aplicar filtro por fecha hasta
    if ($fechaHasta) {
        $derivaciones->where('fecha_derivacion', '<=', $fechaHasta);
    }

    // Aplicar filtro por estado
    if ($estadoId) {
        $derivaciones->where('estado_id', $estadoId);
    }

    // Obtener las derivaciones relacionadas, ordenadas por fecha_derivacion (más reciente primero) y luego por estado_id (1, 2, 3)
    $derivaciones = $derivaciones->orderByDesc('fecha_derivacion')
        ->orderByRaw('FIELD(estado_id, 1, 2, 3)') // Ordenar por estado_id
        ->get();

    $apoderadoCompromisos = "FOMENTAR UNA CONDUCTA ADECUADA EN SU HIJO/A.\n"
        . "VELAR POR EL CUMPLIMIENTO DE LAS NORMAS ESCOLARES.\n"
        . "JUSTIFICAR LAS INASISTENCIAS DE SU HIJO/A OBTENIENDO LA CORRESPONDIENTE DOCUMENTACIÓN.\n"
        . "PARTICIPAR EN LAS REUNIONES QUE SE LE CONVOQUEN.\n"
        . "COLABORAR EN EL SEGUIMIENTO DE LAS ACTIVIDADES ESCOLARES DE SU HIJO/A.";

    $estudianteCompromisos = "RESPETAR A TODOS LOS MIEMBROS DE LA COMUNIDAD ESCOLAR.\n"
          . "RESOLVER CONFLICTOS DE MANERA PACÍFICA.\n"
          . "CUMPLIR CON LAS NORMAS Y REGLAMENTOS ESTABLECIDOS EN EL COLEGIO.\n"
          . "COMPARTIR CON SUS COMPAÑEROS DE MANERA RESPETUOSA.\n"
          . "PARTICIPAR ACTIVAMENTE EN CLASE Y EN LAS ACTIVIDADES DEL COLEGIO.";

    // Devolver la vista con la derivación y las citaciones
    return view('derivaciones.ficha-derivacion', [
        'derivacion' => $derivacion,
        'citaciones' => $citaciones,
        'derivaciones' => $derivaciones,
        'estadoId' => $estadoId,
        'fechaDesde' => $fechaDesde,
        'fechaHasta' => $fechaHasta,
        'entrevistas' => $entrevistas,
        'entrevista' => $entrevista,
        'estudianteCompromisos' => $estudianteCompromisos,
        'apoderadoCompromisos' => $apoderadoCompromisos
    ]);
}


    // Método para listar todas las derivaciones
    public function listarDerivaciones(Request $request)
    {
// Obtener el usuario autenticado
        $usuario = Auth::user();

        // Obtener los filtros de fecha y estado desde la solicitud
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $estadoId = $request->input('estado_id');
    
        // Obtener todas las derivaciones y aplicar los filtros
    // Construir la consulta de derivaciones dependiendo del categoria_id del usuario
    if (in_array($usuario->id_category, [3, 4, 5])) {
        // Si el usuario tiene categoria_id 3, 4, o 5, solo mostrar derivaciones en las que es colaborador
        $derivaciones = Derivacion::where('colaborador', $usuario->user_id);
    } else {
        // Si no, mostrar todas las derivaciones
        $derivaciones = Derivacion::query();
    }

        // Aplicar filtro por fecha desde
        if ($fechaDesde) {
            $derivaciones->where('fecha_derivacion', '>=', $fechaDesde);
        }
    
        // Aplicar filtro por fecha hasta
        if ($fechaHasta) {
            $derivaciones->where('fecha_derivacion', '<=', $fechaHasta);
        }
    
        // Aplicar filtro por estado
        if ($estadoId) {
            $derivaciones->where('estado_id', $estadoId);
        }
    
        $derivaciones = $derivaciones->orderBy('estado_id', 'asc')
        ->orderBy('fecha_derivacion', 'asc')
        ->get();

        // Iterar sobre las derivaciones para obtener el nombre completo del colaborador
        foreach ($derivaciones as $derivacion) {
            $colaborador = User::where('user_id', $derivacion->colaborador)->first();
            $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';
        }
    
        // Pasar los datos a la vista derivaciones-estado
        return view('derivaciones.derivaciones-estado', [
            'derivaciones' => $derivaciones,
            'estadoId' => $estadoId,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
        ]);
    }
    
    

    public function destroy($id)
    {
        // Obtener la derivación por su id
        $derivacion = Derivacion::findOrFail($id);
    
        // Verificar si el estado_id es 1 antes de permitir la eliminación
        if ($derivacion->estado_id != 1) {
            return redirect()->back()->with('error', 'La derivación no puede ser eliminada "Solo se puede eliminar si se encuentra pendiente".');
        }
    
        // Obtener RUN y DV antes de eliminar la derivación
        $run = $derivacion->run; // Asegúrate de que 'run' sea un campo en tu modelo
        $dv = $derivacion->digito_ver; // Asegúrate de que 'digito_ver' sea un campo en tu modelo
    
        // Eliminar la derivación
        $derivacion->delete();
    
        // Redirigir a la ruta del expediente del alumno con RUN y DV
        return redirect()->route('alumno.expediente', ['run' => $run, 'dv' => $dv])
                         ->with('success', 'Derivación eliminada exitosamente.');
    }
    

public function update(Request $request, $id)
{
    $derivacion = Derivacion::findOrFail($id);

    // Solo actualizamos los campos permitidos
    $derivacion->update($request->only([
        'telefono',
        'adulto_responsable',
        'programa_retencion',
        'programa_integracion',
    ]));

    // Redirigir a la vista de la derivación actualizada
    return redirect()->route('derivacion.show', $id)->with('success', 'Datos del estudiante actualizados correctamente.');
}
public function actualizarIndicadores(Request $request, $id)
{
    $derivacion = Derivacion::find($id);

    // Preparar los indicadores personales
    $indicadores_personal = $request->indicador_personal ?? [];
    $diagnostico = $request->filled('diagnostico_previo') ? "Diagnóstico: " . $request->diagnostico_previo : null;
    $tratamiento = $request->filled('tratamiento_farmacologico') ? "Tratamiento farmacológico: " . $request->tratamiento_farmacologico : null;

    if ($diagnostico) {
        $indicadores_personal[] = $diagnostico;
    }

    if ($tratamiento) {
        $indicadores_personal[] = $tratamiento;
    }

    // Preparar los indicadores familiares y socio-comunitarios
    $indicadores_familiar = $request->indicador_familiar ?? [];
    $indicadores_socio_comunitario = $request->indicador_socio_comunitario ?? [];

    // Actualizar los campos en la base de datos
    $derivacion->indicadores_personal = $indicadores_personal;
    $derivacion->indicadores_familiar = $indicadores_familiar;
    $derivacion->indicadores_socio_comunitario = $indicadores_socio_comunitario;

    // Guardar los cambios
    $derivacion->save();

    // Redirigir con un mensaje de éxito
    return redirect()->route('derivacion.show', $id)
        ->with('success', 'Indicadores actualizados correctamente.');
}


public function actualizarMotivoAcciones(Request $request, $id)
{
    // Registrar la llegada a la función
    Log::info('Función actualizarMotivoAcciones invocada', ['id' => $id]);

    // Validar los datos recibidos
    $validatedData = $request->validate([
        'motivo_derivacion' => 'required|string|max:255',
        'acciones_realizadas' => 'nullable|string|max:255',
        'sugerencias' => 'nullable|string|max:255',
    ]);
    
    Log::info('Datos recibidos en el Request:', $request->all());
    Log::info('Datos validados', ['validatedData' => $validatedData]);

    // Buscar la derivación por ID
    $derivacion = Derivacion::findOrFail($id);
    
    // Mostrar el contenido de $derivacion y continuar con el flujo
    Log::info('Derivación encontrada', ['derivacion' => $derivacion->toArray()]);

    // Actualizar los campos
    $derivacion->motivo_derivacion = $request->input('motivo_derivacion');
    $derivacion->acciones_realizadas = $request->input('acciones_realizadas');
    $derivacion->sugerencias = $request->input('sugerencias');

    Log::info('Campos de la derivación actualizados', [
        'motivo_derivacion' => $derivacion->motivo_derivacion,
        'acciones_realizadas' => $derivacion->acciones_realizadas,
        'sugerencias' => $derivacion->sugerencias
    ]);

    // Guardar los cambios en la base de datos
    if ($derivacion->save()) {
        Log::info('Cambios guardados correctamente');
        // Redireccionar a la página anterior con un mensaje de éxito
        return redirect()->back()->with('success', 'Los detalles de la derivación se actualizaron correctamente.');
    } else {
        Log::error('Error al guardar cambios en la derivación', ['derivacion' => $derivacion]);
        return redirect()->back()->withErrors('Error al actualizar la derivación.');
    }
}



public function entrevistaAlumno($id, $tipo_entrevista, $citacion)
{
    Log::info('Función entrevistaAlumno invocada', [
        'id' => $id,
        'tipo_entrevista' => $tipo_entrevista,
        'citacion' => $citacion
    ]);

    // Encontrar la derivación por ID
    $derivacion = Derivacion::findOrFail($id);

    // Buscar el colaborador asociado a la derivación
    $colaborador = User::where('user_id', $derivacion->colaborador)->first();
    $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

    // Obtener los motivos de entrevista
    $motivos = MotivoEntrevista::all();

    // Verificar si ya existe una entrevista para esta derivación y tipo de entrevista
    $entrevistaExistente = Entrevista::where('derivacion_id', $id)
                            ->where('tipo_entrevista', $tipo_entrevista)
                            ->first();

    // Si existe la entrevista, la pasamos a la vista, sino pasamos `null`
    $entrevista = $entrevistaExistente ?: null;

    $citacionId = $citacion;


    // Retornar la vista, pasando la derivación, motivos, tipo de entrevista y la entrevista existente (si la hay)
    return view('entrevistas.entrevista-alumno', compact('derivacion', 'motivos', 'tipo_entrevista', 'entrevista', 'citacionId'));
}

public function entrevistaApoderado($id, $tipo_entrevista, $citacion)
{
    Log::info('Función entrevistaAlumno invocada', [
        'id' => $id,
        'tipo_entrevista' => $tipo_entrevista,
        'citacion' => $citacion
    ]);

    // Encontrar la derivación por ID
    $derivacion = Derivacion::findOrFail($id);

    // Buscar el colaborador asociado a la derivación
    $colaborador = User::where('user_id', $derivacion->colaborador)->first();
    $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

    // Obtener los motivos de entrevista
    $motivos = MotivoEntrevista::all();

    // Verificar si ya existe una entrevista para esta derivación y tipo de entrevista
    $entrevistaExistente = Entrevista::where('derivacion_id', $id)
                            ->where('tipo_entrevista', $tipo_entrevista)
                            ->first();

    // Si existe la entrevista, la pasamos a la vista, sino pasamos `null`
    $entrevista = $entrevistaExistente ?: null;

    $citacionId = $citacion;


    // Retornar la vista, pasando la derivación, motivos, tipo de entrevista y la entrevista existente (si la hay)
    return view('entrevistas.entrevista-apoderado', compact('derivacion', 'motivos', 'tipo_entrevista', 'entrevista', 'citacionId'));
}

public function citacionApoderado($tipo_entrevista, $citacionId)
{
    // Registrar el inicio de la función con los parámetros de entrada
    Log::info('Función citacionApoderado invocada', [
        'tipo_entrevista' => $tipo_entrevista,
        'citacionId' => $citacionId
    ]);

    // Obtener la citación desde la base de datos usando el ID
    $citacion = Citacion::find($citacionId);
    Log::info('Citación obtenida', ['citacion' => $citacion]);

    // Verificar si se encontró la citación
    if (!$citacion) {
        // Si no se encuentra la citación, registrar un error y redirigir
        Log::error('Citación no encontrada', ['citacionId' => $citacionId]);
        return redirect()->route('error')->with('message', 'Citación no encontrada');
    }

    // Continuar con la búsqueda del colaborador
    Log::info('Buscando colaborador...');
    $colaborador = User::where('user_id', $citacion->colaborador)->first();
    Log::info('Colaborador encontrado', ['colaborador' => $colaborador]);

    // Asignar el nombre completo del colaborador a la citación
    if ($colaborador) {
        $citacion->colaborador_nombre = $colaborador->first_name . ' ' . $colaborador->last_name;
    } else {
        $citacion->colaborador_nombre = 'Desconocido';
        Log::warning('No se encontró el colaborador', ['colaborador_id' => $citacion->colaborador]);
    }
    Log::info('Nombre del colaborador asignado', ['colaborador_nombre' => $citacion->colaborador_nombre]);

    // Obtener los motivos de entrevista
    Log::info('Obteniendo los motivos de entrevista...');
    $motivos = MotivoEntrevista::all();
    Log::info('Motivos de entrevista obtenidos', ['motivos' => $motivos]);

    // Obtener el run del estudiante desde la citación
    $runEstudiante = $citacion->run;
    Log::info('Run del estudiante', ['runEstudiante' => $runEstudiante]);
    
    // Buscar la matrícula asociada al run del estudiante
    Log::info('Buscando matrícula para el run del estudiante...');
    $matricula = Matricula::where('run', $runEstudiante)->first();
    Log::info('Matrícula encontrada', ['matricula' => $matricula]);
    
    // Verificar si la matrícula contiene el campo 'desc_grado'
    if ($matricula) {
        Log::info('Verificando campo desc_grado', ['desc_grado' => $matricula->desc_grado]);
    
        // Asignar el curso si 'desc_grado' tiene un valor
        if (!empty($matricula->desc_grado)) {
            $citacion->curso = $matricula->desc_grado;  // Asignamos el curso de la matrícula
            Log::info('Curso asignado', ['curso' => $citacion->curso]);
        } else {
            $citacion->curso = 'Curso no disponible';  // Si 'desc_grado' está vacío o no se encuentra
            Log::warning('Campo desc_grado vacío, asignando valor por defecto', ['curso' => $citacion->curso]);
        }
    } else {
        $citacion->curso = 'Curso no encontrado';  // Si no se encuentra la matrícula
        Log::warning('No se encontró la matrícula, asignando curso por defecto', ['curso' => $citacion->curso]);
    }
    
    // Log final con la información del curso asignado
    Log::info('Información final de la citación', ['citacion' => $citacion]);
    
    // Retornar la vista, pasando la citación, motivos, tipo de entrevista y citacionId
    Log::info('Retornando la vista', [
        'citacion' => $citacion,
        'motivos' => $motivos,
        'tipo_entrevista' => $tipo_entrevista,
        'citacionId' => $citacionId
    ]);

    return view('entrevistas.citacion-apoderado', compact('citacion', 'motivos', 'tipo_entrevista', 'citacionId'));
}




public function entrevistaCompromiso($id, $tipo_entrevista, $citacion)
{
    Log::info('Función entrevistaAlumno invocada', [
        'id' => $id,
        'tipo_entrevista' => $tipo_entrevista,
        'citacion' => $citacion
    ]);

    // Encontrar la derivación por ID
    $derivacion = Derivacion::findOrFail($id);

    // Buscar el colaborador asociado a la derivación
    $colaborador = User::where('user_id', $derivacion->colaborador)->first();
    $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

    // Obtener los motivos de entrevista
    $motivos = MotivoEntrevista::all();

    // Verificar si ya existe una entrevista para esta derivación y tipo de entrevista
    $entrevistaExistente = Entrevista::where('derivacion_id', $id)
                            ->where('tipo_entrevista', $tipo_entrevista)
                            ->first();

    // Si existe la entrevista, la pasamos a la vista, sino pasamos `null`
    $entrevista = $entrevistaExistente ?: null;

    $citacionId = $citacion;


    // Retornar la vista, pasando la derivación, motivos, tipo de entrevista y la entrevista existente (si la hay)
    return view('entrevistas.entrevista-compromiso', compact('derivacion', 'motivos', 'tipo_entrevista', 'entrevista', 'citacionId'));
}


    public function cancelarCitacion($id)
    {
        // Busca la citación por su ID
        $citacion = Citacion::find($id);

        // Verifica si la citación existe
        if (!$citacion) {
            return redirect()->back()->with('error', 'Citación no encontrada.');
        }

        // Elimina la citación
        $citacion->delete();

        // Redirige de nuevo con un mensaje de éxito
        return redirect()->back()->with('success', 'Citación cancelada con éxito.');
    }

    public function cambiarEstado($id, $nuevoEstado)
    {
        try {
            // Buscar la derivación
            $derivacion = Derivacion::findOrFail($id);
    
            // Obtener el nombre del estudiante y el curso
            $nombreEstudiante = $derivacion->nombre_estudiante;
            $cursoEstudiante = $derivacion->curso;
    
            // Obtener el usuario logueado
            $colaborador = Auth::user(); // Obtenemos el usuario autenticado
    
            // Actualizar el estado de la derivación y asignar el colaborador que acepta
            $derivacion->estado_id = $nuevoEstado;
            $derivacion->save();
    
            // Registrar el cambio de estado en cambio_estado_derivacion
            CambioEstadoDerivacion::create([
                'derivacion_id' => $derivacion->id,
                'estado_id' => $nuevoEstado,
                'fecha_actualizacion' => now(),
                'colaborador_acepta' => $colaborador->user_id, // Agregar el ID del usuario logueado
            ]);
    
            $estadoNombre = 'Estado desconocido'; // valor por defecto
            if ($nuevoEstado == 1) {
                $estadoNombre = 'Pendiente';
            } elseif ($nuevoEstado == 2) {
                $estadoNombre = 'Aceptado';
            } elseif ($nuevoEstado == 3) {
                $estadoNombre = 'Finalizado';
            }
                        // Obtener los usuarios con category_id = 1
            $usuarios = User::where('id_category', 1)->get();
    
            // Enviar email a todos los usuarios
            foreach ($usuarios as $user) {
                // Configurar PHPMailer
                $mail = new PHPMailer(true);
    
                try {
                    // Configuración del servidor SMTP
                    $mail->isSMTP();
                    $mail->Host = 'mail.cmvapp.cl';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'soporte@cmvapp.cl';
                    $mail->Password = 'Soportecmv2043'; // La contraseña del correo
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Para SSL
                    $mail->Port = 465;
    
                    // Configurar el conjunto de caracteres a UTF-8
                    $mail->CharSet = 'UTF-8';
    
                    // Destinatarios
                    $mail->setFrom('soporte@marianistasmelipilla.cl', 'Soporte Derivaciones Escolares');
                    $mail->addAddress($user->email); // Agrega un destinatario
    
                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Cambio de Estado de Derivación';
    
                    // Contenido HTML
                    $mail->Body = '
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Cambio de Estado de Derivación</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 20px;
                            }
                            .container {
                                max-width: 600px;
                                margin: auto;
                                background: #fff;
                                padding: 20px;
                                border-radius: 5px;
                                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                            }
                            h1 {
                                color: #333;
                            }
                            p {
                                line-height: 1.5;
                                color: #555;
                            }
                            .footer {
                                margin-top: 20px;
                                font-size: 0.8em;
                                color: #777;
                            }
                            .btn {
                                display: inline-block;
                                padding: 10px 15px;
                                color: #fff !important;
                                background-color: #007bff;
                                text-decoration: none;
                                border-radius: 5px;
                                border: none;
                                cursor: pointer;
                                transition: background-color 0.3s ease;
                            }
                            .btn:hover {
                                background-color: #0056b3;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1>Hola!</h1>
                            <p>Se ha actualizado el estado de la derivación de <strong>' . $nombreEstudiante . '</strong> (Curso: ' . $cursoEstudiante . ').</p>
                            <p>Estado actual: <strong>' . $estadoNombre . '</strong>.</p>
                            <p>Por favor, revisa la derivación para más detalles.</p>
                            <a href="https://cmvapp.cl/proyecto_capston_laravel/public/login" class="btn">Iniciar Sesión</a>
                            <div class="footer">
                                <p>Gracias,<br>El equipo de soporte.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ';
    
                    // Enviar el correo
                    if ($mail->send()) {
                        Log::info("Correo enviado a: " . $user->email); // Log para verificar que el correo fue enviado
                    } else {
                        Log::error("Error al enviar el correo a: " . $user->email); // Log en caso de error
                    }
                } catch (Exception $e) {
                    // Registra el error para depuración
                    Log::error("Error al enviar correo a " . $user->email . ": " . $e->getMessage());
                }
            }
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'El estado de la derivación se actualizó correctamente, y los correos han sido enviados.');
        } catch (\Exception $e) {
            // Registrar el error para depuración
            logger()->error('Error al cambiar el estado de la derivación: ' . $e->getMessage());
    
            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'Hubo un problema al actualizar el estado. Por favor, intente nuevamente.');
        }
    }
    

    public function promedioCambioEstado()
{
    // Variables para acumular diferencias de días entre estados
    $diferencias1A2 = [];
    $diferencias2A3 = [];

    // Obtener todos los cambios de estado agrupados por derivacion_id
    $cambiosEstado = CambioEstadoDerivacion::orderBy('fecha_actualizacion')
        ->get()
        ->groupBy('derivacion_id'); // Agrupar por derivación

    foreach ($cambiosEstado as $cambios) {
        // Ordenar por fecha para garantizar el orden correcto
        $cambios = $cambios->sortBy('fecha_actualizacion')->values();

        for ($i = 0; $i < $cambios->count(); $i++) {
            $cambioActual = $cambios[$i];

            if ($cambioActual->estado_id == 1 && isset($cambios[$i + 1]) && $cambios[$i + 1]->estado_id == 2) {
                $fecha1 = Carbon::parse($cambioActual->fecha_actualizacion);
                $fecha2 = Carbon::parse($cambios[$i + 1]->fecha_actualizacion);
                $diferencias1A2[] = $fecha2->diffInDays($fecha1);
            }

            if ($cambioActual->estado_id == 2 && isset($cambios[$i + 1]) && $cambios[$i + 1]->estado_id == 3) {
                $fecha1 = Carbon::parse($cambioActual->fecha_actualizacion);
                $fecha2 = Carbon::parse($cambios[$i + 1]->fecha_actualizacion);
                $diferencias2A3[] = $fecha2->diffInDays($fecha1);
            }
        }
    }

    // Calcular los promedios
    $promedio1A2 = count($diferencias1A2) > 0 ? array_sum($diferencias1A2) / count($diferencias1A2) : 0;
    $promedio2A3 = count($diferencias2A3) > 0 ? array_sum($diferencias2A3) / count($diferencias2A3) : 0;

    $promedio1A2 = $promedio1A2*-1;
    $promedio2A3 = $promedio2A3*-1;

    // Redondear los promedios a enteros (sin decimales)
$promedio1A2 = round($promedio1A2);
$promedio2A3 = round($promedio2A3);
    // Retornar los resultados como JSON
    return response()->json([
        'Aceptada' => $promedio1A2,
        'Finalizada' => $promedio2A3,
    ]);
}


}