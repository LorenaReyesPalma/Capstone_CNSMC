<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('run'); // RUN del alumno
            $table->string('digito_ver'); // Dígito verificador
            $table->string('curso'); // Curso
            $table->string('genero'); // Género
            $table->date('fecha_nacimiento'); // Fecha de nacimiento
            $table->string('direccion')->nullable(); // Dirección
            $table->string('comuna_residencia')->nullable(); // Comuna de residencia
            $table->string('email')->nullable(); // Email
            $table->string('telefono')->nullable(); // Teléfono
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); // Fecha de creación
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
