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
        Schema::create('qr_logins', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();

            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('ip_address')->nullable();   // 👈 AQUÍ
            $table->string('user_agent')->nullable();    // 👈 AQUÍ
            
            $table->boolean('confirmed')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_logins');
    }
};
