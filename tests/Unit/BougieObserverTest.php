<?php

namespace Tests\Unit;

use App\Models\Bougie;
use App\Models\StockAlert;
use App\Observers\BougieObserver;
use PHPUnit\Framework\TestCase;

class BougieObserverTest extends TestCase
{
    public function test_observer_classe_existe_et_a_methodes_requises()
    {
        $this->assertTrue(class_exists(BougieObserver::class));
        $this->assertTrue(method_exists(BougieObserver::class, 'created'));
        $this->assertTrue(method_exists(BougieObserver::class, 'updated'));
    }

    public function test_bougie_a_methode_is_en_alerte()
    {
        $this->assertTrue(method_exists(Bougie::class, 'isEnAlerte'));
    }

    public function test_is_en_alerte_retourne_vrai_si_stock_inferieur_seuil()
    {
        $bougie = new Bougie([
            'quantite' => 3,
            'seuil_alerte' => 5,
            'reference' => 'BOUG-TEST-001',
        ]);

        $this->assertTrue($bougie->isEnAlerte());
    }

    public function test_is_en_alerte_retourne_faux_si_stock_superieur_seuil()
    {
        $bougie = new Bougie([
            'quantite' => 10,
            'seuil_alerte' => 5,
            'reference' => 'BOUG-TEST-002',
        ]);

        $this->assertFalse($bougie->isEnAlerte());
    }

    public function test_is_en_alerte_retourne_faux_si_stock_egal_seuil()
    {
        $bougie = new Bougie([
            'quantite' => 5,
            'seuil_alerte' => 5,
            'reference' => 'BOUG-TEST-003',
        ]);

        $this->assertFalse($bougie->isEnAlerte());
    }

    public function test_bougie_a_relation_stock_alerts()
    {
        $this->assertTrue(method_exists(Bougie::class, 'stockAlerts'));
    }

    public function test_stock_alert_a_constantes_requises()
    {
        $this->assertTrue(defined(StockAlert::class . '::TYPE_STOCK_BAS'));
    }
}
