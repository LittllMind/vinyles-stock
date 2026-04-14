<?php

namespace App\Observers;

use App\Models\Vinyle;
use App\Services\StockMovementService;

class VinyleObserver
{
    /**
     * Handle the Vinyle "created" event.
     */
    public function created(Vinyle $vinyle): void
    {
        // Traçage création avec stock initial
        if ($vinyle->quantite > 0) {
            StockMovementService::traceVinyleCreated($vinyle);
        }
    }

    /**
     * Handle the Vinyle "updated" event.
     * 
     * NOTE: Les mouvements liés aux ventes sont gérés UNIQUEMENT par StockService.
     * Cet observer ne trace QUE les modifications manuelles admin (création/suppression).
     * Le changement de stock via decrement() est géré par StockService pour éviter
     * les doublons de mouvements.
     */
    public function updated(Vinyle $vinyle): void
    {
        // Désactivé pour éviter doublons avec StockService::reserverStock()
        // Les mouvements de vente sont créés uniquement dans StockService
    }

    /**
     * Handle the Vinyle "deleted" event.
     * Soft delete - on trace comme sortie définitive
     */
    public function deleted(Vinyle $vinyle): void
    {
        if ($vinyle->quantite > 0) {
            // Bypass validation stock car le vinyle est déjà supprimé (soft delete)
            // et Vinyle::find() retournerait null
            \App\Models\MouvementStock::enregistrer(
                'sortie',
                'vinyle',
                $vinyle->id,
                $vinyle->quantite,
                \Illuminate\Support\Facades\Auth::id() ?? 1,
                $vinyle->reference ?? 'VIN-'.str_pad($vinyle->id, 4, '0', STR_PAD_LEFT),
                'Suppression vinyle : ' . $vinyle->nom
            );
        }
    }

    /**
     * Handle the Vinyle "restored" event.
     */
    public function restored(Vinyle $vinyle): void
    {
        if ($vinyle->quantite > 0) {
            StockMovementService::traceVinyleCreated($vinyle);
        }
    }

    /**
     * Handle the Vinyle "force deleted" event.
     */
    public function forceDeleted(Vinyle $vinyle): void
    {
        // Déjà traité par deleted si quantite > 0
    }
}
