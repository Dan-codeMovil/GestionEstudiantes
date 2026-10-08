<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'thiago',
            'email'    => 'thiago@correo.com',
            'password' => Hash::make('hola1'),
            'rol'      => 'estudiante',
        ]);    
    
    // Usuario Administrador
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@correo.com',
            'password' => Hash::make('hola'),
            'rol'      => 'admin',
        ]);

        // Usuario Estudiante
        User::create([
            'name'     => 'estudiante',
            'email'    => 'estudiante@correo.com',
            'password' => Hash::make('hola2'),
            'rol'      => 'estudiante',
        ]);
    }
}