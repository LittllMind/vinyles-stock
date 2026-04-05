<?php

namespace App\Http\Controllers;

use App\Models\Vinyle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Page d'accueil publique - Vinyle Hydrodécoupé
     */
    public function landing()
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

        return view('landing', compact('featured', 'stats'));
    }

    /**
     * Page À propos
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Page Contact
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Page FAQ
     */
    public function faq()
    {
        $faqItems = [
            [
                'question' => 'Comment commander un vinyle personnalisé ?',
                'answer' => 'Rendez-vous dans notre kiosque, choisissez un vinyle et envoyez-nous votre design. Nous vous recontactons sous 24h avec un devis personnalisé.'
            ],
            [
                'question' => 'Quels sont les délais de fabrication ?',
                'answer' => 'Les délais varient de 3 à 7 jours ouvrés selon la complexité du design et la quantité commandée. Les commandes urgentes peuvent être traitées sous 48h avec supplément.'
            ],
            [
                'question' => 'Quels formats de fichier acceptez-vous ?',
                'answer' => 'Nous acceptons les formats vectoriels (AI, EPS, SVG, PDF vectoriel) pour une qualité optimale. Les images haute résolution (300 DPI minimum) en PNG ou JPG sont également acceptées.'
            ],
            [
                'question' => 'Proposez-vous des tarifs dégressifs ?',
                'answer' => 'Oui ! Nous proposons des réductions à partir de 5 vinyles identiques. Contactez-nous pour un devis personnalisé pour vos événements ou besoins professionnels.'
            ],
            [
                'question' => 'Puis-je voir un aperçu avant fabrication ?',
                'answer' => 'Bien sûr ! Nous envoyons systématiquement une maquette numérique pour validation avant tout début de fabrication. Les modifications sont possibles à ce stade.'
            ],
            [
                'question' => 'Quels sont les modes de paiement acceptés ?',
                'answer' => 'Nous acceptons les paiements par carte bancaire (CB, Visa, Mastercard), PayPal et virement bancaire. Un acompte de 50% est demandé à la commande.'
            ],
            [
                'question' => 'Comment suivre ma commande ?',
                'answer' => 'Une fois votre commande validée, vous recevez un email avec un lien de suivi. Vous pouvez également consulter l\'état de votre commande dans votre espace client.'
            ],
            [
                'question' => 'Quelle est votre politique de retour ?',
                'answer' => 'Les vinyles personnalisés ne sont pas échangeables ni remboursables sauf en cas de défaut de fabrication avéré. Une garantie de 6 mois couvre tout problème technique.'
            ],
        ];

        return view('faq', compact('faqItems'));
    }
}