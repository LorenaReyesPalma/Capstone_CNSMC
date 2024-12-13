<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    use HasFactory;

    // Especifica la tabla a la que este modelo está asociado
    protected $table = 'entrevistas';

    protected $casts = [
        'acuerdos' => 'array', // Convierte la columna 'acuerdos' a un array automáticamente
    ];
    
    // Define los campos que pueden ser llenados mediante asignación masiva (mass assignment)
    protected $fillable = [
        'fecha',
        'derivacion_id',
        'tipo_entrevista',
        'nombre_entrevistado',
        'curso',
        'entrevistador',
        'motivo_id',
        'desarrollo_entrevista',
        'acuerdos',
        'firma_entrevistador',
        'firma_estudiante',
    ];

    public function motivo()
    {
        return $this->belongsTo(MotivoEntrevista::class, 'motivo_id'); // Asegúrate de que 'motivo_id' sea el nombre correcto del campo de la clave foránea
    }
}
