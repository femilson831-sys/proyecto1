<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Verificar si la columna no existe antes de agregarla
            if (!Schema::hasColumn('reservas', 'horario_id')) {
                $table->foreignId('horario_id')->nullable()->after('cholet_id')->constrained('horarios')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('reservas', 'hora_reserva')) {
                $table->string('hora_reserva')->nullable()->after('fecha');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            if (Schema::hasColumn('reservas', 'horario_id')) {
                $table->dropForeign(['horario_id']);
                $table->dropColumn('horario_id');
            }
            
            if (Schema::hasColumn('reservas', 'hora_reserva')) {
                $table->dropColumn('hora_reserva');
            }
        });
    }
};