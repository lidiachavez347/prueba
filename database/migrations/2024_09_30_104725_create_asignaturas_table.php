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
            $table->string('nombre_asignatura');
            $table->integer('estado_asignatura');
            $table->timestamps();
        });
        DB::table('asignaturas')->insert([
            ['nombre_asignatura' => 'Lenguaje','estado_asignatura'=> 1],
            ['nombre_asignatura' => 'Matematica','estado_asignatura'=> 1],
            ['nombre_asignatura' => 'Sociales','estado_asignatura'=> 1],
            ['nombre_asignatura' => 'Biologia','estado_asignatura'=> 1],
            

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
