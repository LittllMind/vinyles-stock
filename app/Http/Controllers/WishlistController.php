<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Vinyle;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Affiche la liste des favoris de l'utilisateur
     */
    public function index(): View
    {
        $wishlist = Wishlist::getUserWishlist(Auth::id());
        
        return view('wishlist.index', [
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Ajoute un vinyle aux favoris
     */
    public function add(Request $request): RedirectResponse
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'vinyle_id' => ['required', 'exists:vinyles,id'],
        ]);

        $result = Wishlist::addToWishlist(Auth::id(), $validated['vinyle_id']);

        if ($result === false) {
            return back()->with('info', 'Ce vinyle est déjà dans vos favoris.');
        }

        return back()->with('success', 'Vinyle ajouté à vos favoris.');
    }

    /**
     * Retire un vinyle des favoris
     */
    public function remove(Vinyle $vinyle): RedirectResponse
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifier que l'item appartient à l'utilisateur
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('vinyle_id', $vinyle->id)
            ->first();

        if (!$wishlistItem) {
            abort(403, 'Ce favori ne vous appartient pas.');
        }

        Wishlist::removeFromWishlist(Auth::id(), $vinyle->id);

        return back()->with('success', 'Vinyle retiré de vos favoris.');
    }

    /**
     * Déplace un vinyle des favoris vers le panier
     */
    public function toCart(Vinyle $vinyle, CartService $cartService): RedirectResponse
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifier que l'item appartient à l'utilisateur
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('vinyle_id', $vinyle->id)
            ->first();

        if (!$wishlistItem) {
            abort(403, 'Ce favori ne vous appartient pas.');
        }

        // Vérifier que le vinyle est en stock
        if ($vinyle->quantite <= 0) {
            return back()->with('error', 'Ce vinyle est en rupture de stock.');
        }

        // Ajouter au panier via le CartService (fond_type = 'standard' par défaut)
        $cartService->addVinyle($vinyle->id, 1, 'standard');

        // Supprimer des favoris
        Wishlist::removeFromWishlist(Auth::id(), $vinyle->id);

        return redirect()->route('cart.index')
            ->with('success', 'Vinyle ajouté à votre panier.');
    }
}
