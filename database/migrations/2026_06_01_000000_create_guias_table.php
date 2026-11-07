<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('telefono')->nullable();
            $table->string('foto')->nullable();
            $table->text('biografia')->nullable();
            $table->json('idiomas')->nullable(); // ["español", "ingles", "portugues"]
            $table->integer('anos_experiencia')->default(0);
            $table->string('especialidad')->nullable(); // cultural, aventura, gastronomico
            $table->enum('disponibilidad', ['disponible', 'ocupado', 'descanso'])->default('disponible');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            
            // Índices
            $table->index('email');
            $table->index('disponibilidad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guias');
    }
};