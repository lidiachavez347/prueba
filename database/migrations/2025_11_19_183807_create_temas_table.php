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
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('detalle');
            $table->string('archivo')->nullable();
            $table->string('imagen')->nullable();
            $table->string('video')->nullable();
            $table->integer('estado');

            $table->unsignedBigInteger('id_asignatura');
            $table->foreign('id_asignatura')->references('id')->on('asignaturas')->onDelete('cascade');
            
            $table->unsignedBigInteger('id_trimestre');
            $table->foreign('id_trimestre')->references('id')->on('trimestres')->onDelete('cascade');
            
            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_curso')->references('id')->on('cursos')->onDelete('cascade');
            
            $table->boolean('avance')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temas');
    }
};
