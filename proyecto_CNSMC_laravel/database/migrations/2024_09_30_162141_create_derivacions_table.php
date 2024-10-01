<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_derivacions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerivacionsTable extends Migration
{
    public function up()
    {
        Schema::create('derivacions', function (Blueprint $table) {
            $table->id();
            $table->string('run');
            $table->string('digito_ver');
            $table->string('nombre_estudiante');
            $table->integer('edad');
            $table->string('curso');
            $table->date('fecha_derivacion');
            $table->string('adulto_responsable');
            $table->string('telefono');
            $table->boolean('programa_integracion')->default(false);
            $table->boolean('programa_retencion')->default(false);
            $table->json('indicadores_personal')->nullable();
            $table->json('indicadores_familiar')->nullable();
            $table->json('indicadores_socio_comunitario')->nullable();
            $table->text('motivo_derivacion')->nullable();
            $table->text('acciones_realizadas')->nullable();
            $table->text('sugerencias')->nullable();
            $table->string('colaborador')->nullable();
            $table->integer('estado_id');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('derivacions');
    }
}
