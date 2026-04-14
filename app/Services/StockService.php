<?php

namespace App\Services;

use App\Models\Vinyle;
use App\Models\Fond;
use App\Models\MouvementStock;
use App\Models\Order;
use App\Models\StockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Vérifier si tout le panier est disponible en stock
     * 
     * @param array $items [vinyle_id => quantite, ...]
     * @return array ['available' => bool, 'errors' => []]
     */
    public function verifierDisponibilite(array $items): array
    {
        $errors = [];
        
        foreach ($items as $vinyleId => $quantiteDemandee) {
            $vinyle = Vinyle::find($vinyleId);
            
            if (!$vinyle) {
                $errors[] = "Vinyle #$vinyleId introuvable";
                continue;
            }
            
            if ($vinyle->quantite < $quantiteDemandee) {
                $dispo = $vinyle->quantite;
                $errors[] = "'{$vinyle->nom_complet}' - Stock insuffisant (demandé: $quantiteDemandee, disponible: $dispo)";
            }
        }
        
        return [
            'available' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Vérifier disponibilité d'un fond
     */
    public function verifierFondDisponible(string $typeFond): array
    {
        $fond = Fond::where('type', $typeFond)->first();
        
        if (!$fond) {
            return ['available' => false, 'error' => "Fond '$typeFond' non configuré"];
        }
        
        if ($fond->quantite <= 0) {
            return [
                'available' => false, 
                'error' => "Fond '$typeFond' en rupture de stock"
            ];
        }
        
        return ['available' => true, 'fond' => $fond];
    }
    
    /**
     * Réserver le stock pour une commande (après paiement)
     * Met à jour stock et crée mouvements en DB transaction
     */
    public function reserverStock(Order $order, int $userId): array
    {
        DB::beginTransaction();
        
        try {
            $mouvements = [];
            
            foreach ($order->items as $item) {
                $vinyle = $item->vinyle;
                $quantite = $item->quantite;
                
                if (!$vinyle) {
                    throw new \Exception("Vinyle introuvable pour item #{$item->id}");
                }
                
                // Stock disponible = quantité physique - réservée par d'autres
                $dispoReelle = $vinyle->quantite - $vinyle->reserved_quantity + $quantite; // +$quantite car c'est notre réservation
                if ($dispoReelle < $quantite) {
                    throw new \Exception("Stock insuffisant pour '{$vinyle->nom_complet}' (disponible: {$dispoReelle})");
                }
                
                // Libérer notre réservation ET décrémenter le stock physique
                // Ex: quantite=10, reserved=3 (dont 1 pour cette commande) → reserved devient 2, quantite devient 9
                $vinyle->decrement('reserved_quantity', $quantite);
                $vinyle->decrement('quantite', $quantite);
                
                // Créer mouvement sortie
                $mouvements[] = MouvementStock::enregistrer(
                    type: 'sortie',
                    produitType: 'vinyle',
                    produitId: $vinyle->id,
                    quantite: $quantite,
                    userId: $userId,
                    reference: "VENTE-{$order->id}",
                    notes: "Commande #{$order->id} - {$vinyle->nom_complet}"
                );
                
                // Gérer le fond si présent
                if ($item->fond_id) {
                    $fond = $item->fond;
                    if ($fond) {
                        $fondResult = $this->decrementerFondById($fond, $quantite, $order->id, $userId);
                        if ($fondResult) {
                            $mouvements[] = $fondResult;
                        }
                    }
                }
            }
            
            // Créer alerte si stock critique
            $this->verifierSeuilsAlerte($order);
            
            DB::commit();
            
            return [
                'success' => true,
                'mouvements' => $mouvements
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur réservation stock', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Restituer le stock (annulation/remboursement)
     * Réincrémente le stock physique (la réservation n'existe plus à ce stade)
     */
    public function restituerStock(Order $order, int $userId, string $raison = 'annulation'): array
    {
        DB::beginTransaction();
        
        try {
            $mouvements = [];
            
            foreach ($order->items as $item) {
                $vinyle = $item->vinyle;
                $quantite = $item->quantite;
                
                if (!$vinyle) continue;
                
                // Réincrémenter vinyle (le stock redevient disponible)
                $vinyle->increment('quantite', $quantite);
                
                // Créer mouvement entrée
                $mouvements[] = MouvementStock::enregistrer(
                    type: 'entree',
                    produitType: 'vinyle',
                    produitId: $vinyle->id,
                    quantite: $quantite,
                    userId: $userId,
                    reference: "RETOUR-{$order->id}",
                    notes: "Annulation commande #{$order->id} - {$raison}"
                );
                
                // Restituer fond
                if ($item->fond_id) {
                    $fond = $item->fond;
                    if ($fond) {
                        $this->restituerFondById($fond, $quantite, $order->id, $userId);
                    }
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'mouvements' => $mouvements
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur restitution stock', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Décrémenter un fond par ID
     */
    private function decrementerFondById(Fond $fond, int $quantite, int $orderId, int $userId): ?MouvementStock
    {
        // Stock dispo = physique - réservé (qui inclut notre réservation)
        $dispoReelle = $fond->quantite - $fond->reserved_quantity + $quantite;
        if ($dispoReelle < $quantite) {
            return null;
        }
        
        // Libérer notre réservation ET décrémenter stock
        $fond->decrement('reserved_quantity', $quantite);
        $fond->decrement('quantite', $quantite);
        
        return MouvementStock::enregistrer(
            type: 'sortie',
            produitType: $this->mapFondType($fond->type),
            produitId: $fond->id,
            quantite: $quantite,
            userId: $userId,
            reference: "VENTE-{$orderId}",
            notes: "Commande #{$orderId} - Fond {$fond->type}"
        );
    }
    
    /**
     * Restituer un fond par ID
     */
    private function restituerFondById(Fond $fond, int $quantite, int $orderId, int $userId): void
    {
        $fond->increment('quantite', $quantite);
        
        MouvementStock::enregistrer(
            type: 'entree',
            produitType: $this->mapFondType($fond->type),
            produitId: $fond->id,
            quantite: $quantite,
            userId: $userId,
            reference: "RETOUR-{$orderId}",
            notes: "Annulation - Fond {$fond->type}"
        );
    }
    
    /**
     * Mapper type fond vers produit_type
     */
    private function mapFondType(string $type): string
    {
        return match($type) {
            'miroir' => 'miroir',
            'dore' => 'dore',
            default => 'pochette'
        };
    }
    
    /**
     * Vérifier et créer alertes si seuils atteints
     */
    private function verifierSeuilsAlerte(Order $order): void
    {
        foreach ($order->items as $item) {
            $vinyle = $item->vinyle;
            if (!$vinyle) continue;
            
            $vinyle = $vinyle->fresh(); // Recharger depuis DB
            
            // Créer ou mettre à jour alerte
            if ($vinyle->quantite <= ($vinyle->seuil_alerte ?? 3)) {
                StockAlert::updateOrCreate(
                    [
                        'alertable_type' => Vinyle::class,
                        'alertable_id' => $vinyle->id,
                        'statut' => 'actif'
                    ],
                    [
                        'quantite_actuelle' => $vinyle->quantite,
                        'seuil_alerte' => $vinyle->seuil_alerte ?? 3,
                        'message' => $vinyle->quantite <= 0 
                            ? "Rupture de stock" 
                            : "Stock faible ({$vinyle->quantite} restants)"
                    ]
                );
            }
        }
    }
    
    /**
     * Obtenir historique des mouvements pour un produit
     */
    public function getHistorique(string $type, int $id, ?\DateTime $debut = null, ?\DateTime $fin = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = MouvementStock::parProduit($type, $id)
            ->with('user')
            ->orderBy('date_mouvement', 'desc');
        
        if ($debut && $fin) {
            $query->parPeriode($debut, $fin);
        }
        
        return $query->get();
    }
    
    /**
     * Obtenir état actuel du stock (valorisation)
     */
    public function getValorisationStock(): array
    {
        $vinyles = Vinyle::all();
        $fonds = Fond::all();
        
        return [
            'vinyles' => [
                'quantite' => $vinyles->sum('quantite'),
                'valeur_achat' => $vinyles->sum(fn($v) => $v->quantite * ($v->prix_achat ?? 0)),
                'valeur_vente' => $vinyles->sum(fn($v) => $v->quantite * $v->prix),
            ],
            'fonds' => [
                'miroir' => [
                    'quantite' => $fonds->where('type', 'miroir')->sum('quantite'),
                    'valeur' => $fonds->where('type', 'miroir')->sum(fn($f) => $f->quantite * $f->prix_vente),
                ],
                'dore' => [
                    'quantite' => $fonds->where('type', 'dore')->sum('quantite'),
                    'valeur' => $fonds->where('type', 'dore')->sum(fn($f) => $f->quantite * $f->prix_vente),
                ],
            ],
            'alertes_actives' => StockAlert::where('statut', 'actif')->count()
        ];
    }
}