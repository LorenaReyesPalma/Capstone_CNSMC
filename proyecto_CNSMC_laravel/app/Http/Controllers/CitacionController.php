<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Citacion;
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
}
