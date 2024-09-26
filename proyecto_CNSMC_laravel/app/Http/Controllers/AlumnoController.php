<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Expediente;

class AlumnoController extends Controller
{
    public function verExpediente($run, $dv)
    {
        // Obtener el alumno usando RUN y dígito verificador
        $alumno = Matricula::where('run', $run)
                           ->where('digito_ver', $dv)
                           ->firstOrFail();

        return view('alumno.expediente', compact('alumno'));
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

}
