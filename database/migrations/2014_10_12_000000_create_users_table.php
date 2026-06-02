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

            // DATOS PERSONALES
            $table->string('nombre');
            $table->string('apellido');

            // EMAIL
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // CONTACTO
            $table->string('telefono')->nullable();

            // FOTO
            $table->string('foto')->nullable();

            // ROL
            $table->enum('rol', [
                'cliente',
                'admin',
                'guia'
            ])->default('cliente');

            // PASSWORD
            $table->string('password');

            // RECORDAR SESION
            $table->rememberToken();

            // FECHAS
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