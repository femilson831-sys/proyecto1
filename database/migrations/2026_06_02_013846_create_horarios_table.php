<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cholet_id')->constrained()->onDelete('cascade');
            $table->foreignId('guia_id')->nullable()->constrained('guias')->nullOnDelete(); // ← NUEVO CAMPO
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('capacidad_maxima')->default(20);
            $table->integer('reservas_actuales')->default(0);
            $table->boolean('disponible')->default(true);
            $table->text('notas_guia')->nullable(); // ← Instrucciones para el guía
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('fecha');
            $table->index('guia_id');
            $table->index(['fecha', 'cholet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};