<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_curso');
            $table->string('paralelo',1);
            
            $table->unsignedBigInteger('id_gestion');
            $table->foreign('id_gestion')->references('id')->on('gestiones')->onDelete('cascade');

            $table->boolean('estado_curso')->default(1);
            $table->timestamps();
            
            $table->unique(['nombre_curso', 'paralelo', 'id_gestion'], 'unique_curso_paralelo_gestion');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
