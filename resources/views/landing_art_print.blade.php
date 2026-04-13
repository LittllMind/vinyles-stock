{{-- resources/views/landing-art-print.blade.php --}}
{{-- Landing page ART PRINT - Galerie d'Art --}}

@extends('components.art_print.ap-layout')

@section('title', 'Fundisc • Vinyles ART PRINT')

@section('content')

{{-- Hero Pleine Page --}}
<section class="ap-hero" style="min-height: 100vh; padding-top: 0;">
    <div class="ap-container">
        <div class="ap-hero-content">
            <p class="ap-hero-label">Galerie • {{ $stats['total'] ?? 0 }} pièces en collection</p>
            
            <h1 style="font-size: clamp(3rem, 8vw, 6rem);">
                ART<br>
                <span class="light">PRINT</span>
            </h1>
            
            <p style="font-size: 1.1rem; max-width: 600px;">
                Une sélection de vinyles curatés comme des œuvres d'art.
                Chaque disque choisi pour son histoire, sa rareté, sa beauté.
            </p>
            
            <div class="ap-btn-group" style="margin-top: 3rem;">
                <a href="{{ route('kiosque.index') }}?theme=art-print" class="ap-btn ap-btn-dark">
                    Découvrir la collection
                </a>
                <a href="#featured" class="ap-btn ap-btn-outline">
                    Nouveautés →
                </a>
            </div>
        </div>
        
        {{-- Visual hint --}}
        <div style="position: absolute; bottom: 3rem; left: 50%; transform: translateX(-50%); text-align: center;">
            <span style="font-size: 0.7rem; letter-spacing: 0.2em; text-transform: uppercase; color: #999;">
                {{ $stats['recent'] ?? 0 }} arrivées cette semaine
            </span>
        </div>
    </div>
</section>

{{-- Section Featured --}}
<section id="featured" class="ap-section" style="padding-top: 6rem; padding-bottom: 6rem; background: #FAFAFA;">
    <div class="ap-container">
        
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; font-weight: 300;">Nouveautés</h2>
            <a href="{{ route('kiosque.index') }}?theme=art-print" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666;">Voir tout →</a>
        </div>

        @if($featured->isEmpty())
            <div class="ap-text-block">
                <h3>Collection en préparation</h3>
                <p>Les premières pièces seront exposées prochainement.</p>
            </div>
        @else
            <div class="ap-grid">
                @foreach($featured as $vinyle)
                    <article class="ap-card" style="cursor: pointer;" onclick="window.location.href='{{ route('kiosque.show', $vinyle->id) }}?theme=art-print'">
                        <!-- Image -->
                        <div class="ap-card-image">
                            @if($vinyle->getFirstMediaUrl('photo', 'medium'))
                                <img src="{{ $vinyle->getFirstMediaUrl('photo', 'medium') }}" alt="{{ $vinyle->artiste }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 4rem;">💿</span>
                            @endif
                        </div>
                        
                        <!-- Méta -->
                        <div class="ap-card-meta">
                            <h3 class="ap-card-title">{{ $vinyle->artiste }}</h3>
                            <span class="ap-card-year">{{ $vinyle->created_at?->format('Y') ?? '' }}</span>
                        </div>
                        
                        <p class="ap-card-artist">{{ $vinyle->modele }}</p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                            <p class="ap-card-price">€ {{ number_format($vinyle->prix, 2, ',', ' ') }}</p>
                            
                            @if($vinyle->quantite > 0)
                                <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="vinyle_id" value="{{ $vinyle->id }}">
                                    <input type="hidden" name="quantite" value="1">
                                    <input type="hidden" name="fond" value="standard">
                                    
                                    <button type="submit" class="ap-btn ap-btn-dark" style="padding: 0.6rem 1.2rem;">
                                        +
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.7rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em;">Épuisé</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
        
        {{-- CTA --}}
        <div style="text-align: center; margin-top: 5rem;">
            <a href="{{ route('kiosque.index') }}?theme=art-print" class="ap-btn ap-btn-dark" style="padding: 1rem 2rem;">
                Voir toute la collection ({{ $stats['total'] ?? 0 }})
            </a>
        </div>
    </div>
</section>

{{-- Section About --}}
<section class="ap-section" style="padding-top: 8rem; padding-bottom: 8rem;">
    <div class="ap-container">
        <div class="ap-text-block" style="max-width: 800px;">
            <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Curateurs d'objets sonores</h3>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.8;">
                Nous présentons chaque vinyle comme une pièce de collection.
                Pas de stock industriel, mais une sélection éditoriale,
                actualisée chaque semaine avec soin.
            </p>
            
            <p style="font-size: 0.85rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 3rem;">
                EXPOSITION PERMANENTE • MISE À JOUR HEBDOMADAIRE
            </p>
        </div>
    </div>
</section>

@endsection