<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrevista;
use App\Models\Derivacion;
use App\Models\MotivoEntrevista;
use Illuminate\Support\Facades\Log;

class EntrevistaController extends Controller
{
    // Método para mostrar el formulario de creación de entrevista
    public function entrevistaAlumno($id, $tipo_entrevista,$citacion)
    {
        // Encontrar la derivación (relacionada con el estudiante) por ID
        $derivacion = Derivacion::with('entrevista')->findOrFail($id);
        
        // Obtener los motivos de entrevista existentes
        $motivos = MotivoEntrevista::all();

        // Inicializar tipoEntrevista con el valor recibido
        $tipoEntrevista = $tipo_entrevista;
        $citacionId = $citacion;

        // Retornar la vista y pasar la derivación, motivos y tipo de entrevista
        return view('entrevistas.entrevista-alumno', compact('derivacion', 'motivos', 'tipoEntrevista','citacionId'));
    }

    public function entrevistaApoderado($id, $tipo_entrevista,$citacion)
    {
        // Encontrar la derivación (relacionada con el estudiante) por ID
        $derivacion = Derivacion::with('entrevista')->findOrFail($id);
        
        // Obtener los motivos de entrevista existentes
        $motivos = MotivoEntrevista::all();

        // Inicializar tipoEntrevista con el valor recibido
        $tipoEntrevista = $tipo_entrevista;
        $citacionId = $citacion;

        // Retornar la vista y pasar la derivación, motivos y tipo de entrevista
        return view('entrevistas.entrevista-apoderado', compact('derivacion', 'motivos', 'tipoEntrevista','citacionId'));
    }

    // Método para guardar la entrevista en la base de datos
    public function store(Request $request)
    {
        Log::info('Método store fue llamado.');

        // Log de los datos del request antes de validación
        Log::info('Datos del request antes de validación:', $request->all());

        // Validar los campos del formulario
        $request->validate([
            'nombre_entrevistado' => 'required|string|max:255',
            'curso' => 'required|string|max:255',
            'entrevistador' => 'required|string|max:255',
            'motivo_id' => 'required|string',
            'nuevo_motivo' => 'nullable|string|max:255',
            'desarrollo_entrevista' => 'required|string',
            'acuerdos.*' => 'required|string|max:255',
            'plazos.*' => 'required|date',
            'derivacion_id' => 'required|exists:derivacions,id',
            'tipo_entrevista' => 'required|string|max:255',
            'citacionId' => 'required|string',

        ]);

        Log::info('Validación pasada, continuando con la lógica de la entrevista.');

        // Comprobar si se seleccionó un nuevo motivo
        $motivoId = null;
        if ($request->motivo_id === 'otro' && $request->nuevo_motivo) {
            // Eliminar espacios en blanco y convertir a mayúsculas
            $nuevoMotivoTexto = strtoupper(trim($request->nuevo_motivo));
            Log::info('Nuevo motivo recibido:', ['nuevo_motivo' => $nuevoMotivoTexto]);

            // Verificar si el nuevo motivo ya existe en la base de datos
            $existingMotivo = MotivoEntrevista::where('motivo', $nuevoMotivoTexto)->first();

            if ($existingMotivo) {
                $motivoId = $existingMotivo->id;
                Log::info('Motivo existente encontrado:', ['motivo_id' => $motivoId]);
            } else {
                // Intentar crear el nuevo motivo
                try {
                    $nuevoMotivo = MotivoEntrevista::create(['motivo' => $nuevoMotivoTexto]);
                    $motivoId = $nuevoMotivo->id; // Usar el ID del nuevo motivo
                    Log::info('Nuevo motivo creado:', ['motivo_id' => $motivoId]);
                } catch (\Exception $e) {
                    Log::error('Error al crear el nuevo motivo:', ['error' => $e->getMessage()]);
                    return back()->withErrors(['error' => 'Error al crear el nuevo motivo.']);
                }
            }
        } else {
            $motivoId = $request->motivo_id; // Asegúrate de que aquí no usas `trim()`
            Log::info('Motivo seleccionado:', ['motivo_id' => $motivoId]);
        }

        // Crear una nueva instancia de la entrevista
        $entrevista = new Entrevista();
        $entrevista->fecha = now(); // O establece una fecha específica si es necesario
        $entrevista->derivacion_id = $request->derivacion_id; // Recuperamos el derivacion_id
        $entrevista->tipo_entrevista = $request->tipo_entrevista; // Recuperamos el tipo_entrevista
        $entrevista->nombre_entrevistado = strtoupper(trim($request['nombre_entrevistado']));
        $entrevista->curso = strtoupper(trim($request['curso']));
        $entrevista->entrevistador = strtoupper(trim($request['entrevistador']));
        $entrevista->motivo_id = $motivoId; // Usa el ID del motivo correspondiente
        $entrevista->desarrollo_entrevista = strtoupper(trim($request['desarrollo_entrevista']));
        $entrevista->citacion_id = $request['citacionId']; // Usa el ID del motivo correspondiente

        // Guardar los acuerdos y plazos como un array
        $acuerdos = [];
        foreach ($request->acuerdos as $index => $acuerdo) {
            if (isset($request->plazos[$index])) {
                $acuerdos[] = [
                    'acuerdo' => $acuerdo,
                    'plazo' => $request->plazos[$index],
                ];
            }
        }

        // Asignar el array de acuerdos a la entrevista
        $entrevista->acuerdos = $acuerdos;

        // Guardar la entrevista en la base de datos
        $entrevista->save();

        // Redireccionar a la página deseada con un mensaje de éxito
        return back()->with(['success' => 'Entrevista guardada con éxito.']);
    }

    public function destroy($id)
{
    $entrevista = Entrevista::findOrFail($id);
    $entrevista->delete();

    return redirect()->back()->with('success', 'Los detalles de la derivación se actualizaron correctamente.');
}

}
