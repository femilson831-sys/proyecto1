<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Este migration ya está incluido en el create_users_table
        // Si ya tienes la tabla users, puedes agregar columnas faltantes
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nombre')) {
                $table->string('nombre')->after('id');
            }
            if (!Schema::hasColumn('users', 'apellido')) {
                $table->string('apellido')->after('nombre');
            }
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['cliente', 'admin', 'guia'])->default('cliente')->after('foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellido', 'telefono', 'foto', 'rol']);
        });
    }
};