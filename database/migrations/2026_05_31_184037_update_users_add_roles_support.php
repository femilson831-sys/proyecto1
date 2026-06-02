<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Solo reforzamos el ENUM por seguridad
            $table->enum('rol', ['cliente', 'admin', 'guia'])
                ->default('cliente')
                ->change();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['cliente', 'admin'])
                ->default('cliente')
                ->change();
        });
    }
};
