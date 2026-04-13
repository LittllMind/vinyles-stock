<?php

/**
 * Helpers personnalisés pour l'application
 */

if (!function_exists('theme_view')) {
    /**
     * Retourne le nom de vue approprié selon le thème actif.
     * 
     * @param string $viewBase Nom de base de la vue (ex: 'auth.login')
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

if (!function_exists('theme_route')) {
    /**
     * Génère une URL de route avec le thème préservé dans la session.
     * Le paramètre theme n'apparaît pas dans l'URL mais reste en mémoire.
     * 
     * @param string $name Nom de la route
     * @param array $parameters Paramètres additionnels
     * @return string URL
     */
    function theme_route(string $name, array $parameters = []): string
    {
        // Le thème est en session, pas besoin de l'ajouter à l'URL
        return route($name, $parameters);
    }
}

if (!function_exists('is_theme')) {
    /**
     * Vérifie si le thème actuel correspond à celui demandé.
     * 
     * @param string $theme Nom du thème ('art_print' ou 'vinyl-cult')
     * @return bool
     */
    function is_theme(string $theme): bool
    {
        $currentTheme = request()->attributes->get('theme') ?? session('theme', 'vinyl-cult');
        return $currentTheme === $theme;
    }
}
