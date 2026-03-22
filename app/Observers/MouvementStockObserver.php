<?php

namespace App\Observers;

use App\Models\MouvementStock;
use App\Models\Bougie;

class MouvementStockObserver
{
    /**
     * Handle the MouvementStock "created" event.
     */
    public function created(MouvementStock $mouvement): void
    {
        // Vérifier que c'est une Bougie (polymorphique)
        if ($mouvement->stockable_type !== Bougie::class) {
            return;
        }

        $bougie = Bougie::find($mouvement->stockable_id);
        if (!$bougie) {
            return;
        }

        if ($mouvement->isEntree()) {
            $bougie->quantite += $mouvement->quantite;
        } elseif ($mouvement->isSortie()) {
            $bougie->quantite -= $mouvement->quantite;
            if ($bougie->quantite < 0) {
                $bougie->quantite = 0; // Sécurité stock négatif
            }
        }

        $bougie->save();
    }
}