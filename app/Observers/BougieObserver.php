<?php

namespace App\Observers;

use App\Models\Bougie;
use App\Models\StockAlert;

class BougieObserver
{
    /**
     * Handle the Bougie "created" event.
     */
    public function created(Bougie $bougie): void
    {
        $this->checkStockAlert($bougie);
    }

    /**
     * Handle the Bougie "updated" event.
     */
    public function updated(Bougie $bougie): void
    {
        if ($bougie->wasChanged('quantite')) {
            $this->checkStockAlert($bougie);
        }
    }

    /**
     * Vérifie si une alerte stock doit être créée.
     */
    private function checkStockAlert(Bougie $bougie): void
    {
        if ($bougie->isEnAlerte()) {
            // Vérifier s'il existe déjà une alerte non résolue via la relation
            $existeAlerte = $bougie->stockAlerts()
                ->where('type', StockAlert::TYPE_STOCK_BAS)
                ->where('resolu', false)
                ->first();

            if (!$existeAlerte) {
                $bougie->stockAlerts()->create([
                    'type' => StockAlert::TYPE_STOCK_BAS,
                    'message' => "Stock bas pour {$bougie->reference}",
                    'resolu' => false,
                ]);
            }
        }
    }
}
