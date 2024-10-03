<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Derivacion;


class Citacion extends Model
{
    use HasFactory;

    protected $table = 'citaciones';


    protected $fillable = [
        'derivacion_id', 'tipo_accion', 'fecha_citacion', 'hora_citacion', 'observaciones','colaborador','estado'
    ];

    public function derivacion()
    {
        return $this->belongsTo(Derivacion::class);
    }
}
