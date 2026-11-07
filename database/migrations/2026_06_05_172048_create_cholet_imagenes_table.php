<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cholet_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cholet_id')->constrained('cholets')->onDelete('cascade');
            $table->string('imagen', 500);  // ✅ CORREGIDO: soporta rutas largas
            $table->integer('orden')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cholet_imagenes');
    }
};