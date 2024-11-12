<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category; // Importa el modelo Category
use App\Models\Curso;

class EquipoDirectivoController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if (Auth::check()) {
            // Recuperar el usuario autenticado
            $user = Auth::user();
            \Log::info('Usuario autenticado: ' . $user->user_id);
            $categories = Category::all();
            $cursos = Curso::all(); // Ajusta `Curso` al modelo correspondiente a tu tabla de cursos
            $profesores = User::where('id_category' , 3)->get(); // Ajusta `User` según el modelo y condición para profesores
            $profesorActual = $profesores->first();  // o cualquier lógica para obtener al profesor actual

        } else {
            \Log::info('Usuario no autenticado');
            return redirect()->route('login'); // Redirigir si no está autenticado
        }

        // Pasar el usuario a la vista
        return view('equipo-directivo-profile', compact('user','categories','cursos','profesores','profesorActual'));
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
      $categories = Category::all(); // Obtener todas las categorías
  
      $request->validate([
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'email' => 'required|email|unique:user,email',
          'id_category' => 'required|integer',
          'role' => 'nullable|string|max:255',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      ]);
  
      \Log::info('Validación exitosa');
  
      // Generar la contraseña automática
      $year = date('Y');
      $password = strtolower(substr($request->first_name, 0, 2) . substr($request->last_name, 0, 2) . $year);
  
      // Manejar la imagen
      $nombreArchivo = null;
      if ($request->hasFile('image')) {
          $image = $request->file('image');
          $nombreArchivo = time() . '.' . $image->getClientOriginalExtension();
          $image->move(public_path('images'), $nombreArchivo); // Guardar en public/images
      }
  
      // Insertar el usuario con la contraseña generada
      DB::table('user')->insert([
          'first_name' => strtolower($request->first_name),
          'last_name' => strtolower($request->last_name),
          'email' => strtolower($request->email),
          'password' => Hash::make($password),
          'id_category' => $request->id_category,
          'role' => $request->role ?? 'Default Role',
          'image' => 'images/perfil-de-usuario.webp' // Guardar la ruta relativa
      ]);
  
      \Log::info('Usuario guardado correctamente con contraseña generada automáticamente.');
  
      return redirect()->route('equipo-directivo.profile')->with('success', 'Usuario creado correctamente.');
  }
  

    // Función para listar usuarios
    public function listUsers(Request $request)
    {
        $categoryId = $request->input('id_category');
    
        // Obtener usuarios según la categoría seleccionada
        $users = User::with('category')
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('id_category', $categoryId);
            })
            ->paginate(9);
    
        // Obtener todas las categorías
        $categories = Category::all();
    
        // Pasar el parámetro de categoría a la vista
        return view('equipo-directivo.list-users', compact('users', 'categories', 'categoryId'));
    }
    

    // Función para editar usuario
    public function editUser($user_id)
    {
        $user = User::where('user_id', $user_id)->firstOrFail();
        $categories = Category::all();
        return view('equipo-directivo.edit-user', compact('user', 'categories'));
    }

    public function updateUser(Request $request, $user_id)
    {
        // Validación de los campos requeridos
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:user,email,' . $user_id . ',user_id',
            'id_category' => 'required|integer',
            'role' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Recuperar el usuario
        $user = User::where('user_id', $user_id)->firstOrFail();
    
        // Verificar si se desea cambiar la contraseña
        if ($request->changePassword) {
            $request->validate([
                'current_password' => 'required|string', // Validar contraseña actual
                'password' => 'required|string|min:8|confirmed', // Nueva contraseña
            ]);
    
            // Verificar la contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
    
            // Actualizar la contraseña
            $user->password = Hash::make($request->password);
        }
    
        // Manejar la imagen
        $nombreArchivo = $user->image; // Mantener la imagen existente por defecto
    
        if ($request->hasFile('image')) {
            // Si se subió una nueva imagen, se guarda
            $nombreArchivo = $request->file('image')->store('images', 'public');
        }
    
        // Actualizar el usuario
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email ?? $user->email, // Mantener el email existente si no se proporciona uno nuevo
            'id_category' => $request->id_category,
            'role' => $request->role ?? $user->role, // Usar el valor existente si no se proporciona uno nuevo
            'image' => $nombreArchivo,
        ]);
    
        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }
    


    // Función para eliminar usuario
    public function deleteUser($user_id)
    {
        DB::table('curso_user')->where('user_id', $user_id)->delete();
        User::where('user_id', $user_id)->delete();
        return redirect()->route('list-users')->with('success', 'Usuario eliminado correctamente.');
    }
}