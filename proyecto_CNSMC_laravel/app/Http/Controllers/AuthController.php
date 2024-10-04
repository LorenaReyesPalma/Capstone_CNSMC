<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Obtener los datos del usuario
        $user = User::where('email', $request->email)->first();


        if ($user && Hash::check($request->password, $user->password)) {
            // Autenticar al usuario manualmente
            Auth::loginUsingId($user->user_id);

            // Verificar la autenticación
            if (Auth::check()) {
                \Log::info('Usuario autenticado: ' . Auth::user()->user_id);
            } else {
                \Log::info('Usuario no autenticado');
            }

            // Obtener la categoría del usuario
            $id_category = $user->id_category;

            // Redirigir según la categoría del usuario
            return redirect($this->getRedirectUrl($id_category));
        } else {
            \Log::error('Error de autenticación para el correo: ' . $request->email);
            return redirect()->route('login')->withErrors(['email' => 'Credenciales incorrectas.']);
        }
    }

    private function getRedirectUrl($id_category)
    {
        switch ($id_category) {
            case 1:
                return route('equipo-directivo.profile');
            case 2:
                return route('convivencia-escolar.profile');
            case 3:
                return route('profesores-jefes.profile');
            case 4:
                return route('profesores-asignatura.profile');
            case 5:
                return route('pie.profile');
            default:
                return route('login'); // Redirige al login si la categoría no es válida
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function enviarRecuperacion(Request $request)
    {
        // Validar que el campo email esté presente y sea un email válido
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $correoDestino = $request->email;
    
        // Verificar si el correo existe en la tabla users
        $user = User::where('email', $correoDestino)->first();
    
        if (!$user) {
            // Mensaje genérico para no revelar si el correo está registrado
            return back()->withErrors(['email' => 'Si el correo existe en el sistema, se enviará una nueva contraseña.']);
        }
    
        // Generar una contraseña aleatoria de 8 caracteres
        $password = Str::random(8);
    
        // Hashear la nueva contraseña antes de guardarla en la base de datos
        $passwordHashed = Hash::make($password);
    
        // Actualizar la nueva contraseña hasheada en la base de datos para el usuario
        $user->password = $passwordHashed;
        $user->save();
    
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
            $mail->addAddress($correoDestino); // Agrega un destinatario
    
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
    
            // Contenido HTML
            $mail->Body = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Recuperación de Contraseña</title>
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
                    color: #fff !important; /* Color del texto del botón */
                    background-color: #007bff; /* Color de fondo */
                    text-decoration: none;
                    border-radius: 5px;
                    border: none; /* Sin borde */
                    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
                    transition: background-color 0.3s ease; /* Transición suave */
                }
                .btn:hover {
                    background-color: #0056b3; /* Color de fondo al pasar el mouse */
                }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Hola!</h1>
                    <p>Has solicitado recuperar tu contraseña. A continuación, encontrarás tu nueva contraseña:</p>
                    <h2>' . $password . '</h2>
                    <p>Por motivos de seguridad, te recomendamos cambiar esta contraseña en cuanto inicies sesión.</p>
                    <p>Si no solicitaste este cambio, ignora este correo.</p>
                    <a href="https://cmvapp.cl/proyecto_capston_laravel/public/login" class="btn">Iniciar Sesión</a>
                    <div class="footer">
                        <p>Gracias,<br>El equipo de soporte.</p>
                    </div>
                </div>
            </body>
            </html>
            ';
    
    
            $mail->send();
            return back()->with('status', 'Se ha enviado una nueva contraseña a tu correo.');
        } catch (Exception $e) {
            // Registra el error para depuración
            Log::error("Error al enviar correo: " . $e->getMessage());
            return back()->withErrors(['email' => 'No se pudo enviar el correo de recuperación.']);
        }
    }
}