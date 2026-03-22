<?php

namespace Tests\Feature;

use App\Models\Bougie;
use App\Models\StockAlert;
use App\Observers\BougieObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieStockAlertObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_classe_existe_et_a_methodes_requises()
    {
        $this->assertTrue(class_exists(BougieObserver::class));
        $this->assertTrue(method_exists(BougieObserver::class, 'created'));
        $this->assertTrue(method_exists(BougieObserver::class, 'updated'));
    }

    public function test_cree_alerte_quand_stock_inferieur_seuil()
    {
        // Créer une bougie avec stock bas (déclenche l'observer via created)
        $bougie = Bougie::create([
            'reference' => 'BOUG-TEST-001',
            'parfum' => 'Vanille',
            'nom' => 'Bougie Test',
            'prix' => 25.00,
            'quantite' => 3,
            'seuil_alerte' => 5,
        ]);

        // Vérifier qu'une alerte a été créée
        $this->assertDatabaseHas('stock_alerts', [
            'alertable_type' => Bougie::class,
            'alertable_id' => $bougie->id,
            'type' => StockAlert::TYPE_STOCK_BAS,
            'resolu' => false,
        ]);

        $alerte = $bougie->stockAlerts()->first();
        $this->assertNotNull($alerte);
        $this->assertEquals(StockAlert::TYPE_STOCK_BAS, $alerte->type);
        $this->assertEquals("Stock bas pour {$bougie->reference}", $alerte->message);
        $this->assertFalse($alerte->resolu);
    }

    public function test_ne_cree_pas_alerte_si_quantite_superieure_seuil()
    {
        // Créer une bougie avec stock suffisant
        $bougie = Bougie::create([
            'reference' => 'BOUG-TEST-002',
            'parfum' => 'Lavande',
            'nom' => 'Bougie Test 2',
            'prix' => 30.00,
            'quantite' => 10,
            'seuil_alerte' => 5,
        ]);

        // Vérifier qu'aucune alerte n'a été créée
        $this->assertDatabaseMissing('stock_alerts', [
            'alertable_type' => Bougie::class,
            'alertable_id' => $bougie->id,
        ]);

        $this->assertEquals(0, $bougie->stockAlerts()->count());
    }

    public function test_ne_cree_pas_alerte_si_existe_deja_non_resolue()
    {
        // Créer une bougie avec stock bas
        $bougie = Bougie::create([
            'reference' => 'BOUG-TEST-003',
            'parfum' => 'Rose',
            'nom' => 'Bougie Test 3',
            'prix' => 20.00,
            'quantite' => 2,
            'seuil_alerte' => 5,
        ]);

        // Vérifier qu'une seule alerte existe
        $this->assertEquals(1, $bougie->stockAlerts()->count());

        // Simuler une mise à jour (qui déclencherait updated)
        $bougie->update(['quantite' => 3]);

        // Vérifier qu'il n'y a toujours qu'une seule alerte
        $this->assertEquals(1, $bougie->stockAlerts()->count());
    }

    public function test_cree_nouvelle_alerte_si_precedente_resolue()
    {
        // Créer une bougie avec stock bas
        $bougie = Bougie::create([
            'reference' => 'BOUG-TEST-004',
            'parfum' => 'Jasmin',
            'nom' => 'Bougie Test 4',
            'prix' => 35.00,
            'quantite' => 2,
            'seuil_alerte' => 5,
        ]);

        // Vérifier qu'une alerte existe
        $this->assertEquals(1, $bougie->stockAlerts()->count());

        // Résoudre l'alerte
        $alerte = $bougie->stockAlerts()->first();
        $alerte->resoudre();

        // Simuler une mise à jour qui remet le stock bas
        $bougie->update(['quantite' => 1]);

        // Vérifier qu'une nouvelle alerte a été créée (2 au total)
        $this->assertEquals(2, $bougie->stockAlerts()->count());

        // Vérifier qu'une alerte non résolue existe
        $this->assertEquals(1, $bougie->stockAlerts()->where('resolu', false)->count());
    }
}
