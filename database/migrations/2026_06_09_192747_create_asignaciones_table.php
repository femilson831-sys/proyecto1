<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained()->onDelete('cascade');
            $table->foreignId('guia_id')->constrained()->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->string('estado')->default('activa');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->index(['guia_id', 'fecha_asignacion']);
            $table->unique('reserva_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('asignaciones');
    }
};