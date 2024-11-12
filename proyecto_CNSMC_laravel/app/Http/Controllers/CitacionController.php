<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Citacion;
use App\Models\Matricula;
use App\Models\Derivacion;
use Illuminate\Http\Request;
use App\Models\User;

class CitacionController extends Controller
{
    public function store(Request $request, $derivacion_id)
    {
        $colaborador = Auth::user(); // Obtenemos el usuario autenticado

        // Validación de los datos
        $request->validate([
            'tipo_accion' => 'required|string',
            'fecha_citacion' => 'required|date',
            'hora_citacion' => ['required', 'regex:/^([01]\d|2[0-3]):([0-5]\d)$/'], // Hora en formato 24 horas
            'observaciones' => 'nullable|string',
        ]);

        // Crear la citación
        Citacion::create([
            'derivacion_id' => $derivacion_id,
            'tipo_accion' => $request->tipo_accion,
            'fecha_citacion' => $request->fecha_citacion,
            'hora_citacion' => $request->hora_citacion,
            'observaciones' => $request->observaciones,
            'estado' => 1, // Valor predeterminado para estado
            'colaborador' => $colaborador->user_id,
        ]);

        // Redirigir a la vista de la derivación usando su id
        return redirect()->route('derivacion.show', $derivacion_id)->with('success', 'Citación creada correctamente');
    }


public function obtenerCitaciones()
{
    // Obtener el usuario autenticado
    $usuario = auth()->user();

    // Verificar la categoría del usuario y aplicar el filtro de citaciones en consecuencia
    if (in_array($usuario->id_category, [3, 4, 5])) {
        // Si el usuario tiene id_category 3, 4, o 5, solo mostrar citaciones en las que es colaborador
        $citaciones = Citacion::where('estado', 1)
            ->where('colaborador', $usuario->user_id)
            ->get();
    } else {
        // Si el usuario no tiene id_category 3, 4 o 5, mostrar todas las citaciones con estado 1
        $citaciones = Citacion::where('estado', 1)->get();
    }
    // Array para almacenar los eventos
    $eventos = [];

    // Iterar sobre las citaciones para obtener el nombre completo del colaborador y el nombre del alumno
    foreach ($citaciones as $citacion) {
        // Obtener nombre del colaborador
        $colaborador = User::where('user_id', $citacion->colaborador)->first();
        $colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';

        // Obtener nombre del alumno desde la tabla Matriculas, usando el run
        $alumno = Matricula::where('run', $citacion->run)->first(); // Cambiar 'expedientes' por 'matriculas'
        $alumno_nombre = $alumno ? $alumno->nombres .' '. $alumno->apellido_paterno : 'Desconocido';

        // Si no se encuentra el alumno, intentar obtenerlo desde la derivación
        if (!$alumno) {
            $derivacion = Derivacion::where('id', $citacion->derivacion_id)->first();
            if ($derivacion) {
                $alumno = Matricula::where('run', $derivacion->run)->first(); // Cambiar 'expedientes' por 'matriculas'
                $alumno_nombre =  $alumno ?  $alumno->nombres .' '. $alumno->apellido_paterno : 'Desconocido';
            }
        }
        

        // Agregar citación al array de eventos
        $eventos[] = [
            'citacion_id' => $citacion->id,
            'title' => $citacion->tipo_accion . ' - ' . $alumno_nombre, // Incluir el nombre del alumno en el título
            'start' => "{$citacion->fecha_citacion}T{$citacion->hora_citacion}",
            'end' => "{$citacion->fecha_citacion}T" . date('H:i', strtotime($citacion->hora_citacion) + 3600), // Asumiendo que cada evento dura una hora
            'extendedProps' => [
                'observaciones' => $citacion->observaciones,
                'colaborador_nombre' => $colaborador_nombre,
                'derivacion_id' => $citacion->derivacion_id,
                'run' => $citacion->run,
                'digito_ver' => $citacion->digito_ver,
                'tipo_accion' => $citacion->tipo_accion, // Añadir tipo_accion aquí
                'alumno_nombre' => $alumno_nombre, // Añadir nombre del alumno
            ],
        ];
    }

    // Retornar el array de eventos
    return $eventos;
}


// Método que carga la vista con los eventos de las citaciones
public function citacionShow(Request $request)
{
      // Obtener todos los cursos para mostrar en el formulario
      $cursos = Matricula::select('cod_tipo_ensenanza', 'cod_grado', 'desc_grado', 'letra_curso')
      ->distinct()
      ->orderBy('cod_tipo_ensenanza')
      ->orderBy('cod_grado')
      ->orderBy('desc_grado')
      ->orderBy('letra_curso')
      ->get();

    // Obtener los eventos llamando a obtenerCitaciones
    $eventos = $this->obtenerCitaciones();

    // Obtener el valor de búsqueda (nombre, RUN o curso)
    $searchTerm = $request->input('search_term', ''); // Buscar por nombre, RUN o curso

    // Obtener los alumnos que coincidan con el término de búsqueda
    $alumnos = Matricula::leftJoin('expedientes', function($join) {
        $join->on('matricula.run', '=', 'expedientes.run')
             ->on('matricula.digito_ver', '=', 'expedientes.digito_ver');
    })
    ->select('matricula.run', 'matricula.nombres', 'matricula.cod_tipo_ensenanza', 'matricula.cod_grado', 'matricula.desc_grado', 'matricula.letra_curso')
    ->where(function ($query) use ($searchTerm) {
        $query->where('matricula.nombres', 'LIKE', "%$searchTerm%")
              ->orWhere('matricula.run', 'LIKE', "%$searchTerm%")
              ->orWhere('matricula.cod_tipo_ensenanza', 'LIKE', "%$searchTerm%")
              ->orWhere('matricula.cod_grado', 'LIKE', "%$searchTerm%")
              ->orWhere('matricula.desc_grado', 'LIKE', "%$searchTerm%");
    })
    ->get();

    // Retornar la vista y pasar los eventos y los alumnos como parámetros
    return view('citaciones.citacion', [
        'eventos' => $eventos,
        'cursos' => $cursos,

        'alumnos' => $alumnos
    ]);
}


    public function guardarCitacion(Request $request)
{
    $colaborador = Auth::user(); // Obtenemos el usuario autenticado

    // Mostrar los datos del formulario

    // Validación de la solicitud
    $request->validate([
        'run' => 'required',
        'digito_ver' => 'required',
        'tipo_accion' => 'required',
        'fecha_citacion' => 'required|date',
        'hora_citacion' => 'required|date_format:H:i',
        'observaciones' => 'nullable',
    ]);

    // Crea la citación y agrega la hora a la fecha
    $fechaHora = $request->fecha_citacion . ' ' . $request->hora_citacion;

    // Prepara los datos para la creación
    $data = [
        'fecha_citacion' => $fechaHora,
        'tipo_accion' => $request->tipo_accion,
        'run' => $request->run,
        'digito_ver' => $request->digito_ver,
        'observaciones' => $request->observaciones,
        'estado' => 1,
        'colaborador' => $colaborador->user_id,
        'hora_citacion' => date('H:i:s', strtotime($request->hora_citacion)),
    ];

    // Crea la citación
    Citacion::create($data);

    return redirect()->route('citacionShow')->with('success', 'Citación guardada exitosamente.');
}


    public function buscarAlumnos2(Request $request)
{
    // Lógica de búsqueda existente
    $cursoSeleccionado = $request->input('cod_tipo_ensenanza');
    $search = $request->input('search');
    $cleanedSearch = preg_replace('/\s+/', '', $search);
    $query = Matricula::query();

    // Filtrado por curso y por término de búsqueda
    if ($cursoSeleccionado) {
        $curso = explode('_', $cursoSeleccionado);
        $cod_tipo_ensenanza = $curso[0];
        $cod_grado = $curso[1];
        $letra_curso = $curso[2];
        $query->where('cod_tipo_ensenanza', $cod_tipo_ensenanza)
              ->where('cod_grado', $cod_grado)
              ->where('letra_curso', $letra_curso);
    }

    if ($search) {
        $query->where(function($q) use ($cleanedSearch) {
            $q->where('run', 'like', '%' . $cleanedSearch . '%')
              ->orWhere('nombres', 'like', '%' . $cleanedSearch . '%')
              ->orWhere('apellido_paterno', 'like', '%' . $cleanedSearch . '%')
              ->orWhere('apellido_materno', 'like', '%' . $cleanedSearch . '%');
        });
    }

    // Obtener los resultados con RUT y dígito verificador
    $alumnos = $query->get(['run', 'digito_ver', 'nombres', 'apellido_paterno', 'apellido_materno']);

    // Retornar la respuesta en formato JSON con los resultados
    return response()->json(['alumnos' => $alumnos]);
}

    
}