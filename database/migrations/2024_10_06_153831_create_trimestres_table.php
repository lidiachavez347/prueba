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
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->string('periodo');
            $table->boolean('estado')->default(1);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->unsignedBigInteger('id_gestion');
            $table->foreign('id_gestion')->references('id')->on('gestiones')->onDelete('cascade');

            $table->timestamps();
            $table->unique(['periodo', 'id_gestion'], 'unique_periodo_gestion');
        });
        //use Illuminate\Support\Facades\DB;
        DB::table('trimestres')->insert([
            ['periodo' => 'PRIMER TRIMESTRE 2025',  'estado' => 1, 'fecha_inicio' => '2025-02-01', 'fecha_fin' => '2025-04-30', 'id_gestion' => 1,'created_at' => now(), 'updated_at' => now()],
            ['periodo' => 'SEGUNDO TRIMESTRE 2025', 'estado' => 1, 'fecha_inicio' => '2025-05-01', 'fecha_fin' => '2025-07-31', 'id_gestion' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['periodo' => 'TERCER TRIMESTRE 2025',  'estado' => 1, 'fecha_inicio' => '2025-08-01', 'fecha_fin' => '2025-10-31', 'id_gestion' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trimestres');
    }
};
