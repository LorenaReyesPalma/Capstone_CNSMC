<?php
// app/Models/Derivacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Citacion; 

class Derivacion extends Model
{
    use HasFactory;

    // Define los atributos que se pueden asignar masivamente
    protected $fillable = [
        'run',
        'digito_ver',
        'nombre_estudiante',
        'edad',
        'curso',
        'fecha_derivacion',
        'adulto_responsable',
        'telefono',
        'programa_integracion',
        'programa_retencion',
        'indicadores_personal',
        'indicadores_familiar',
        'indicadores_socio_comunitario',
        'motivo_derivacion',
        'acciones_realizadas',
        'sugerencias',
        'colaborador',
        'estado_id',

    ];

    // Si tienes relaciones o métodos adicionales, agrégales aquí

    public function citaciones()
    {
        return $this->hasMany(Citacion::class);
    }
}
