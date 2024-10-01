<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category; // Importa el modelo Category

class EquipoDirectivoController extends BaseController
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

        return view('equipo-directivo-profile');
    }

    // Mostrar formulario de creación de usuario
    public function create()
    {
        $categories = Category::all(); // Obtener todas las categorías
        return view('crear-usuario', compact('categories'));
    }

    // Guardar nuevo usuario en la base de datos
  // Guardar nuevo usuario en la base de datos
public function store(Request $request)
{
    \Log::info('Datos recibidos para crear usuario:', $request->all());

    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:user,email',
        'password' => 'required|string|min:8|confirmed',
        'id_category' => 'required|integer',
        'role' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    \Log::info('Validación exitosa');

    // Manejar la imagen
    $nombreArchivo = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $nombreArchivo = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $nombreArchivo); // Guardar en public/images
    }

    DB::table('user')->insert([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'id_category' => $request->id_category,
        'role' => $request->role ?? 'Default Role',
        'image' => $nombreArchivo ? 'images/' . $nombreArchivo : null, // Guardar la ruta relativa
    ]);

    \Log::info('Usuario guardado correctamente');

    return redirect()->route('equipo-directivo.profile')->with('success', 'Usuario creado correctamente.');
}

    // Función para listar usuarios
    public function listUsers()
    {
        $users = User::with('category')->paginate(10); // Cambia 10 por el número de usuarios que deseas mostrar por página
        return view('equipo-directivo.list-users', compact('users'));
    }
    // Función para editar usuario
    public function editUser($user_id)
    {
        $user = User::where('user_id', $user_id)->firstOrFail();
        $categories = Category::all();
        return view('equipo-directivo.edit-user', compact('user', 'categories'));
    }

    // Función para actualizar el usuario
    public function updateUser(Request $request, $user_id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $user_id . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'id_category' => 'required|integer',
            'role' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para imagen
        ]);

        $user = User::where('user_id', $user_id)->firstOrFail();

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Si se subió una nueva imagen, se guarda
            $nombreArchivo = $request->file('image')->store('images', 'public');
        } else {
            // Mantener la imagen existente
            $nombreArchivo = $user->image;
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'id_category' => $request->id_category,
            'role' => $request->role ?? $user->role,
            'image' => $nombreArchivo,
        ]);

        return redirect()->route('list-users')->with('success', 'Usuario actualizado correctamente.');
    }

    // Función para eliminar usuario
    public function deleteUser($user_id)
    {
        User::where('user_id', $user_id)->delete();
        return redirect()->route('list-users')->with('success', 'Usuario eliminado correctamente.');
    }
}
