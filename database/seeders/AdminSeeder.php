<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'jachatours936@gmail.com'],
            [
                'nombre' => 'EDSON FLORES',
                'telefono' => '69375465',
                'password' => Hash::make('Ci13732438'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}