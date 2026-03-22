<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bougie;

class BougieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bougie::factory()->count(10)->create();
    }
}
