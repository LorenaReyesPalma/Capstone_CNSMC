<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Derivacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;


class ProfesoresJefesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if (Auth::check()) {
            \Log::info('Usuario autenticado: ' . Auth::user()->user_id);
        } else {
            \Log::info('Usuario no autenticado');
        }

        return view('profesores-jefes.profe-jefeindex');
    }

    public function store(Request $request)
    {
        // Validar el formulario
        $request->validate([
            'motivo_derivacion' => 'required|string',
        ]);

        // Crear una nueva derivación asociada al usuario autenticado
        Derivacion::create([
            'motivo_derivacion' => $request->motivo_derivacion,
            'user_id' => Auth::id(),  // Asociar la derivación al usuario
            'fecha_derivacion' => now(),
        ]);

        return redirect()->route('profejefe.profile')->with('success', 'Derivación creada exitosamente');
    }

    public function show($id)
    {
        // Obtener la derivación específica creada por el usuario
        $derivacion = Derivacion::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return view('derivacion.show', compact('derivacion'));  // Asegúrate de tener esta vista creada
    }
}
