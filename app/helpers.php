<?php

/**
 * Helpers personnalisés pour l'application
 * 
 * Thème ART PRINT unique par défaut (vinyl-cult désactivé pour le public)
 */

if (!function_exists('theme_view')) {
    /**
     * Retourne le nom de vue approprié selon le thème actif.
     * Par défaut, ART PRINT est le thème unique.
     * 
     * @param string $viewBase Nom de base de la vue (ex: 'auth.login')
     * @return string Nom complet de la vue avec suffixe _art_print
     */
    function theme_view(string $viewBase): string
    {
        // Thème par défaut: ART PRINT (vinyl-cult réservé admin via session)
        $theme = request()->attributes->get('theme') ?? session('theme', 'art_print');
        
        if ($theme === 'art_print' || $theme === 'art-print') {
            // Priorité à la version ART PRINT
            $artPrintView = $viewBase . '_art_print';
            if (view()->exists($artPrintView)) {
                return $artPrintView;
            }
            // Fallback si pas de version ART PRINT (logs warning en dev)
            if (app()->environment('local')) {
                // logger()->warning("Vue ART PRINT manquante: {$artPrintView}, fallback sur {$viewBase}");
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
