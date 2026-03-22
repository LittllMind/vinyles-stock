<?php

namespace App\Observers;

use App\Models\MouvementStock;
use App\Models\Bougie;

class MouvementStockObserver
{
    /**
     * Handle the MouvementStock "creating" event.
     * Met à jour le stock de la bougie avant la création du mouvement.
     */
    public function creating(MouvementStock $mouvement): void
    {
        // Récupérer le produit concerné
        /** @var Bougie $produit */
        $produit = $mouvement->stockable;

        if ($mouvement->isEntree()) {
            $produit->quantite += $mouvement->quantite;
        } else {
            $produit->quantite -= $mouvement->quantite;
        }

        $produit->save();
    }

    /**
     * Handle the MouvementStock "deleting" event.
     * Annule le mouvement de stock en cas de suppression.
     */
    public function deleting(MouvementStock $mouvement): void
    {
        /** @var Bougie $produit */
        $produit = $mouvement->stockable;

        if ($mouvement->isEntree()) {
            $produit->quantite -= $mouvement->quantite;
        } else {
            $produit->quantite += $mouvement->quantite;
        }

        $produit->save();
    }
}
