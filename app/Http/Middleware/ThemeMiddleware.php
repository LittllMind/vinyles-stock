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
        // Récupère le thème depuis l'URL (?theme=...) ou la session
        $theme = $request->query('theme');
        
        // Si URL contient ?theme=..., le stocker en session (normalisation _)
        if ($theme) {
            $theme = str_replace('-', '_', $theme);
            session(['theme' => $theme]);
        } else {
            // Sinon récupérer depuis session, avec art_print comme défaut
            $theme = session('theme', 'art_print');
        }
        
        // Normaliser les vieilles valeurs art-print vers art_print
        if ($theme === 'art-print') {
            $theme = 'art_print';
        }
        
        // Vérifier que c'est un thème valide
        $allowedThemes = ['vinyl_cult', 'art_print'];
        if (!in_array($theme, $allowedThemes)) {
            $theme = 'art_print'; // Fallback sur ART PRINT, pas vinyl_cult
        }
        
        // Rendre le thème disponible dans toutes les vues
        View::share('currentTheme', $theme);
        
        // Ajouter le thème aux données de la requête pour les contrôleurs
        $request->attributes->add(['theme' => $theme]);
        
        return $next($request);
    }
}
