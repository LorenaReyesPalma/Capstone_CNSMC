<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Expediente;
use Illuminate\Support\Facades\Http; // Asegúrate de que esta línea esté aquí

class AlumnoController extends Controller
{
    public function verExpediente($run, $dv)
    {
        // Lógica para obtener el alumno y el expediente
        $alumno = Matricula::where('run', $run)->where('digito_ver', $dv)->first();
        $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();
        
        // Obtener las regiones (suponiendo que ya lo tienes definido)
        $regiones = $this->obtenerRegiones(); // Asegúrate de que esto devuelve las regiones
    
        // Obtener comunas de la región 11 (esto podría ser dinámico basado en la región seleccionada)
        $comunas = $this->obtenerComunasPorRegion(11); // Cambia 11 según la región seleccionada
    
        return view('alumno.expediente', compact('alumno', 'expediente', 'regiones', 'comunas'));
    }
    
    private function obtenerComunasPorRegion($regionId)
    {
        $response = file_get_contents("https://apis.digital.gob.cl/dpa/regiones/$regionId/comunas");
        // $response = file_get_contents("https://cmvapp.cl/proyecto_capston_laravel/public/comunas/$regionId");
        return json_decode($response, true); // Asegúrate de que el contenido es JSON
    }

    private function obtenerRegiones()
    {
        $response = file_get_contents("https://apis.digital.gob.cl/dpa/regiones");
        // $response = file_get_contents("https://cmvapp.cl/proyecto_capston_laravel/public/comunas/$regionId");
        return json_decode($response, true); // Asegúrate de que el contenido es JSON
    }
    

    public function crearExpediente($run, $dv)
    {
        // Obtener los datos de matrícula del alumno
        $matricula = Matricula::where('run', $run)
                              ->where('digito_ver', $dv)
                              ->first();

        if (!$matricula) {
            return redirect()->back()->withErrors('No se encontró la matrícula del alumno.');
        }

        // Crear un nuevo expediente con los datos de matrícula
        $expediente = new Expediente();
        $expediente->run = $matricula->run;
        $expediente->digito_ver = $matricula->digito_ver;
        $expediente->curso = "{$matricula->desc_grado} {$matricula->letra_curso}"; // Ejemplo de cómo combinar curso
        $expediente->genero = $matricula->genero;
        $expediente->fecha_nacimiento = $matricula->fecha_nacimiento;
        $expediente->direccion = $matricula->direccion;
        $expediente->comuna_residencia = $matricula->comuna_residencia;
        $expediente->email = $matricula->email;
        $expediente->telefono = $matricula->telefono;

        // Guardar el expediente en la base de datos
        $expediente->save();

        return redirect()->route('alumno.expediente', ['run' => $run, 'dv' => $dv])
                         ->with('success', 'Expediente creado exitosamente.');
    }

    // // Actualizar expediente
    // public function updateExpediente(Request $request, $run, $dv)
    // {
    //     // Validar los datos
    //     $validatedData = $request->validate([
    //         'curso' => 'required|string|max:255',
    //         'genero' => 'required|string|in:M,F',
    //         'fecha_nacimiento' => 'required|date',
    //         'direccion' => 'required|string|max:255',
    //         'comuna_residencia' => 'required|string|max:255',
    //         'email' => 'nullable|email|max:255',
    //         'telefono' => 'nullable|string|max:15',
    //     ]);
    
    //     // Encontrar el expediente
    //     $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();
    
    //     if (!$expediente) {
    //         return redirect()->route('curso.index')->with('error', 'Expediente no encontrado.');
    //     }
    
    //     // Actualizar el expediente
    //     $expediente->update($validatedData);
    
    //     return redirect()->route('curso.index')->with('success', 'Expediente actualizado correctamente.');
    // }

    // Actualizar expediente
public function updateExpediente(Request $request, $run, $dv)
{
    // Validar los datos
    $validatedData = $request->validate([
        'curso' => 'required|string|max:255',
        'genero' => 'required|string|in:M,F',
        'fecha_nacimiento' => 'required|date',
        'direccion' => 'required|string|max:255',
        'comuna_residencia' => 'required|string|max:255', // Asegúrate de validar el nombre de la comuna
        'email' => 'nullable|email|max:255',
        'telefono' => 'nullable|string|max:15',
    ]);

    // Encontrar el expediente
    $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();

    if (!$expediente) {
        return redirect()->route('curso.index')->with('error', 'Expediente no encontrado.');
    }

    // Actualizar el expediente
    $expediente->curso = $validatedData['curso'];
    $expediente->genero = $validatedData['genero'];
    $expediente->fecha_nacimiento = $validatedData['fecha_nacimiento'];
    $expediente->direccion = $validatedData['direccion'];
    $expediente->comuna_residencia = $validatedData['comuna_residencia']; // Guardar el nombre de la comuna
    $expediente->email = $validatedData['email'];
    $expediente->telefono = $validatedData['telefono'];

    // Guardar cambios
    $expediente->save();

    return redirect()->route('curso.index')->with('success', 'Expediente actualizado correctamente.');
}

    
    public function showExpediente($run, $dv)
    {
        // Recuperar el expediente más reciente
        $expediente = Expediente::where('run', $run)
                                ->where('digito_ver', $dv)
                                ->firstOrFail();

        return view('expediente.show', compact('expediente'));
    }
}
