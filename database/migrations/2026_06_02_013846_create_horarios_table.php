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
            $table->unsignedBigInteger('cholet_id');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('capacidad_maxima')->default(20);
            $table->integer('reservas_actuales')->default(0);
            $table->boolean('disponible')->default(true);
            $table->timestamps();
            
            $table->foreign('cholet_id')->references('id')->on('cholets')->onDelete('cascade');
            $table->unique(['cholet_id', 'fecha', 'hora_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};