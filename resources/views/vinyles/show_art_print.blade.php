{{-- resources/views/vinyles/show-art-print.blade.php --}}
{{-- Fiche vinyle ART PRINT - Style galerie d'art --}}

@extends('components.art_print.ap-layout')

@section('title', $vinyle->artiste . ' – ' . $vinyle->modele)

@section('content')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
@endphp

{{-- Navigation secondaire --}}
<div style="padding-top: 7rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e5e5;">
    <div class="ap-container">
        <a  href="{{ route('kiosque.index') }}" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; text-decoration: none;">
            ← Retour à la collection
        </a>
    </div>
</div>

{{-- Fiche Produit --}}
<section class="ap-section" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="ap-container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
            
            {{-- Image Principale --}}
            <div>
                <div style="background: #F8F8F8; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e5e5;">
                    @if($vinyle->getFirstMediaUrl('photo', 'large'))
                        <img src="{{ $vinyle->getFirstMediaUrl('photo', 'large') }}" 
                             alt="{{ $vinyle->artiste }} - {{ $vinyle->modele }}"
                             style="width: 100%; height: 100%; object-fit: contain;">
                    @elseif($vinyle->getFirstMediaUrl('photo', 'medium'))
                        <img src="{{ $vinyle->getFirstMediaUrl('photo', 'medium') }}" 
                             alt="{{ $vinyle->artiste }} - {{ $vinyle->modele }}"
                             style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <span style="font-size: 8rem;">💿</span>
                    @endif
                </div>
                
                {{-- Miniatures (si plusieurs photos) --}}
                @php($photos = $vinyle->getMedia('photo'))
                @if($photos->count() > 1)
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        @foreach($photos as $photo)
                            <div style="width: 80px; height: 80px; background: #F8F8F8; border: 1px solid #e5e5e5; cursor: pointer;">
                                <img src="{{ $photo->getUrl('thumb') }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            {{-- Informations --}}
            <div>
                {{-- Méta --}}
                <p style="font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; color: #999; margin-bottom: 1rem;">
                    @if($vinyle->genre)
                        {{ $vinyle->genre }} <span style="margin: 0 0.5rem;">•</span>
                    @endif
                    Réf. {{ $vinyle->reference }}
                </p>
                
                <h1 style="font-size: 2.5rem; font-weight: 300; line-height: 1.2; margin-bottom: 0.5rem;">
                    {{ $vinyle->artiste }}
                </h1>
                
                <p style="font-size: 1.25rem; color: #666; margin-bottom: 2rem;">
                    {{ $vinyle->modele }}
                </p>
                
                {{-- Détails --}}
                <div style="border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; padding: 1.5rem 0; margin-bottom: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; font-size: 0.9rem;">
                        @if($vinyle->format)
                            <div>
                                <span style="display: block; color: #999; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Format</span>
                                <span>{{ $vinyle->format }}</span>
                            </div>
                        @endif
                        
                        @if($vinyle->statut)
                            <div>
                                <span style="display: block; color: #999; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">État</span>
                                <span>{{ $vinyle->statut }}</span>
                            </div>
                        @endif
                        
                        <div>
                            <span style="display: block; color: #999; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Stock</span>
                            <span>{{ $vinyle->quantite }} exemplaire{{ $vinyle->quantite > 1 ? 's' : '' }}</span>
                        </div>
                        
                        @if($vinyle->annee_sortie)
                            <div>
                                <span style="display: block; color: #999; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Année</span>
                                <span>{{ $vinyle->annee_sortie }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Prix et CTA --}}
                <div style="margin-bottom: 2rem;">
                    <p style="font-size: 2rem; font-weight: 300; margin-bottom: 2rem;">
                        € {{ formatPrice($vinyle->prix) }}
                    </p>
                    
                    @if($vinyle->quantite > 0)
                        <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="vinyle_id" value="{{ $vinyle->id }}">
                            <input type="hidden" name="fond" value="standard">
                            
                            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                                <div style="border: 1px solid #e5e5e5; display: flex; align-items: center;">
                                    <button type="button" 
                                            style="background: none; border: none; padding: 0.75rem 1rem; cursor: pointer; font-size: 1rem;"
                                            onclick="this.parentElement.querySelector('input').stepDown()">−</button>
                                    <input type="number" name="quantite" value="1" min="1" max="{{ $vinyle->quantite }}"
                                           style="width: 60px; text-align: center; border: none; background: transparent; font-size: 0.95rem;">
                                    
                                    <button type="button"
                                            style="background: none; border: none; padding: 0.75rem 1rem; cursor: pointer; font-size: 1rem;"
                                            onclick="this.parentElement.querySelector('input').stepUp()">+</button>
                                </div>
                                
                                <button type="submit" class="ap-btn ap-btn-dark" style="flex: 1; padding: 1rem 2rem;">
                                    Ajouter au panier
                                </button>
                            </div>
                        </form>
                        
                        <p style="font-size: 0.75rem; color: #666;">
                            🚚 Livraison en France métropolitaine
                        </p>
                    @else
                        <button class="ap-btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                            Épuisé
                        </button>
                        
                        <p style="font-size: 0.85rem; color: #999; margin-top: 1rem;">
                            Ce vinyle n'est plus disponible.
                        </p>
                    @endif
                </div>
                
                {{-- Description si existe --}}
                @if($vinyle->description)
                    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e5e5;">
                        <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: #999; margin-bottom: 1rem;">À propos</h3>
                        <p style="line-height: 1.8; color: #666;">{{ $vinyle->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Section suggestions --}}
<section style="padding: 4rem 0; background: #FAFAFA;">
    <div class="ap-container">
        <h2 style="font-size: 1.25rem; font-weight: 300; margin-bottom: 2rem;">Vous aimerez aussi</h2>
        
        <p style="color: #999;">
            <a  href="{{ route('kiosque.index') }}" style="color: inherit;">Découvrir la collection complète →</a>
        </p>
    </div>
</section>

@endsection