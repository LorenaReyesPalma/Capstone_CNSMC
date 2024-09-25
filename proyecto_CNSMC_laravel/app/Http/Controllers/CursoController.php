<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use App\Models\Expediente;


class CursoController extends Controller
{
    public function index()
    {
        // Obtener todos los cursos para mostrar en el formulario
        $cursos = Matricula::select('cod_tipo_ensenanza', 'cod_grado', 'desc_grado', 'letra_curso')
            ->distinct()
            ->orderBy('cod_tipo_ensenanza')
            ->orderBy('cod_grado')
            ->orderBy('desc_grado')
            ->orderBy('letra_curso')
            ->get();

        return view('buscar-alumnos', ['cursos' => $cursos]);
    }

    public function buscarAlumnos(Request $request)
    {
        // Obtener los valores de búsqueda del curso y del input de búsqueda (RUT o nombre)
        $cursoSeleccionado = $request->input('cod_tipo_ensenanza');
        $search = $request->input('search');
    
        // Limpiar el término de búsqueda
        $cleanedSearch = preg_replace('/\s+/', '', $search); // Eliminar espacios
    
        // Crear la consulta base
        $query = Matricula::query();
    
        // Si se seleccionó un curso, filtrar por curso
        if ($cursoSeleccionado) {
            $curso = explode('_', $cursoSeleccionado);
            $cod_tipo_ensenanza = $curso[0];
            $cod_grado = $curso[1];
            $letra_curso = $curso[2];
    
            $query->where('cod_tipo_ensenanza', $cod_tipo_ensenanza)
                  ->where('cod_grado', $cod_grado)
                  ->where('letra_curso', $letra_curso);
        }
    
        // Si se ingresó un término de búsqueda (RUT o nombre), agregar el filtro
        if ($search) {
            $query->where(function($q) use ($cleanedSearch) {
                $q->where('run', 'like', '%' . $cleanedSearch . '%')
                  ->orWhere('nombres', 'like', '%' . $cleanedSearch . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $cleanedSearch . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $cleanedSearch . '%')
                  ->orWhere(DB::raw("CONCAT(run, '-', digito_ver)"), 'like', '%' . $cleanedSearch . '%')
                  ->orWhere(DB::raw("CONCAT(run, digito_ver)"), 'like', '%' . str_replace('-', '', $cleanedSearch) . '%');
            });
        }
    
        // Obtener los resultados filtrados
        $alumnos = $query->get();
    
        // Verificar si hay un expediente para cada alumno
        foreach ($alumnos as $alumno) {
            $alumno->expediente_existe = Expediente::where('run', $alumno->run)
                ->where('digito_ver', $alumno->digito_ver)
                ->exists();
        }
    
        // Obtener todos los cursos disponibles para el select
        $cursos = Matricula::select('cod_tipo_ensenanza', 'cod_grado', 'desc_grado', 'letra_curso')
            ->distinct()
            ->orderBy('cod_tipo_ensenanza')
            ->orderBy('cod_grado')
            ->orderBy('desc_grado')
            ->orderBy('letra_curso')
            ->get();
    
        // Retornar la vista con los resultados
        return view('buscar-alumnos', compact('alumnos', 'cursos'));
    }
    
    
    public function seleccionarAlumnos(Request $request)
{
    // Obtener los valores de búsqueda del curso y del input de búsqueda (RUT o nombre)
    $cursoSeleccionado = $request->input('cod_tipo_ensenanza');
    $search = $request->input('search');

    // Crear la consulta base
    $query = Matricula::query();

    // Filtrar por curso si se ha seleccionado uno
    if ($cursoSeleccionado) {
        $curso = explode('_', $cursoSeleccionado);
        $cod_tipo_ensenanza = $curso[0];
        $cod_grado = $curso[1];
        $letra_curso = $curso[2];

        $query->where('cod_tipo_ensenanza', $cod_tipo_ensenanza)
              ->where('cod_grado', $cod_grado)
              ->where('letra_curso', $letra_curso);
    }

    // Filtrar por RUT o nombre
    if ($search) {
        $query->where(function($q) use ($search) {
            // Lógica de búsqueda como antes
        });
    }

    // Obtener los resultados filtrados
    $alumnos = $query->get();

    // Obtener todos los cursos disponibles para el select
    $cursos = Matricula::select('cod_tipo_ensenanza', 'cod_grado', 'desc_grado', 'letra_curso')
        ->distinct()
        ->orderBy('cod_tipo_ensenanza')
        ->orderBy('cod_grado')
        ->orderBy('desc_grado')
        ->orderBy('letra_curso')
        ->get();

    return view('buscar-alumnos', compact('alumnos', 'cursos'));
}

}
