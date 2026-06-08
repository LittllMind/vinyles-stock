<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Thème ART PRINT par défaut. Vinyl-Cult uniquement si explicitement demandé.
     */
    public function handle(Request $request, Closure $next)
    {
        // Thème unique : ART PRINT. Pas de switcher.
        $theme = 'art_print';
        
        // Rendre le thème disponible dans toutes les vues
        View::share('currentTheme', $theme);
        
        // Ajouter le thème aux données de la requête pour les contrôleurs
        $request->attributes->add(['theme' => $theme]);
        
        return $next($request);
    }
}
