<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
}
