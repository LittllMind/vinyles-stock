<?php

namespace Database\Seeders;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Vinyle;
use App\Models\Fond;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VenteSeeder extends Seeder
{
    /**
     * Pas de ventes historiques pour l'instant - données propres
     * Les ventes se feront via le système de commandes en production
     */
    public function run(): void
    {
        // Ne rien créer - les ventes historiques ne sont pas pertinentes
        // Le système de stats utilisera les orders/payments à la place
        
        // Optionnel : créer quelques mouvements de stock pour les fonds
        $fonds = Fond::all();
        
        foreach ($fonds as $fond) {
            // S'assurer que les fonds ont un stock initial
            if ($fond->quantite === 0) {
                $fond->quantite = match($fond->type) {
                    'standard' => 999,
                    'miroir' => 50,
                    'dore' => 50,
                    default => 20,
                };
                $fond->save();
            }
        }
    }
}
