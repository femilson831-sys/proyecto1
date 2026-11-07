<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guia_cholet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guia_id')->constrained('guias')->onDelete('cascade');
            $table->foreignId('cholet_id')->constrained('cholets')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->date('fecha_asignacion')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['guia_id', 'cholet_id']);
            
            // Índices para búsquedas rápidas
            $table->index(['guia_id', 'activo']);
            $table->index(['cholet_id', 'activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guia_cholet');
    }
};
