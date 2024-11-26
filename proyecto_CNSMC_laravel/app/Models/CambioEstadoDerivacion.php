<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambioEstadoDerivacion extends Model
{
    use HasFactory;

    // Define el nombre de la tabla
    protected $table = 'cambio_estado_derivacion';

    // Permitir asignación masiva en estas columnas
    protected $fillable = [
        'derivacion_id',
        'estado_id',
        'fecha_actualizacion',
        'colaborador_acepta', // Asegúrate de que este campo esté aquí

    ];

    /**
     * Relación con la tabla derivacions.
     * Cada cambio de estado pertenece a una derivación.
     */
    public function derivacion()
    {
        return $this->belongsTo(Derivacion::class, 'derivacion_id');
    }
}
