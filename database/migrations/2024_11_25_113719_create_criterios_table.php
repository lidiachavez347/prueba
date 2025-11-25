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
        Schema::create('criterios', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->unique();

            $table->unsignedBigInteger('id_dimencion');
            $table->foreign('id_dimencion')->references('id')->on('dimencions')->onDelete('cascade');

            $table->timestamps();
        });
        
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterios');
    }
};
