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
            $table->integer('estado_curso');

            $table->unsignedBigInteger('id_colegio');
            $table->foreign('id_colegio')->references('id')->on('colegios')->onDelete('cascade');

            $table->timestamps();
        });
        
        DB::table('cursos')->insert([
            ['nombre_curso' => 'PRIMERO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'PRIMERO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'PRIMERO C', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEGUNDO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEGUNDO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEGUNDO C', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEGUNDO D', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'TERCERO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'TERCERO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'TERCERO C', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'TERCERO D', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'CUARTO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'CUARTO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'CUARTO C', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'QUINTO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'QUINTO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'QUINTO C', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEXTO A', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEXTO B', 'estado_curso' => 1, 'id_colegio' => 1],
            ['nombre_curso' => 'SEXTO C', 'estado_curso' => 1, 'id_colegio' => 1],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
