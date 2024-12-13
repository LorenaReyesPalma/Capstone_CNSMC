<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Derivacion;
use App\Models\User;
use App\Models\CambioEstadoDerivacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class ConvivenciaEscolarController extends Controller
{
    public function index(Request $request)
    {
        // // Obtener los filtros de fecha y estado desde la solicitud
         $fechaDesde = $request->input('fecha_desde');
         $fechaHasta = $request->input('fecha_hasta');
         $estadoId = $request->input('estado_id');
    
        // // Obtener todas las derivaciones y aplicar los filtros
        // $derivaciones = Derivacion::query();
    
        // // Aplicar filtro por fecha desde
        // if ($fechaDesde) {
        //     $derivaciones->where('fecha_derivacion', '>=', $fechaDesde);
        // }
    
        // // Aplicar filtro por fecha hasta
        // if ($fechaHasta) {
        //     $derivaciones->where('fecha_derivacion', '<=', $fechaHasta);
        // }
    
        // // Aplicar filtro por estado
        // if ($estadoId) {
        //     $derivaciones->where('estado_id', $estadoId);
        // }

        $estadoAceptado = $request->input('estado_aceptado', 2); // Por defecto, estado 2 si no se pasa un valor

        $derivaciones = Derivacion::where('estado_id', $estadoAceptado)->get();

    
        // $derivaciones = $derivaciones->get();
    
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
        $derivaciones2 = Derivacion::where('estado_id', 1)->orderBy('fecha_derivacion', 'asc')->get();
    
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
        $derivacionesEstado1 = Derivacion::where('estado_id', 1)
        ->whereMonth('fecha_derivacion', $mesActual)
        ->whereYear('fecha_derivacion', $anioActual)
        ->count();

        // Contar cuántas derivaciones están en estado 2 en el mes actual
        $derivacionesEstado2MesActual = Derivacion::where('estado_id', 2)
            ->whereMonth('fecha_derivacion', $mesActual)
            ->whereYear('fecha_derivacion', $anioActual)
            ->count();

        // Contar cuántas derivaciones están en estado 3 en el mes actual
        $derivacionesEstado3MesActual = Derivacion::where('estado_id', 3)
            ->whereMonth('fecha_derivacion', $mesActual)
            ->whereYear('fecha_derivacion', $anioActual)
            ->count();

        // Contar cuántas derivaciones totales hay en el mes actual
        $derivacionesMesActual = Derivacion::whereMonth('fecha_derivacion', $mesActual)
            ->whereYear('fecha_derivacion', $anioActual)
            ->count();

            $estadisticas = [
                'Pendientes' => $derivacionesEstado1,
                'Aceptadas' => $derivacionesEstado2MesActual,
                'Finalizadas' => $derivacionesEstado3MesActual,
                
            ];

    
        return view('convivencia.convivencia-index', [
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
    
    public function aceptarDerivacion($id)
    {
        try {

        $colaborador = Auth::user(); // Obtenemos el usuario autenticado

        // Buscar la derivación por ID
        $derivacion = Derivacion::findOrFail($id);
        $nombreEstudiante = $derivacion->nombre_estudiante;
        $cursoEstudiante = $derivacion->curso;
        // Cambiar estado a completado (por ejemplo, estado_id = 2)
        $derivacion->estado_id = 2;
        $derivacion->save();
        $usuarioLogueado = auth()->user(); // El usuario logueado

    
        // Registrar el cambio de estado en la tabla CambioEstadoDerivacion
        CambioEstadoDerivacion::create([
            'derivacion_id' => $derivacion->id,
            'estado_id' => 2, // Estado completado
            'fecha_actualizacion' => now(),
            'colaborador_acepta' => $colaborador->user_id, // Agregar el ID del usuario logueado

        ]);
        $nuevoEstado = 2;
        $estadoNombre = 'Estado desconocido'; // valor por defecto
        if ($nuevoEstado == 1) {
            $estadoNombre = 'Pendiente';
        } elseif ($nuevoEstado == 2) {
            $estadoNombre = 'Aceptado';
        } elseif ($nuevoEstado == 3) {
            $estadoNombre = 'Finalizado';
        }
        $usuarios = User::where('id_category', 1)->get();

         // Enviar email a todos los usuarios
         foreach ($usuarios as $user) {
            // Configurar PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'mail.cmvapp.cl';
                $mail->SMTPAuth = true;
                $mail->Username = 'soporte@cmvapp.cl';
                $mail->Password = 'Soportecmv2043'; // La contraseña del correo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Para SSL
                $mail->Port = 465;

                // Configurar el conjunto de caracteres a UTF-8
                $mail->CharSet = 'UTF-8';

                // Destinatarios
                $mail->setFrom('soporte@marianistasmelipilla.cl', 'Soporte Derivaciones Escolares');
                $mail->addAddress($user->email); // Agrega un destinatario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Cambio de Estado de Derivación';

                // Contenido HTML
                $mail->Body = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Cambio de Estado de Derivación</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 600px;
                            margin: auto;
                            background: #fff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        }
                        h1 {
                            color: #333;
                        }
                        p {
                            line-height: 1.5;
                            color: #555;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 0.8em;
                            color: #777;
                        }
                        .btn {
                            display: inline-block;
                            padding: 10px 15px;
                            color: #fff !important;
                            background-color: #007bff;
                            text-decoration: none;
                            border-radius: 5px;
                            border: none;
                            cursor: pointer;
                            transition: background-color 0.3s ease;
                        }
                        .btn:hover {
                            background-color: #0056b3;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Hola!</h1>
                        <p>Se ha actualizado el estado de la derivación de <strong>' . $nombreEstudiante . '</strong> (Curso: ' . $cursoEstudiante . ').</p>
                        <p>Estado actual: <strong>' . $estadoNombre . '</strong>.</p>
                        <p>Por favor, revisa la derivación para más detalles.</p>
                        <a href="https://cmvapp.cl/proyecto_capston_laravel/public/login" class="btn">Iniciar Sesión</a>
                        <div class="footer">
                            <p>Gracias,<br>El equipo de soporte.</p>
                        </div>
                    </div>
                </body>
                </html>
                ';

                // Enviar el correo
                if ($mail->send()) {
                    Log::info("Correo enviado a: " . $user->email); // Log para verificar que el correo fue enviado
                } else {
                    Log::error("Error al enviar el correo a: " . $user->email); // Log en caso de error
                }
            } catch (Exception $e) {
                // Registra el error para depuración
                Log::error("Error al enviar correo a " . $user->email . ": " . $e->getMessage());
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'El estado de la derivación se actualizó correctamente, y los correos han sido enviados.');
    } catch (\Exception $e) {
        // Registrar el error para depuración
        logger()->error('Error al cambiar el estado de la derivación: ' . $e->getMessage());

        // Redirigir con mensaje de error
        return redirect()->back()->with('error', 'Hubo un problema al actualizar el estado. Por favor, intente nuevamente.');
    }
}
        


}