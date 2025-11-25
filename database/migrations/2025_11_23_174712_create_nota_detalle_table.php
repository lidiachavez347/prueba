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
        Schema::create('nota_detalle', function (Blueprint $table) {
            $table->id();

            $table->decimal('nota_ser', 5, 2)->nullable();
            $table->decimal('nota_saber', 5, 2)->nullable();
            $table->decimal('nota_hacer', 5, 2)->nullable();
            $table->decimal('nota_decidir', 5, 2)->nullable();

            $table->unsignedBigInteger('id_trimestre');
            $table->unsignedBigInteger('id_materia');
            $table->unsignedBigInteger('id_estudiante');

            $table->decimal('promedio_materia', 5, 2)->nullable();

            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_curso')->references('id')->on('cursos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_detalle');
    }
};
