<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_bougies_table_exists()
    {
        $this->assertTrue(\Schema::hasTable('bougies'));
    }

    public function test_bougies_table_has_required_columns()
    {
        $columns = ['id', 'reference', 'parfum', 'nom', 'collection', 'format', 
                   'type_cire', 'temps_brulure', 'notes', 'prix', 'quantite', 
                   'seuil_alerte', 'created_at', 'updated_at'];
        
        foreach ($columns as $column) {
            $this->assertTrue(\Schema::hasColumn('bougies', $column), 
                "Colonne manquante: {$column}");
        }
    }

    public function test_bougie_model_is_functional()
    {
        $bougie = \App\Models\Bougie::factory()->create([
            'reference' => 'TEST-001',
            'nom' => 'Bougie Test'
        ]);

        $this->assertDatabaseHas('bougies', [
            'reference' => 'TEST-001',
            'nom' => 'Bougie Test'
        ]);
    }

    public function test_bougie_factory_works()
    {
        $bougie = \App\Models\Bougie::factory()->create();

        $this->assertNotNull($bougie->id);
        $this->assertNotNull($bougie->reference);
        $this->assertNotNull($bougie->parfum);
    }

    public function test_bougie_reference_is_unique()
    {
        \App\Models\Bougie::factory()->create(['reference' => 'UNIQUE-REF']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        \App\Models\Bougie::factory()->create(['reference' => 'UNIQUE-REF']);
    }

    public function test_bougie_default_values_are_correct()
    {
        $bougie = \App\Models\Bougie::factory()->create([
            'quantite' => null,
            'seuil_alerte' => null,
        ]);

        $this->assertEquals(0, $bougie->quantite);
        $this->assertEquals(5, $bougie->seuil_alerte);
    }
}
