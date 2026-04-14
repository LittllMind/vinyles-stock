<?php

namespace App\Observers;

use App\Models\Fond;
use App\Services\StockMovementService;

class FondObserver
{
    private array $oldQuantities = [];

    /**
     * Handle the Fond "retrieved" event.
     * Capture les valeurs avant modification
     */
    public function retrieved(Fond $fond): void
    {
        // On ne capture pas ici car retrieved est appelé à chaque chargement
    }

    /**
     * Handle the Fond "saving" event.
     * Capture les anciennes valeurs
     */
    public function saving(Fond $fond): void
    {
        // Désactivé - StockService gère tous les mouvements de vente
        // Cet observer ne trace plus les quantités pour éviter doublons
    }

    /**
     * Handle the Fond "saved" event.
     * 
     * NOTE: StockService gère UNIQUEMENT les mouvements de vente (sortie après paiement).
     * Les modifications manuelles admin sont désactivées ici pour éviter doublons.
     */
    public function saved(Fond $fond): void
    {
        // Désactivé - les mouvements de stock sont créés uniquement par StockService
        // lors des réservations (ventes) et restitutions (annulations)
    }
}
