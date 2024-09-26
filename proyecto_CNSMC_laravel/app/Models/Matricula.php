<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Matricula extends Model
{
    // La tabla asociada al modelo
    protected $table = 'matricula';

    // La clave primaria del modelo
    protected $primaryKey = 'id';

    // Indica si el modelo usa timestamps
    public $timestamps = false;

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'ano', 'cod_tipo_ensenanza', 'cod_grado', 'desc_grado', 'letra_curso',
        'run', 'digito_ver', 'genero', 'nombres', 'apellido_paterno', 'apellido_materno',
        'direccion', 'comuna_residencia', 'email', 'telefono', 'fecha_nacimiento',
        'fecha_incorporacion_curso', 'fecha_retiro'
    ];

    // Los atributos que deberían ser tratados como fechas
    protected $dates = ['fecha_nacimiento', 'fecha_incorporacion_curso', 'fecha_retiro'];

    public function getFormattedRutAttribute()
    {
        $rut = str_pad($this->run, 8, '0', STR_PAD_LEFT); // Asegúrate de que el RUT tenga al menos 8 dígitos
        $digito = strtoupper($this->digito_ver);
    
        // Formatear el RUT con puntos
        $rutFormateado = substr($rut, 0, -6) . '.' . substr($rut, -6, 3) . '.' . substr($rut, -3) . '-' . $digito;
    
        return $rutFormateado;
    }
    
}
