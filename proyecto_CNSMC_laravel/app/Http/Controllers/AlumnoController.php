<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Expediente;
use App\Models\Derivacion;
use App\Models\User;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;


    use Illuminate\Support\Facades\Http; // Asegúrate de que esta línea esté aquí

    class AlumnoController extends Controller
    {

        public function verExpediente($run, $dv)
        {
            // Lógica para obtener el alumno y el expediente
            $alumno = Matricula::where('run', $run)->where('digito_ver', $dv)->first();
            $expediente = Expediente::where('run', $run)->where('digito_ver', $dv)->first();
            $cursos = Curso::all();
            $user = Auth::user(); 
        
            // Verificar los valores de region y comuna_id
            \Log::info("Region: " . $expediente->region);
            \Log::info("Comuna ID: " . $expediente->comuna_id);
        
            // Definir las variables fechaDesde, fechaHasta y estadoId
            $fechaDesde = request('fecha_desde', ''); // Valor por defecto vacío
            $fechaHasta = request('fecha_hasta', ''); // Valor por defecto vacío
            $estadoId = request('estado_id', ''); // Valor por defecto vacío
        
            // Obtener las derivaciones con filtros
            $derivacionesQuery = Derivacion::where('run', $run)
                ->where('digito_ver', $dv);
        
            // Agregar filtros de fecha si están presentes
            if ($fechaDesde) {
                $derivacionesQuery->where('fecha_derivacion', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $derivacionesQuery->where('fecha_derivacion', '<=', $fechaHasta);
            }
            if ($estadoId) {
                $derivacionesQuery->where('estado_id', $estadoId);
            }
        
            $derivaciones = $derivacionesQuery->get()->map(function ($derivacion) {
                $colaborador = User::where('user_id', $derivacion->colaborador)->first();
                $derivacion->colaborador_nombre = $colaborador ? $colaborador->first_name . ' ' . $colaborador->last_name : 'Desconocido';
                return $derivacion;
            });
        
            // Obtener el primer registro de derivacion
            $derivacion = $derivaciones->first();
        
            // Obtener regiones
            $regiones = $this->obtenerRegiones();
            
            // Verificar el valor de region y buscar el nombre
            $regionCodigo = (string)$expediente->region; // Convierte a string si es un entero
            $nombreRegion = $regiones->firstWhere('codigo', $regionCodigo);
            $nombreRegion = $nombreRegion ? $nombreRegion['nombre'] : 'Desconocido';
            
            // Obtener comunas según el region_id
            $comunas = $this->obtenerComunasPorRegion($regionCodigo);
             
            // Verificar el valor de comuna_id y buscar el nombre de la comuna
            $comunaCodigo = (string)$expediente->comuna_id; // Convierte a string si es un entero
            $nombreComuna = $comunas->firstWhere('codigo', $comunaCodigo);
            $nombreComuna = $nombreComuna ? $nombreComuna['nombre'] : 'Desconocido';
        
            return view('alumno.expediente', compact('alumno', 'expediente', 'regiones', 'comunas', 'derivaciones', 'nombreRegion', 'nombreComuna', 'cursos', 'derivacion', 'fechaDesde', 'fechaHasta', 'estadoId','user'));
        }
        
        
        
        
        // Función para obtener regiones desde la API
        private function obtenerRegiones()
        {
            $response = file_get_contents("https://apis.digital.gob.cl/dpa/regiones");
            $regiones = json_decode($response, true); // Decodificar el JSON a un array
            return collect($regiones); // Convertir a una colección de Laravel
        }
        
        
        
        private function obtenerComunasPorRegion($regionId)
        {
            $response = file_get_contents("https://apis.digital.gob.cl/dpa/regiones/$regionId/comunas");
            $comunas = json_decode($response, true); // Decodificar el JSON a un array
            return collect($comunas); // Convertir a una colección de Laravel
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
            $expediente->comuna_id = 0;
            $expediente->region = 0;

            $expediente->email = $matricula->email;
            $expediente->telefono = $matricula->telefono;

            // Guardar el expediente en la base de datos
            $expediente->save();

            return redirect()->route('alumno.expediente', ['run' => $run, 'dv' => $dv])
                            ->with('success', 'Expediente creado exitosamente.');
        }


        public function updateExpediente(Request $request, $run, $dv)
        {
            // Validar los datos
            $validatedData = $request->validate([
                'curso' => 'required|string|max:255',
                'genero' => 'required|string|in:M,F',
                'fecha_nacimiento' => 'required|date',
                'direccion' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:15',
                'region' => 'nullable|integer',  // Validar que el ID de la región es un número
                'comuna_id' => 'nullable|integer',  // Validar que el ID de la comuna es un número
                'adulto_responsable' => 'nullable|string|max:255', 
            ]);
       

            // Obtener el expediente existente
            $expediente = Expediente::where('run', $run)
                ->where('digito_ver', $dv)
                ->firstOrFail();  // Si no se encuentra, lanzará un 404
        
            // Actualizar los datos del expediente
            $expediente->curso = $validatedData['curso'];
            $expediente->genero = $validatedData['genero'];
            $expediente->fecha_nacimiento = $validatedData['fecha_nacimiento'];
            $expediente->direccion = $validatedData['direccion'];
            $expediente->email = $validatedData['email'];
            $expediente->telefono = $validatedData['telefono'];
            $expediente->region = $validatedData['region'];
            $expediente->comuna_id = $validatedData['comuna_id'];
            $expediente->adulto_responsable = $validatedData['adulto_responsable'];
            
            // Guardar los cambios
            $expediente->save();
        
            // Redireccionar o devolver una respuesta
            return redirect()->route('alumno.expediente', ['run' => $run, 'dv' => $dv])
                             ->with('success', 'Expediente actualizado correctamente.');
        }
        
        
        public function showExpediente($run, $dv)
        {
            // Recuperar el expediente más reciente
            $expediente = Expediente::where('run', $run)
                                    ->where('digito_ver', $dv)
                                    ->firstOrFail();
           $user = Auth::user();                         
            return view('expediente.show', compact('expediente','user'));
        }
    }