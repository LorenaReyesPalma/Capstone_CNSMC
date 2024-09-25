<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_category'; // Clave primaria si no sigue la convención
    public $timestamps = false; // Si la tabla no tiene columnas de timestamps

    protected $fillable = ['id_category', 'category']; // Campos que se pueden llenar masivamente

    public function users()
    {
        return $this->hasMany(User::class, 'id_category', 'id_category');
    }

}
