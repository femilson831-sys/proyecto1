<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cholet_id')->constrained()->onDelete('cascade');
            $table->foreignId('guia_id')->nullable()->constrained('guias')->nullOnDelete(); // ← AGREGAR ESTA LÍNEA
            $table->foreignId('experiencia_id')->constrained()->onDelete('cascade');
            $table->foreignId('horario_id')->nullable()->constrained()->onDelete('set null');
            $table->date('fecha')->index();
            $table->string('hora_reserva', 10)->nullable();
            $table->string('experiencia', 100)->nullable();
            $table->integer('personas');
            $table->string('telefono', 20);
            $table->decimal('precio_unitario', 8, 2);
            $table->decimal('precio_total', 8, 2);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente')->index();
            $table->string('codigo_reserva')->unique();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};