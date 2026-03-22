<?php

namespace Tests\Unit;

use App\Models\Bougie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieTest extends TestCase
{
    use RefreshDatabase;

    public function test_bougie_peut_detecter_alerte_stock()
    {
        $bougie = Bougie::factory()->create([
            'quantite' => 3,
            'seuil_alerte' => 5,
        ]);

        $this->assertTrue($bougie->isEnAlerte());
    }

    public function test_bougie_ne_detecte_pas_alerte_si_stock_ok()
    {
        $bougie = Bougie::factory()->create([
            'quantite' => 10,
            'seuil_alerte' => 5,
        ]);

        $this->assertFalse($bougie->isEnAlerte());
    }
}
