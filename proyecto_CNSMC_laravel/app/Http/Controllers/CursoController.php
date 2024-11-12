<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use App\Models\Expediente;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Support\Facades\Log;


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
    
        // Obtener los alumnos con expediente y aplicar paginación
        $alumnosConExpediente = Matricula::join('expedientes', function($join) {
                $join->on('matricula.run', '=', 'expedientes.run')
                     ->on('matricula.digito_ver', '=', 'expedientes.digito_ver');
            })
            ->orderBy('expedientes.fecha_creacion', 'desc') // Ordenar por fecha_creacion de expedientes
            ->paginate(10, ['matricula.*']); // Especifica 10 resultados por página (ajusta el número si es necesario)
        
        // Marcar que estos alumnos tienen un expediente
        foreach ($alumnosConExpediente as $alumno) {
            $alumno->expediente_existe = true;
        }
    
        // Pasar los cursos y alumnos a la vista
        return view('buscar-alumnos', [
            'cursos' => $cursos,
            'alumnos' => $alumnosConExpediente // Mostrar alumnos con expediente inicialmente con paginación
        ]);
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


public function asignarProfesorCurso(Request $request)
{
    // Verificar los valores recibidos
    Log::info('Datos recibidos en asignarProfesorCurso:', $request->all());  // Esto escribirá todos los datos recibidos en el log

    // Obtener los IDs del curso y el profesor del formulario
    $cursoId = $request->input('curso_id');
    $userId = $request->input('user_id');

    Log::info('Curso ID recibido:', ['curso_id' => $cursoId]);  // Escribir solo el curso_id en el log
    Log::info('User ID recibido:', ['user_id' => $userId]);  // Escribir solo el user_id en el log

    // Obtener el curso y verificar si ya tiene un profesor asignado
    $curso = Curso::findOrFail($cursoId);

    // Verificar si el profesor está asignado a otro curso
    $profesorAsignado = User::where('user_id', $userId)->first();

    if ($profesorAsignado) {
        // Si el profesor ya está asignado a otro curso, lo eliminamos de su asignación anterior
        Log::info('Profesor ya asignado a otro curso. Desvinculando...');
        $profesorAsignado->cursos()->detach();  // Desvincula al profesor de todos los cursos a los que esté asignado
    }

    // Asignar el nuevo profesor al curso
    Log::info('Asignando nuevo profesor al curso...');
    $curso->profesores()->sync([$userId]);  // Esto asigna el profesor al curso y asegura que no haya asignaciones duplicadas

    Log::info('Nuevo profesor asignado al curso:', ['user_id' => $userId]);  // Confirmar que el profesor fue asignado

    return redirect()->back()->with('success', 'Profesor asignado o cambiado exitosamente.');
}



public function quitarProfesorCurso(Request $request)
{
    // Verificar los valores recibidos
    Log::info('Datos recibidos en quitarProfesorCurso:', $request->all());  // Esto escribirá todos los datos recibidos en el log

    // Obtener los IDs del curso y el profesor del formulario
    $cursoId = $request->input('curso_id');
    $userId = $request->input('user_id');

    Log::info('Curso ID recibido para quitar profesor:', ['curso_id' => $cursoId]);
    Log::info('User ID recibido para quitar profesor:', ['user_id' => $userId]);

    // Buscar el curso y el profesor, y eliminar la relación
    $curso = Curso::findOrFail($cursoId);

    // Verificar si el profesor está asignado
    Log::info('Curso encontrado para quitar profesor:', ['curso' => $curso]);

    // Eliminar la relación con el profesor actual
    $curso->profesores()->detach($userId);
    Log::info('Profesor quitado del curso:', ['user_id' => $userId]);

    return redirect()->back()->with('success', 'Profesor quitado del curso exitosamente.');
}


}
