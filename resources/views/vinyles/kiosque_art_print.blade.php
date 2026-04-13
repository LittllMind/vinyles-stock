@extends('layouts.art-print')

@section('title', 'Collection')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
@endphp

@section('content')

{{-- Hero Section --}}<div class="ap-hero" style="background-color: #f8f7f2; padding: 3rem 0;">
    <div style="max-width: 64rem; margin: 0 auto; padding: 0 1.5rem; text-align: center;">
        <p style="font-size: 0.875rem; color: #b8a77d; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">
            Galerie • {{ $vinyles->total() }} œuvres exposées
        </p>
        
        <h1 style="font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 300; color: #1a1a1a; margin-bottom: 1rem;">
            Notre collection de vinyles
        </h1>
        
        <p style="color: #666; max-width: 600px; margin: 0 auto;">
            Chaque disque est avec soin et présenté comme une pièce unique.
        </p>
    </div>
</div>

{{-- Recherche --}}<div style="background: white; border-bottom: 1px solid #e5e5e5; padding: 1.5rem 0;">
    <div style="max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;">
        <form action="{{ route('kiosque.index') }}" method="GET" style="display: flex; gap: 1rem; max-width: 600px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un artiste, album..." style="flex: 1; padding: 0.75rem 1rem; border: 1px solid #e5e5e5; border-radius: 4px; font-size: 0.9rem;">
            <button type="submit" class="ap-btn ap-btn-dark" style="padding: 0.75rem 1.5rem;">>
                Rechercher
            </button>
        </div>
    </div>
</div>

{{-- Grille --}}<div style="max-width: 80rem; margin: 0 auto; padding: 3rem 1.5rem;">
    
    <div style="margin-bottom: 2rem; color: #666;">
        {{ $vinyles->total() }} pièces
    </div>
    
    @if($vinyles->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            @foreach($vinyles as $vinyle)
                <article class="ap-card" style="background: white;">
                    <a href="{{ route('kiosque.show', $vinyle->id) }}" style="text-decoration: none; color: inherit; display: block;">
                        <div class="ap-card-image" style="background-color: #faf9f7;">
                            @if($vinyle->getFirstMediaUrl('photo', 'medium'))
                                <img src="{{ $vinyle->getFirstMediaUrl('photo', 'medium') }}" alt="{{ $vinyle->artiste }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 4rem;">💿</span>
                            @endif
                        </div>
                        
                        <div class="ap-card-meta" style="padding: 1.5rem 1.5rem 0;">
                            <h3 class="ap-card-title" style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $vinyle->artiste }}</h3>
                            <span style="color: #999; font-size: 0.85rem;">{{ $vinyle->genre ?? 'Vinyle' }}</span>
                        </div>
                        
                        <p style="padding: 0 1.5rem; margin: 0.5rem 0 0; color: #666; min-height: 2.5em; font-size: 0.9rem;">{{ $vinyle->modele }}</p>
                    </a>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 1.5rem 1.5rem; margin-top: 1rem;">
                        <p style="font-size: 1rem; font-weight: 600;">€ {{ formatPrice($vinyle->prix) }}</p>
                        
                        @if($vinyle->quantite > 0)
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); openFondModal({{ $vinyle->id }}, {{ $vinyle->prix }}, '{{ addslashes($vinyle->artiste) }}')" class="ap-btn ap-btn-dark" style="padding: 0.6rem 1.2rem;">
                                +
                            </button>
                        @else
                            <span style="font-size: 0.7rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em;">Épuisé</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        @if($vinyles->hasPages())
            <div style="display: flex; justify-content: center;">
                {{ $vinyles->links() }}
            </div>
        @endif
        
    @else
        <div style="text-align: center; padding: 4rem 0;">
            <p style="color: #999; font-size: 1.125rem; margin-bottom: 1rem;">Aucun vinyle trouvé</p>
            <a href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-dark">Voir tous les vinyles</a>
        </div>
    @endif
    
</div>

@endsection