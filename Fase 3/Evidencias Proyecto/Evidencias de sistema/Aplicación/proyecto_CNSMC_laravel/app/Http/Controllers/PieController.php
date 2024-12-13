<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Derivacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class PieController extends Controller
{
    //
    public function index(Request $request)
    {
         // Obtener el ID del usuario autenticado
         $usuarioId = Auth::id();
         // Obtener los filtros de fecha y estado desde la solicitud
         $fechaDesde = $request->input('fecha_desde');
         $fechaHasta = $request->input('fecha_hasta');
         $estadoId = $request->input('estado_id');
     
    
         // Obtener solo las derivaciones del usuario autenticado y aplicar los filtros
         $derivaciones = Derivacion::where('colaborador', $usuarioId);
 
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
     
         // Verificar si el usuario está autenticado
         if (Auth::check()) {
             \Log::info('Usuario autenticado: ' . Auth::user()->user_id);
         } else {
             \Log::info('Usuario no autenticado');
         }
     
         // Filtrar derivaciones pendientes
         $derivaciones2 = Derivacion::where('estado_id', 1)
         ->where('colaborador', $usuarioId)
         ->orderBy('fecha_derivacion', 'asc')
         ->get();
             
         // Agregar alerta si las derivaciones pendientes tienen más de 3 días sin aceptar
         foreach ($derivaciones2 as $derivacion) {
             $colaborador = User::where('user_id', $derivacion->colaborador)->first();
             $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';
     
             // Calcular la diferencia en días entre la fecha de derivación y la fecha actual
             $fechaDerivacion = \Carbon\Carbon::parse($derivacion->fecha_derivacion);
             $diasDiferencia = \Carbon\Carbon::now()->diffInDays($fechaDerivacion);
     
             // Marcar la derivación con alerta si ha pasado más de 3 días sin ser aceptada
             if ($diasDiferencia > 3 && $derivacion->estado_id == 1) { // estado_id 1 significa pendiente
                 $derivacion->alerta = true;
             } else {
                 $derivacion->alerta = false;
             }
         }
         // consultas para totales
 
          // Obtener el mes y año actual
     $mesActual = \Carbon\Carbon::now()->month;
     $anioActual = \Carbon\Carbon::now()->year;
 
         // Contar cuántas derivaciones están en estado 1
 
         $usuarioId = Auth::id();
         $derivacionesEstado1 = Derivacion::where('estado_id', 1)
         ->where('colaborador', $usuarioId)
         ->whereMonth('fecha_derivacion', $mesActual)
         ->whereYear('fecha_derivacion', $anioActual)
         ->count();
 
 // Contar cuántas derivaciones están en estado 2 en el mes actual del usuario autenticado
 $derivacionesEstado2MesActual = Derivacion::where('estado_id', 2)
     ->where('colaborador', $usuarioId)
     ->whereMonth('fecha_derivacion', $mesActual)
     ->whereYear('fecha_derivacion', $anioActual)
     ->count();
 
 // Contar cuántas derivaciones están en estado 3 en el mes actual del usuario autenticado
 $derivacionesEstado3MesActual = Derivacion::where('estado_id', 3)
     ->where('colaborador', $usuarioId)
     ->whereMonth('fecha_derivacion', $mesActual)
     ->whereYear('fecha_derivacion', $anioActual)
     ->count();
 
 // Contar cuántas derivaciones totales hay en el mes actual del usuario autenticado
 $derivacionesMesActual = Derivacion::where('colaborador', $usuarioId)
     ->whereMonth('fecha_derivacion', $mesActual)
     ->whereYear('fecha_derivacion', $anioActual)
     ->count();
 
             $estadisticas = [
                 'Pendientes' => $derivacionesEstado1,
                 'Aceptadas' => $derivacionesEstado2MesActual,
                 'Finalizadas' => $derivacionesEstado3MesActual,
                 
             ];
    
        return view('pie.pie-index', [
            'derivaciones' => $derivaciones,
            'derivaciones2' => $derivaciones2,
            'estadoId' => $estadoId,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'derivacionesEstado1' => $derivacionesEstado1,
            'derivacionesEstado2MesActual' => $derivacionesEstado2MesActual,
            'derivacionesEstado3MesActual' => $derivacionesEstado3MesActual,
            'derivacionesMesActual' => $derivacionesMesActual,
            'estadisticas' => $estadisticas,

        ]);
    }
}
