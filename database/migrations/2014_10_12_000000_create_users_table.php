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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable(true);
            $table->string('nombres');
            $table->string('apellidos');
            $table->boolean('genero')->default(1);
            $table->text('direccion');
            $table->boolean('estado_user')->default(1);
            $table->string('ci', 20); // O más si necesitas más caracteres
            $table->integer('telefono')->unique();
            $table->date('fecha_nac');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->unsignedBigInteger('id_rol');
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');

            $table->string('qr_token')->unique();
            $table->string('qr_url')->nullable(true);
            $table->string('last_login_at')->nullable(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
