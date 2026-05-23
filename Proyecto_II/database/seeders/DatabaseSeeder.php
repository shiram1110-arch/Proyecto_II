<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR ROLES PRIMERO
        DB::table('roles')->insert([
            ['idRol' => 1, 'nombre' => 'Admin'],
            ['idRol' => 2, 'nombre' => 'Usuario']
        ]);

        // 2. ADMIN
        Usuario::create([
            'nombre' => 'Admin',
            'apellidoUno' => 'Sistema',
            'apellidoDos' => null,
            'email' => 'admin@correo.com',
            'telefono' => '88888888',
            'userName' => 'admin',
            'password' => Hash::make('admin123'),
            'idRol' => 1
        ]);

        // 3. USUARIO NORMAL
        Usuario::create([
            'nombre' => 'Juan',
            'apellidoUno' => 'Pérez',
            'apellidoDos' => 'Gómez',
            'email' => 'juan@gmail.com',
            'telefono' => '77777777',
            'userName' => 'juanp',
            'password' => Hash::make('123456'),
            'idRol' => 2
        ]);
    }
}