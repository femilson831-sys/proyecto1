<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cholet_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->min(1)->max(5);
            $table->timestamps();
            
            // Un usuario solo puede calificar una vez por cholet
            $table->unique(['user_id', 'cholet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};