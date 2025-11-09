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
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_asig');
            $table->boolean('estado_asig')->default(1);
            $table->unsignedBigInteger('id_area');
            $table->foreign('id_area')->references('id')->on('areas')->onDelete('cascade');

            $table->timestamps();
        });
        DB::table('asignaturas')->insert([
            ['nombre_asig' => 'CIENCIAS NATURALES', 'estado_asig' => 1, 'id_area' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'LENGUAJE Y COMUNICACION', 'estado_asig' => 1, 'id_area' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'CIENCIAS SOCIALES', 'estado_asig' => 1, 'id_area' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'ARTES PLASTICAS Y VISUALES', 'estado_asig' => 1, 'id_area' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'EDUCACION FISICA Y DEPORTES', 'estado_asig' => 1, 'id_area' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'EDUCACION MUSICAL', 'estado_asig' => 1, 'id_area' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'MATEMATICA', 'estado_asig' => 1, 'id_area' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'TECNICA TECNOLOGICA', 'estado_asig' => 1, 'id_area' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre_asig' => 'VALORES ESPIRITUALIDAD Y RELIGION', 'estado_asig' => 1, 'id_area' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas');
    }
};
