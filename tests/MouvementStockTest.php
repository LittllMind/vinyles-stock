<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bougie;
use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MouvementStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\BougieSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_migration_mouvements_stock_existe()
    {
        $this->assertTrue(\Schema::hasTable('mouvements_stock'));
    }

    public function test_mouvements_stock_a_les_bonnes_colonnes()
    {
        $this->assertTrue(\Schema::hasColumns('mouvements_stock', [
            'id',
            'stockable_type',
            'stockable_id',
            'type',
            'quantite',
            'raison',
            'user_id',
            'created_at',
            'updated_at'
        ]));
    }

    public function test_modele_mouvement_stock_fonctionne()
    {
        $bougie = Bougie::first();
        
        $mouvement = MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 10,
            'raison' => 'Réception fournisseur',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('mouvements_stock', [
            'id' => $mouvement->id,
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 10,
        ]);
    }

    public function test_mouvement_entree_augmente_stock_bougie()
    {
        $bougie = Bougie::first();
        $stockInitial = $bougie->quantite;

        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 5,
            'raison' => 'Réception stock',
            'user_id' => $this->user->id,
        ]);

        $bougie->refresh();
        $this->assertEquals($stockInitial + 5, $bougie->quantite);
    }

    public function test_mouvement_sortie_diminue_stock_bougie()
    {
        $bougie = Bougie::first();
        $bougie->update(['quantite' => 20]);

        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'sortie',
            'quantite' => 3,
            'raison' => 'Vente',
            'user_id' => $this->user->id,
        ]);

        $bougie->refresh();
        $this->assertEquals(17, $bougie->quantite);
    }

    public function test_relation_morphTo_stockable()
    {
        $bougie = Bougie::first();

        $mouvement = MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 5,
            'raison' => 'Test',
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(Bougie::class, $mouvement->stockable);
        $this->assertEquals($bougie->id, $mouvement->stockable->id);
    }

    public function test_peut_recuperer_historique_mouvements_par_bougie()
    {
        $bougie = Bougie::first();

        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 10,
            'raison' => 'Achat',
            'user_id' => $this->user->id,
        ]);

        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'sortie',
            'quantite' => 2,
            'raison' => 'Vente',
            'user_id' => $this->user->id,
        ]);

        $historique = MouvementStock::where('stockable_type', Bougie::class)
            ->where('stockable_id', $bougie->id)
            ->get();

        $this->assertCount(2, $historique);
    }
}