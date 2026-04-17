<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('pass'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'employe@test.com'],
            [
                'name' => 'Employé',
                'password' => Hash::make('pass'),
                'role' => 'employe',
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client',
                'password' => Hash::make('pass'),
                'role' => 'client',
            ]
        );
    }
}
