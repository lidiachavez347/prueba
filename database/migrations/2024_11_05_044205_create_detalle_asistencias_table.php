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
        Schema::create('detalle_asistencias', function (Blueprint $table) {
            $table->id();
            $table->string('estado'); //'presente', 'ausente', 'tarde', 'justificado'
            $table->date('fecha'); 
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('estudiantes')->onDelete('cascade');

            $table->unsignedBigInteger('curso_id'); // FK a usuarios (tutor)
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_asistencias');
    }
};
