<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO - Titre optimisé --}}
    <title>@yield('title', 'Accueil') • FUN DISC</title>
    
    {{-- Meta Description pour SEO --}}
    <meta name="description" content="@yield('meta_description', 'FUN DISC - Vinyles découpés en œuvres d\'art uniques. Chaque pièce est sélectionnée avec soin et transformée avec passion en objet de décoration contemporain.')">
    
    {{-- Open Graph pour réseaux sociaux --}}
    <meta property="og:title" content="@yield('og_title', 'FUN DISC - Vinyles découpés en œuvres d\'art')">
    <meta property="og:description" content="@yield('og_description', 'Découvrez notre collection de vinyles découpés, transformés en pièces uniques d\'art contemporain.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:site_name" content="FUN DISC">
    <meta property="og:locale" content="fr_FR">
    
    
    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'FUN DISC - Vinyles découpés en œuvres d\'art')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Découvrez notre collection de vinyles découpés.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">
    
    {{-- Canonical URL pour éviter contenu dupliqué --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    {{-- Preconnect pour performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="{{ asset('css/ap-global.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    @php
        $role = Auth::check() ? Auth::user()->role : null;
        $cartCount = session('cart_count', 0);
    @endphp

    {{-- Navigation --}}
    @include('components.art_print.ap-nav')

    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Modale sélecteur de fond --}}
    @if(!request()->routeIs('admin.*') && !request()->routeIs('marche.*'))
        @include('components.art_print.fond-selector-modal')
    @endif

    {{-- Footer complet --}}
    <footer class="bg-white border-t border-gray-200 py-16 mt-20">
        <div class="max-w-6xl mx-auto px-6">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                {{-- Marque --}}
                <div>
                    <h3 style="font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">FUN DISC</h3>
                    <p style="color: #666; font-size: 0.9rem; line-height: 1.6;">
                        Vinyles découpés en œuvres d'art uniques.
                        Chaque pièce raconte une histoire musicale.
                    </p>
                </div>

                {{-- Navigation --}}
                <div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; color: #999;">Explorer</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('kiosque.index') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">Collection</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('about') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">À propos</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('contact') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">Contact</a></li>
                    </ul>
                </div>

                {{-- Légal --}}
                <div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; color: #999;">Informations</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('cgv') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">CGV</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('mentions-legales') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">Mentions légales</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="{{ route('confidentialite') }}" style="color: #666; text-decoration: none; font-size: 0.9rem;">Politique de confidentialité</a></li>
                    </ul>
                </div>

                {{-- Réseaux --}}
                <div>
                    <h4 style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; color: #999;">Suivez-nous</h4>
                    <div style="display: flex; gap: 1rem;">
                        <a href="https://instagram.com/fundisc" target="_blank" rel="noopener" 
                            style="width: 40px; height: 40px; border: 1px solid #e5e5e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #666; transition: all 0.3s;"
                            aria-label="Instagram FUN DISC">
                            📷
                        </a>
                    </div>
                    <p style="color: #666; font-size: 0.85rem; margin-top: 1rem;">@fundisc</p>
                </div>
            </div>

            {{-- Séparateur --}}
            <hr style="border: none; border-top: 1px solid #eee; margin: 2rem 0;">

            {{-- Copyright --}}
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                <p style="color: #999; font-size: 0.85rem; margin: 0;">
                    © 2026 FUN DISC • Tous droits réservés
                </p>
                <p style="color: #999; font-size: 0.75rem; margin: 0;">
                    Hébergé par Hostinger • fait avec ❤️ en France
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
