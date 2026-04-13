<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Récupère le thème depuis l'URL ou la session
        $theme = $request->query('theme') ?? session('theme');
        
        // Si un nouveau thème est spécifié dans l'URL, le sauvegarder en session
        if ($request->has('theme')) {
            session(['theme' => $theme]);
        }
        
        // Valeur par défaut : art-print (thème unique)
        if (!$theme) {
            $theme = 'art_print';
        }
        
        // Vérifier que c'est un thème valide
        $allowedThemes = ['vinyl_cult', 'art_print'];
        if (!in_array($theme, $allowedThemes)) {
            $theme = 'vinyl_cult';
        }
        
        // Rendre le thème disponible dans toutes les vues
        View::share('currentTheme', $theme);
        
        // Ajouter le thème aux données de la requête pour les contrôleurs
        $request->attributes->add(['theme' => $theme]);
        
        return $next($request);
    }
}
