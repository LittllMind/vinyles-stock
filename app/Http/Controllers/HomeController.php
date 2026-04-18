<?php

namespace App\Http\Controllers;

use App\Models\Vinyle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Page d'accueil publique - Vinyle Hydrodécoupé
     */
    public function landing(Request $request)
    {
        // Récupérer quelques vinyles en vedette pour la landing page
        $featured = Vinyle::where('quantite', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Statistiques rapides
        $stats = [
            'total' => Vinyle::where('quantite', '>', 0)->count(),
            'recent' => Vinyle::where('quantite', '>', 0)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // Utilise le helper theme_view pour le thème depuis session/middleware
        return view(theme_view('landing'), compact('featured', 'stats'));
    }

    /**
     * Page À propos
     */
    public function about(Request $request)
    {
        return view(theme_view('about'));
    }

    /**
     * Page Contact
     */
    public function contact(Request $request)
    {
        return view(theme_view('contact'));
    }

    /**
     * Page Article - Hermes Agent vs OpenClaw
     */
    public function articleHermesVsOpenclaw()
    {
        return view('articles.hermes-vs-openclaw');
    }

    /**
     * Page Conditions Générales de Vente
     */
    public function cgv()
    {
        return view('legal.cgv');
    }

    /**
     * Page Mention Légales
     */
    public function mentionsLegales()
    {
        return view('legal.mentions-legales');
    }

    /**
     * Page Politique de Confidentialité
     */
    public function confidentialite()
    {
        return view('legal.confidentialite');
    }
}