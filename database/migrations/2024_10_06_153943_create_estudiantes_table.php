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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('imagen_es')->nullable(true);
            $table->string('nombres_es');
            $table->string('apellidos_es');
            $table->date('fecha_nac_es');
            $table->boolean('genero_es')->default(1);
            $table->string('ci_es', 20);
            $table->integer('rude_es')->unique();
            $table->boolean('estado_es')->default(1);

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
        Schema::dropIfExists('estudiantes');
    }
};
