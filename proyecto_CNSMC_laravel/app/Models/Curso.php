<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla en la base de datos
    protected $table = 'curso';

    // Especificar las columnas que pueden ser asignadas en masa
    protected $fillable = [
        'cod_tipo_ensenanza',
        'cod_grado',
        'desc_grado',
        'letra_curso',
    ];

    // Indicar que el modelo usa las columnas de timestamps
    public $timestamps = false;
}
