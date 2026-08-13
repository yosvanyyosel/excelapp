<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MasterAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Maestro',
            'username' => 'masterYosvany',
            'password' => Hash::make('master2026***'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Oniel Rojas',
            'username' => 'oniel',
            'password' => Hash::make('oniel2026*'),
            'role' => 'admin',
        ]);
    }
}
