<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Blue, Gold, Platinum
            $table->string('slug')->unique(); // blue, gold, platinum
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2); // Precio en USD
            $table->decimal('precio_bs', 10, 2)->nullable(); // Precio en Bolivianos
            $table->string('icono')->nullable(); // Icono FontAwesome
            $table->string('color')->nullable(); // Color representativo
            $table->integer('duracion_horas')->default(3); // Duración en horas
            $table->text('incluye')->nullable(); // Qué incluye la experiencia
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiencias');
    }
};