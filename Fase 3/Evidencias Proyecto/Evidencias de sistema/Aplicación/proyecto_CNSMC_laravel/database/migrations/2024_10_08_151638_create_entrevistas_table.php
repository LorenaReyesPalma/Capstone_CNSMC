<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('entrevistas', function (Blueprint $table) {
            $table->id();  // Llave primaria
            $table->date('fecha');
            $table->string('nombre_estudiante');
            $table->string('curso');
            $table->string('entrevistador');
            $table->text('motivo_entrevista');
            $table->text('desarrollo_entrevista');
            $table->text('acuerdos');
            $table->string('firma_entrevistador');
            $table->string('firma_estudiante');
            $table->timestamps(); // Esto añade los campos created_at y updated_at
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('entrevistas');
    }
    
};
