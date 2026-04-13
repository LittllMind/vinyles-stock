<?php

if (!function_exists('theme_view')) {
    /**
     * Helper pour retourner le nom de vue approprié selon le thème actif.
     * 
     * @param string $viewBase Nom de base de la vue (ex: 'kiosque.index')
     * @return string Nom complet de la vue avec suffixe thème si nécessaire
     */
    function theme_view(string $viewBase): string
    {
        $theme = request()->attributes->get('theme') ?? session('theme', 'vinyl-cult');
        
        if ($theme === 'art_print') {
            // Vérifier si une version art-print existe
            $artPrintView = $viewBase . '_art_print';
            if (view()->exists($artPrintView)) {
                return $artPrintView;
            }
        }
        
        return $viewBase;
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Helper pour retourner le chemin CSS/JS approprié selon le thème.
     * 
     * @param string $path Chemin relatif au dossier public
     * @return string Chemin complet avec version thème si nécessaire
     */
    function theme_asset(string $path): string
    {
        $theme = request()->attributes->get('theme') ?? session('theme', 'vinyl-cult');
        
        // Remplace 'app.css' par 'art-print.css' ou similaire si besoin
        if ($theme === 'art_print' && str_contains($path, 'app.css')) {
            return asset('css/art-print.css');
        }
        
        return asset($path);
    }
}

if (!function_exists('theme_route')) {
    /**
     * Helper pour générer une URL de route avec le thème préservé.
     * 
     * @param string $name Nom de la route
     * @param array $parameters Paramètres additionnels
     * @return string URL
     */
    function theme_route(string $name, array $parameters = []): string
    {
        $theme = request()->attributes->get('theme') ?? session('theme', 'vinyl-cult');
        
        if ($theme === 'vinyl-cult') {
            // Pas besoin de paramètre pour le thème par défaut
            return route($name, $parameters);
        }
        
        return route($name, array_merge($parameters, ['theme' => $theme]));
    }
}