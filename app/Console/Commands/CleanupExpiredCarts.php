<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Console\Command;

/**
 * Commande pour nettoyer les paniers expirés et libérer le stock réservé
 * À exécuter via cron : * * * * * cd /path && php artisan carts:cleanup &>/dev/null
 */
class CleanupExpiredCarts extends Command
{
    protected $signature = 'carts:cleanup {--dry-run : Affiche seulement ce qui serait supprimé}';
    protected $description = 'Supprime les paniers expirés et libère le stock réservé';

    public function handle(CartService $cartService): int
    {
        $dryRun = $this->option('dry-run');
        
        $expiredCarts = Cart::where('expires_at', '<', now())
            ->withCount('items')
            ->get();

        $count = 0;
        $itemsReleased = 0;

        foreach ($expiredCarts as $cart) {
            if (!$dryRun) {
                // Libérer le stock réservé
                $cartService->releaseStockForCart($cart);
                
                // Supprimer les items puis le panier
                $cart->items()->delete();
                $cart->delete();
            }
            
            $count++;
            $itemsReleased += $cart->items_count;
        }

        if ($dryRun) {
            $this->info("[DRY-RUN] {$count} paniers expirés seraient supprimés ({$itemsReleased} items)");
        } else {
            $this->info("{$count} paniers expirés supprimés ({$itemsReleased} items, stock libéré)");
        }

        return self::SUCCESS;
    }
}
