{{-- resources/views/kiosque-art-print.blade.php --}}
{{-- Kiosque version ART PRINT - Galerie d'Art Moderne --}}

@php
    $cartCount = 0;
    if (auth()->check()) {
        try {
            $cartService = app(\App\Services\CartService::class);
            $cart = $cartService->getCart();
            $cartCount = $cart->items ? $cart->items->sum('quantite') : 0;
        } catch (\Exception $e) {
            $cartCount = 0;
        }
    }
@endphp

@extends('components.art_print.ap-layout')

@section('title', 'Collection')

@section('content')

<!-- Hero Collection -->
<section class="ap-hero" style="min-height: 60vh; padding-top: 8rem; padding-bottom: 4rem;">
    <div class="ap-container">
        <div class="ap-hero-content">
            <p class="ap-hero-label">Galerie • {{ count($vinylesData ?? []) }} œuvres exposées</p>
            
            <h1>
                Notre<br>
                <span class="light">collection de vinyles</span>
            </h1>
            
            <p>
                Chaque disque est sélectionné avec soin et présenté comme une pièce unique.
                Une exposition permanente de vinyles d'occasion de qualité.
            </p>
            
            <div class="ap-btn-group">
                @if($cartCount > 0)
                    <a href="{{ route('cart.index') }}" class="ap-btn ap-btn-dark">
                        Voir mon panier ({{ $cartCount }}) →
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Grille de Collection -->
<section class="ap-section" style="padding-top: 2rem;">
    
    <!-- Filtres minimalistes -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e5e5;">
        <div>
            <input type="text" 
                placeholder="Rechercher..." 
                style="background: transparent; border: 1px solid #e5e5e5; padding: 0.75rem 1rem; font-size: 0.85rem; width: 280px; font-family: 'Inter', sans-serif;">
        </div>
        
        <div style="font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; color: #666;">
            {{ count($vinylesData ?? []) }} pièces
        </div>
    </div>

    @if(empty($vinylesData))
        <div class="ap-text-block">
            <h3>Collection vide</h3>
            <p>Aucune œuvre n'est actuellement exposée. Revenez prochainement.</p>
        </div>
    @else
        <div class="ap-grid">
            @foreach($vinylesData as $vinyle)
                <article class="ap-card" style="cursor: pointer;" onclick="window.location.href='{{ route('kiosque.show', $vinyle['id']) }}">
                    
                    <!-- Image Œuvre -->
                    <div class="ap-card-image">
                        @if(isset($vinyle['image']) && $vinyle['image'])
                            <img src="{{ $vinyle['image'] }}" alt="{{ $vinyle['artiste'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 4rem;">💿</span>
                        @endif
                    </div>
                    
                    <!-- Méta Œuvre -->
                    <div class="ap-card-meta">
                        <h3 class="ap-card-title">{{ $vinyle['artiste'] }}</h3>
                        <span class="ap-card-year">{{ $vinyle['genre'] ?? 'Vinyle' }}</span>
                    </div>
                    
                    <p class="ap-card-artist">{{ $vinyle['modele'] ?? 'Vinyle d\'occasion' }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                        <p class="ap-card-price">€ {{ number_format(($vinyle['prix'] ?? 0) / 100, 2, ',', ' ') }}</p>
                        
                        @if(($vinyle['quantite'] ?? 0) > 0)
                            <button type="button" 
                                    onclick="event.stopPropagation(); openFondModal({{ $vinyle['id'] }}, {{ ($vinyle['prix'] ?? 0) / 100 }}, '{{ addslashes($vinyle['artiste']) }}')" 
                                    class="ap-btn ap-btn-dark" 
                                    style="padding: 0.6rem 1.2rem;">
                                +
                            </button>
                        @else
                            <span style="font-size: 0.7rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em;">Épuisé</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    
    
    <!-- Pagination -->
    @if(isset($vinyles) && $vinyles->lastPage() > 1)
        <div style="margin-top: 4rem; text-align: center;">
            {{ $vinyles->links() }}
        </div>
    @endif
    @endif
</section>

@endsection