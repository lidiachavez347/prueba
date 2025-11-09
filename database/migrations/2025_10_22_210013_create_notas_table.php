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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();

            $table->integer('nota');

            $table->unsignedBigInteger('id_estudiante');
            $table->foreign('id_estudiante')->references('id')->on('estudiantes')->onDelete('cascade');

            $table->unsignedBigInteger('id_materia');
            $table->foreign('id_materia')->references('id')->on('asignaturas')->onDelete('cascade');

            $table->unsignedBigInteger('id_criterio');
            $table->foreign('id_criterio')->references('id')->on('criterios')->onDelete('cascade');

            $table->unsignedBigInteger('id_trimestre');
            $table->foreign('id_trimestre')->references('id')->on('trimestres')->onDelete('cascade');
            
            $table->unsignedBigInteger('id_dimencion');
            $table->foreign('id_dimencion')->references('id')->on('dimencions')->onDelete('cascade');

            $table->string('detalle');
            $table->date('fecha');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
