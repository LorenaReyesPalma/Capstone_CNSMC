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
        Schema::create('motivos_entrevista', function (Blueprint $table) {
            $table->id();
            $table->string('motivo');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('motivos_entrevista');
    }
    
};
