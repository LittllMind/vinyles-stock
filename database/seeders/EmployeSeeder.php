<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'employe@example.com'],
            [
                'name' => 'Employé Test',
                'password' => bcrypt('password'),
                'role' => 'employe',
            ]
        );
    }
}
