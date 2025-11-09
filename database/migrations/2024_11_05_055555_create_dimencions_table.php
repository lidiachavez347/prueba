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
        Schema::create('dimencions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->integer('ponderacion')->check('ponderacion >= 0 AND ponderacion <= 100');

            $table->timestamps();
        });
        DB::table('dimencions')->insert([
            ['nombre' => 'SER',     'ponderacion' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'SABER',   'ponderacion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'HACER',   'ponderacion' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'DECIDIR', 'ponderacion' => 15, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimencions');
    }
};
