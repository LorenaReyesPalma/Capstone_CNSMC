<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoEntrevista extends Model
{
    protected $table = 'motivos_entrevista';

    protected $fillable = ['motivo'];
}
