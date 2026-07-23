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
            'username' => 'master',
            'password' => Hash::make('master123'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Admin Centro 1',
            'username' => 'admin1',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
