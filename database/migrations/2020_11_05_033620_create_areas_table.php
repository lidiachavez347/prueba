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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
        DB::table('areas')->insert([
            ['area' => 'VIDA TIERRA TERRITORIO','estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['area' => 'COMUNIDAD Y SOCIEDAD','estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['area' => 'CIENCIA TECNOLOGIA Y PRODUCCION','estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['area' => 'COSMOS Y PENSAMIENTOS','estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
