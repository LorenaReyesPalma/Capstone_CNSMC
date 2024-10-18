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
        // Obtener el alumno de la base de datos
        $alumno = Matricula::where('run', $run)->where('digito_ver', $dv)->first();
        
        // Verificar que el alumno existe
        if (!$alumno) {
            abort(404, 'Alumno no encontrado');
        }

        // Obtener el expediente relacionado con el alumno
        $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();

        // Verificar que el expediente existe
        if (!$expediente) {
            // Llamar a la función para crear el expediente usando el AlumnoController
            $this->alumnoController->crearExpediente($run, $dv);
            // Después de crear el expediente, puedes volver a buscarlo
            $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();

            // Verificar nuevamente que el expediente fue creado
            if (!$expediente) {
                abort(404, 'No se pudo crear el expediente');
            }
        }

        // Obtener el teléfono del expediente
        $telefono = $expediente->telefono; // Suponiendo que la columna se llama 'telefono'

        // Obtener la fecha actual
        $fechaActual2 = Carbon::now(); // Fecha actual
        $fechaActual = now()->format('Y-m-d');

        // Asegúrate de que la fecha de nacimiento esté en el formato correcto
        $fechaNacimiento = Carbon::parse($alumno->fecha_nacimiento); // Conversión a Carbon
        $nombre_completo = $alumno->nombres . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno;
        
        // Calcular la edad solo si la fecha de nacimiento es válida
        if ($fechaNacimiento > $fechaActual2) {
            $edad = 0; // O puedes asignar un valor por defecto
        } else {
            $edad = (int) $fechaNacimiento->diffInYears($fechaActual2);
        }

        // Retornar la vista y pasar los datos del alumno
        return view('derivacion', compact('run', 'dv', 'alumno', 'edad', 'nombre_completo', 'fechaActual', 'telefono'));
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
        Derivacion::create([
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

        // Redirigir con un mensaje de éxito
        return redirect()->route('curso.index', [$request->run, $request->digito_ver])
            ->with('success', 'Derivación creada exitosamente.');
    
    } catch (\Exception $e) {
        // Registrar el error para depuración
        logger()->error('Error al crear la derivación: ' . $e->getMessage());

        // Redirigir con un mensaje de error
        return redirect()->route('derivacion.create', [$request->run, $request->digito_ver])
            ->with('error', 'Hubo un problema al crear la derivación. Por favor, intente nuevamente.');
    }
}


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

    // Obtener las citaciones relacionadas con la derivación
    $citacionesConEntrevista = Entrevista::whereNotNull('citacion_id')
                                     ->pluck('citacion_id')
                                     ->toArray();

    $citaciones = Citacion::where('derivacion_id', $id)
                        ->whereNotIn('id', $citacionesConEntrevista)
                        ->orderBy('fecha_citacion', 'asc') // Reemplaza 'fecha_citacion' con el nombre real de tu columna de fecha
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
        ->orderByRaw('FIELD(estado_id, 1, 2, 3)')
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
        // Obtener los filtros de fecha y estado desde la solicitud
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $estadoId = $request->input('estado_id');
    
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
    
        $derivaciones = $derivaciones->get();
    
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
    $indicadores_personal = $request->indicador_personal;

    // Concatenar diagnóstico previo y tratamiento farmacológico
    $diagnostico = $request->diagnostico_previo ? "Diagnóstico: " . $request->diagnostico_previo : null;
    $tratamiento = $request->tratamiento_farmacologico ? "Tratamiento farmacológico: " . $request->tratamiento_farmacologico : null;

    // Añadir las concatenaciones a los indicadores personales
    if ($diagnostico) {
        $indicadores_personal[] = $diagnostico;
    }

    if ($tratamiento) {
        $indicadores_personal[] = $tratamiento;
    }

    // Actualizar los indicadores personales
    $derivacion->indicadores_personal = $indicadores_personal;

    // Guardar los cambios en la base de datos
    $derivacion->save();

    // Redirigir o retornar respuesta
    return redirect()->route('derivacion.show', $id)->with('success', 'Datos del estudiante actualizados correctamente.');
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
    
}