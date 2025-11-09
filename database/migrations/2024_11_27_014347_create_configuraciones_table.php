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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('logo');
            $table->string('direccion');
            $table->integer('telefono');
            $table->string('email')->unique();
            $table->boolean('estado')->default(1);

            $table->timestamps();
        });
        DB::table('configuraciones')->insert([
            ['nombre' => 'U.E. CNEL. MIGUEL ESTENSSORO', 'logo' => 'logo.png', 'direccion' => 'CALLE CAMERO ESQUINA SANTA CRUZ, YACUIBA, BOLIVIA', 'telefono' => '76829247', 'email' => 'ueestenssoro@ueestenssoro.com', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
