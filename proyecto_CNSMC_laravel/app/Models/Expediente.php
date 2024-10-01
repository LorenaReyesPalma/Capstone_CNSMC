<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'expedientes';

    // Definir los atributos que se pueden asignar masivamente
    protected $fillable = [
        'run',
        'digito_ver',
        'curso',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'region',
        'comuna_residencia',
        'email',
        'telefono',
        // Agrega otros campos si es necesario
    ];

    // Desactivar timestamps si no se utilizan
    public $timestamps = false;
}
