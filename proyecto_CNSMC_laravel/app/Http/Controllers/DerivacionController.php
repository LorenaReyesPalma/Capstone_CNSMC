<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Asegúrate de importar Carbon para manejar fechas
use App\Models\Derivacion;
use App\Models\User;
use App\Models\Matricula;


class DerivacionController extends Controller
{

    public function create($run, $dv)
    {
        // Obtener el alumno de la base de datos
        $alumno = Matricula::where('run', $run)->where('digito_ver', $dv)->first();
    
        // Verificar que el alumno existe
        if (!$alumno) {
            abort(404, 'Alumno no encontrado');
        }
    
        // Obtener la fecha actual
        $fechaActual2 = Carbon::now(); // Fecha actual
        $fechaActual = now()->format('Y-m-d');

        // Asegúrate de que la fecha de nacimiento esté en el formato correcto
        $fechaNacimiento = Carbon::parse($alumno->fecha_nacimiento); // Conversión a Carbon
        $nombre_completo = $alumno->nombres . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno;

        // Mostrar las fechas en consola para depuración
        logger()->info('Fecha actual: ' . $fechaActual2);
        logger()->info('Fecha de nacimiento: ' . $fechaNacimiento);
    
        // Calcular la edad solo si la fecha de nacimiento es válida
        if ($fechaNacimiento > $fechaActual2) {
            $edad = 0; // O puedes asignar un valor por defecto
        } else {
            // Calcular la diferencia en años truncando el valor
            $edad = (int) $fechaNacimiento->diffInYears($fechaActual2);
        }
    
        // Mostrar en consola para depuración
    
        // Retornar la vista y pasar los datos del alumno
        return view('derivacion', compact('run', 'dv', 'alumno', 'edad','nombre_completo','fechaActual'));
    }
    

    public function store(Request $request)
    {
    // Validar los datos
    $request->validate([
        'nombre_estudiante' => 'required|string|max:255',
        'edad' => 'required|integer',
        'curso' => 'required|string|max:255',
        'adulto_responsable' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        // Añade más validaciones según tus necesidades
    ]);

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
    return redirect()->route('derivacion.create', [$request->run, $request->digito_ver])->with('success', 'Derivación creada exitosamente.');
}
}