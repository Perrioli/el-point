<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username'        => 'admin',
            'nombre_completo' => 'Tomas Quispe',
            'email'           => 'admin@elpoint.com',
            'password'        => Hash::make('12345678'),
            'rol'             => 'Administrador',
        ]);
    }
}
