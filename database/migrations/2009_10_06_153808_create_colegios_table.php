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
        Schema::create('colegios', function (Blueprint $table) {
            $table->id();
            $table->string('departamento');
            $table->string('distrito');
            $table->string('unidad_educativa');
            $table->string('dependencia');
            $table->string('turno');
            $table->string('getion');
            $table->string('nivel');
            $table->string('logo');
            $table->string('direccion');
            $table->integer('telefono');
            $table->string('correo');
            $table->timestamps();
        });
        DB::table('colegios')->insert([
            [
                'departamento'=> 'Yacuiba-Tarija',
                'distrito' => 'Zona urbana',
                'dependencia' => 'Fiscal',
                'turno' => 'Tarde',
                'getion'=> 2024,
                'nivel' => 'Primaria comunitaria',
                'unidad_educativa' => 'U.E. Coronel Miguel Estenssoro',
                'logo' => 'images/logo.png',
                'direccion' => 'Calle Gral Campero E/ Av. Sta. Cruz y Ballivian',
                'telefono' => 78451269,
                'correo' => 'uecmestenssoro.@gmail.com'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colegios');
    }
};
