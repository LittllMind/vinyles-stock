<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Vinyle;
use App\Models\Fond;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Récupère (ou crée) le panier de l'utilisateur courant
     */
    public function getCart(): Cart
    {
        // Ensure the session has an ID. In some test scenarios the session has not
        // yet been started which can lead to creating anonymous carts without a
        // session_id (and later requests using a different session create a
        // second anonymous cart). Start the session when missing to keep the
        // anonymous cart bound to the real session id.
        $sessionId = session()->getId();
        if (empty($sessionId)) {
            session()->start();
            $sessionId = session()->getId();
        }

        \Illuminate\Support\Facades\Log::info('CartService.getCart', [
            'session_id' => $sessionId,
            'user_id' => Auth::check() ? Auth::id() : null,
            'auth' => Auth::check(),
        ]);

        if (Auth::check()) {
            // 1 seul panier par user_id (index unique unique_user_cart)
            $cart = Cart::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                ],
                [
                    'session_id' => $sessionId,          // optionnel
                    'expires_at' => now()->addHours(2),
                ]
            );
        } else {
            // 1 seul panier par session_id invité
            $cart = Cart::firstOrCreate(
                [
                    'session_id' => $sessionId,
                ],
                [
                    'expires_at' => now()->addHours(2),
                ]
            );

            // Also set a cookie with the anonymous cart id so it can be referenced directly on login
            try {
                if ($cart && $cart->id) {
                    \Illuminate\Support\Facades\Cookie::queue('anon_cart_id', $cart->id, 0);
                }
            } catch (\Throwable $e) {
                // Non-critical: cookie queue may fail in console context
                \Illuminate\Support\Facades\Log::warning('Could not queue anon_cart_id cookie', ['error' => $e->getMessage()]);
            }
        }

        // Pour les anciens paniers sans expires_at
        if (is_null($cart->expires_at)) {
            $cart->expires_at = now()->addHours(2);
            $cart->save();
        }

        return $cart;
    }


    /**
     * Ajouter un vinyle au panier
     * avec réservation automatique du stock
     */
    public function addVinyle(int $vinyleId, int $quantite = 1, string $fondType = 'standard'): CartItem
    {
        $vinyle = Vinyle::lockForUpdate()->findOrFail($vinyleId);

        if ($quantite <= 0) {
            throw new \Exception("La quantité doit être supérieure à 0");
        }

        // --- Suppléments en centimes (cohérent avec DB) ---
        $fondSupplements = [
            'standard' => 0,     // €0
            'miroir'   => 800,   // €8
            'dore'     => 1300,  // €13
        ];

        // --- Vérif/chargement du fond (miroir/doré) ---
        $fondModel = null;
        if (in_array($fondType, ['miroir', 'dore'])) {
            $fondModel = Fond::lockForUpdate()->where('type', $fondType)->first();

            if (!$fondModel) {
                throw new \Exception("Fond {$fondType} introuvable");
            }
            
            // Stock disponible = quantité - réservée
            $fondDispo = $fondModel->quantite - $fondModel->reserved_quantity;
            if ($fondDispo < $quantite) {
                throw new \Exception("Stock insuffisant de fonds {$fondType} (disponible : {$fondDispo})");
            }
        }

        // Stock vinyle disponible = quantité - réservée
        $vinyleDispo = $vinyle->quantite - $vinyle->reserved_quantity;
        if ($vinyleDispo < $quantite) {
            throw new \Exception("Stock insuffisant pour {$vinyle->nom} (disponible : {$vinyleDispo})");
        }

        $fondId = $fondModel?->id;

        // --- Prix unitaire ---
        $supplement   = $fondSupplements[$fondType] ?? 0;
        $prixUnitaire = $vinyle->prix + $supplement;

        $cart = $this->getCart();

        \Illuminate\Support\Facades\Log::info('CartService.addVinyle called', [
            'vinyle_id' => $vinyleId,
            'quantite' => $quantite,
            'fond_type' => $fondType,
            'cart_id' => $cart->id ?? null,
            'cart_user_id' => $cart->user_id ?? null,
            'session_id' => session()->getId(),
        ]);

        return DB::transaction(function () use ($cart, $vinyle, $fondModel, $fondId, $vinyleId, $quantite, $prixUnitaire, $fondType) {
            // --- RESERVATION: Incrémenter reserved_quantity ---
            // On réserve physiquement le stock quand on ajoute au panier
            $vinyle->increment('reserved_quantity', $quantite);
            
            if ($fondModel) {
                $fondModel->increment('reserved_quantity', $quantite);
            }

            // --- Chercher si même vinyle + même fond existent déjà dans le panier ---
            $cartItem = $cart->items()
                ->where('vinyle_id', $vinyleId)
                ->where('fond_id', $fondId)
                ->first();

            if ($cartItem) {
                $nouvelleQuantite = $cartItem->quantite + $quantite;
                $cartItem->update([
                    'quantite'      => $nouvelleQuantite,
                    'prix_unitaire' => $prixUnitaire,
                ]);
            } else {
                $cartItem = $cart->items()->create([
                    'vinyle_id'     => $vinyleId,
                    'fond_id'       => $fondId,
                    'quantite'      => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                ]);
            }

            return $cartItem->load(['vinyle', 'fond']);
        });
    }

    /**
     * Met à jour la quantité d'un item du panier
     * Libère/réserve la différence de stock
     */
    public function updateQuantite(int $itemId, int $quantite): void
    {
        if ($quantite <= 0) {
            throw new \Exception("La quantité doit être supérieure à 0");
        }

        $cart = $this->getCart();

        /** @var CartItem|null $item */
        $item = $cart->items()
            ->with(['vinyle', 'fond'])
            ->whereKey($itemId)
            ->first();

        if (!$item) {
            throw new \Exception("Article introuvable dans le panier.");
        }

        $vinyle = $item->vinyle;
        if (!$vinyle) {
            throw new \Exception("Vinyle introuvable pour cet article.");
        }

        $oldQuantite = $item->quantite;
        $delta = $quantite - $oldQuantite; // différence à réserver (+) ou libérer (-)

        // Vérif stock disponible si on augmente
        if ($delta > 0) {
            $dispo = $vinyle->quantite - $vinyle->reserved_quantity;
            if ($dispo < $delta) {
                throw new \Exception("Stock insuffisant pour {$vinyle->nom} (disponible : {$dispo}).");
            }
            if ($item->fond) {
                $fondDispo = $item->fond->quantite - $item->fond->reserved_quantity;
                if ($fondDispo < $delta) {
                    throw new \Exception("Stock insuffisant de fonds {$item->fond->type} (disponible : {$fondDispo}).");
                }
            }
        }

        // Mise à jour avec transaction pour stock
        DB::transaction(function () use ($item, $vinyle, $quantite, $delta) {
            // Ajuster reserved_quantity
            $vinyle->increment('reserved_quantity', $delta);
            if ($item->fond) {
                $item->fond->increment('reserved_quantity', $delta);
            }

            $item->update(['quantite' => $quantite]);
        });
    }

    /**
     * Supprimer un item du panier
     * Libère le stock réservé
     */
    public function removeItem(int $itemId): void
    {
        $cart = $this->getCart();

        /** @var CartItem|null $item */
        $item = $cart->items()
            ->with(['vinyle', 'fond'])
            ->whereKey($itemId)
            ->first();

        if (!$item) {
            return; // Déjà supprimé
        }

        // Libérer le stock réservé
        DB::transaction(function () use ($item) {
            if ($item->vinyle) {
                $item->vinyle->decrement('reserved_quantity', $item->quantite);
            }
            if ($item->fond) {
                $item->fond->decrement('reserved_quantity', $item->quantite);
            }
            $item->delete();
        });
    }

    /**
     * Libère le stock réservé pour tous les items d'un panier
     * Appelé après paiement confirmé (le stock passe de réservé à vendu)
     * ou si le panier expire
     */
    public function releaseStockForCart(Cart $cart): void
    {
        $items = $cart->items()->with(['vinyle', 'fond'])->get();

        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                if ($item->vinyle) {
                    $item->vinyle->decrement('reserved_quantity', $item->quantite);
                }
                if ($item->fond) {
                    $item->fond->decrement('reserved_quantity', $item->quantite);
                }
            }
        });
    }

    /**
     * Vider le panier
     * Libère tout le stock réservé
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        
        // Libérer d'abord le stock
        $this->releaseStockForCart($cart);
        
        // Puis vider les items
        $cart->items()->delete();
    }

    /**
     * Nombre total d'articles dans le panier
     */
    public function count(): int
    {
        return $this->getCart()
            ->items()
            ->sum('quantite');
    }

    /**
     * Vérifie le stock des vinyles/fonds pour tous les items du panier
     * Retourne un tableau de messages d'erreur (à afficher dans la vue)
     */
    public function checkStock(): array
    {
        $cart = $this->getCart();

        $errors = [];

        $items = $cart->items()->with(['vinyle', 'fond'])->get();

        foreach ($items as $item) {
            $vinyle = $item->vinyle;

            if ($vinyle && $vinyle->quantite < $item->quantite) {
                $errors[] = "Stock insuffisant pour {$vinyle->nom} (demandé : {$item->quantite}, disponible : {$vinyle->quantite}).";
            }

            if ($item->fond && $item->fond->quantite < $item->quantite) {
                $errors[] = "Stock insuffisant pour le fond {$item->fond->type} sur {$vinyle->nom} (demandé : {$item->quantite}, disponible : {$item->fond->quantite}).";
            }
        }

        return $errors;
    }

    /**
     * Merge the anonymous (session) cart into the authenticated user's cart after login.
     */
    /**
     * Merge the anonymous (session) cart into the authenticated user's cart after login.
     *
     * @param string|null $sourceSessionId Optional previous session id where the anonymous cart is stored
     */
    /**
     * Merge the anonymous (session) cart into the authenticated user's cart after login.
     *
     * @param string|null $sourceSessionId Optional previous session id where the anonymous cart is stored
     * @param int|null $anonCartId Optional anonymous cart id (preferred when present)
     * @return bool True if a merge occurred, false otherwise
     */
    public function mergeAnonymousCart(?string $sourceSessionId = null, ?int $anonCartId = null): bool
    {
        if (!Auth::check()) {
            return false;
        }

        // Prefer explicit anonymous cart id (set via cookie) because session ids can be unreliable during login
        $anonCart = null;

        if (!is_null($anonCartId)) {
            $anonCart = Cart::where('id', $anonCartId)->whereNull('user_id')->first();
            if ($anonCart) {
                \Illuminate\Support\Facades\Log::info('mergeAnonymousCart: found anon cart by id', ['anon_cart_id' => $anonCartId, 'items' => $anonCart->items()->count()]);
            } else {
                \Illuminate\Support\Facades\Log::info('mergeAnonymousCart: no anon cart found by id', ['anon_cart_id' => $anonCartId]);
            }
        }

        // Fallback to previous session id if no cart id was provided/found
        if (is_null($anonCart)) {
            $sourceSessionId = $sourceSessionId ?? session()->getId();
            $anonCart = Cart::where('session_id', $sourceSessionId)->whereNull('user_id')->first();

            if (!$anonCart) {
                \Illuminate\Support\Facades\Log::info('mergeAnonymousCart: no anon cart found by session', ['source_session' => $sourceSessionId]);
                return false;
            }

            \Illuminate\Support\Facades\Log::info('mergeAnonymousCart: found anon cart by session', ['source_session' => $sourceSessionId, 'anon_cart_id' => $anonCart->id, 'items' => $anonCart->items()->count()]);
        }

        // Ensure user cart exists (use current session id)
        $currentSession = session()->getId();
        $userCart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => $currentSession, 'expires_at' => now()->addHours(2)]
        );

        DB::transaction(function () use ($anonCart, $userCart) {
            $items = $anonCart->items()->with(['vinyle', 'fond'])->get();

            foreach ($items as $item) {
                \Illuminate\Support\Facades\Log::info('mergeAnonymousCart: processing item', ['vinyle_id' => $item->vinyle_id, 'fond_id' => $item->fond_id, 'quantite' => $item->quantite]);
                $vinyle = $item->vinyle;
                $fond = $item->fond;

                // Determine how many we can safely add
                $availableVinyle = $vinyle?->quantite ?? 0;
                $availableFond = $fond?->quantite ?? null; // null => no fond constraint

                // Find existing item in user cart (same vinyle + fond)
                $existing = $userCart->items()
                    ->where('vinyle_id', $item->vinyle_id)
                    ->where('fond_id', $item->fond_id)
                    ->first();

                if ($existing) {
                    $desired = $existing->quantite + $item->quantite;
                    $capped = min($desired, $availableVinyle);
                    if (!is_null($availableFond)) {
                        $capped = min($capped, $availableFond);
                    }

                    $existing->update(['quantite' => $capped]);
                } else {
                    $addQty = min($item->quantite, $availableVinyle);
                    if (!is_null($availableFond)) {
                        $addQty = min($addQty, $availableFond);
                    }

                    if ($addQty <= 0) {
                        continue; // nothing to add
                    }

                    $userCart->items()->create([
                        'vinyle_id' => $item->vinyle_id,
                        'fond_id' => $item->fond_id,
                        'quantite' => $addQty,
                        'prix_unitaire' => $item->prix_unitaire,
                    ]);
                }
            }

            // Clean up anonymous cart
            $anonCart->items()->delete();
            $anonCart->delete();

            // Refresh user cart expiry
            $userCart->expires_at = now()->addHours(2);
            $userCart->save();
        });

        return true;
    }
}

