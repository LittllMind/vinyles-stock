{{-- resources/views/cart/index-art-print.blade.php --}}
{{-- Panier ART PRINT - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Panier')

@section('content')

<!-- Hero -->
<section class="ap-hero" style="min-height: auto; padding-top: 8rem; padding-bottom: 3rem;">
    <div class="ap-container">
        <h1>Votre panier</h1>
        
        <p style="color: #666;">
            @if($cart->items->count() === 0)
                Votre panier est vide
            @else
                {{ $cart->items->sum('quantite') }} article{{ $cart->items->sum('quantite') > 1 ? 's' : '' }} dans votre sélection
            @endif
        </p>
    </div>
</section>

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
$total = 0;
foreach ($cart->items as $item) {
    $total += $item->quantite * $item->prix_unitaire;
}
@endphp

<!-- Contenu Panier -->
<section class="ap-section" style="padding-top: 2rem; padding-bottom: 6rem;">
    <div class="ap-container">
        
        @if(!$cart->items || $cart->items->isEmpty())
            
            <div class="ap-text-block" style="text-align: center; padding: 4rem 0;">
                <p style="font-size: 4rem; margin-bottom: 1rem;">🛒</p>
                <h3>Votre sélection est vide</h3>
                <p style="color: #666;">
                    Explorez notre collection pour découvrir les pièces disponibles.
                </p>
                
                <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-dark" style="margin-top: 2rem;">
                    Découvrir la collection
                </a>
            </div>
        
        @else
            
            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 4rem;">
                
                {{-- Liste articles --}}
                <div>
                    {{-- Erreurs de stock --}}
                    @if(!empty($stockErrors))
                        <div style="background: #FEF3F2; border: 1px solid #FECACA; padding: 1rem; margin-bottom: 2rem; font-size: 0.85rem;">
                            <strong>Quantités ajustées :</strong>
                            <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                                @foreach($stockErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div style="border-top: 1px solid #e5e5e5;">
                        @foreach($cart->items as $item)
                            @php($vinyle = $item->vinyle)
                            <div style="display: grid; grid-template-columns: 100px 1fr auto; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid #e5e5e5;">
                                
                                {{-- Image --}}
                                <div style="background: #F8F8F8; aspect-ratio: 1; display: flex; align-items: center; justify-content: center;">
                                    @if($vinyle->getFirstMediaUrl('photo', 'thumb'))
                                        <img src="{{ $vinyle->getFirstMediaUrl('photo', 'thumb') }}" 
                                             alt="{{ $vinyle->artiste }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span style="font-size: 2rem;">💿</span>
                                    @endif
                                </div>
                                
                                {{-- Détails --}}
                                <div>
                                    <p style="font-size: 0.75rem; color: #999; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">
                                        {{ $vinyle->artiste }}
                                    </p>
                                    
                                    <p style="font-weight: 500; margin-bottom: 0.5rem;">
                                        <a  href="{{ route('kiosque.show', $vinyle->id) }} 
                                          " style="color: inherit; text-decoration: none;">
                                            {{ $vinyle->modele }}
                                        </a>
                                    </p>
                                    
                                    @if($item->fond_id)
                                        <p style="font-size: 0.75rem; color: #999;">
                                            + Fond {{ $item->fond->type }}
                                        </p>
                                    @endif
                                    
                                    {{-- Actions --}}
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.75rem;">
                                        {{-- Quantité --}}
                                        <div style="border: 1px solid #e5e5e5; display: flex; align-items: center;">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" style="display: flex; align-items: center;">
                                                @csrf
                                                @method('PATCH')
                                                
                                                <button type="submit" name="quantite" value="{{ $item->quantite - 1 }}"
                                                        {{ $item->quantite <= 1 ? 'disabled' : '' }}
                                                        style="background: none; border: none; padding: 0.5rem 0.75rem; cursor: pointer; font-size: 0.9rem; opacity: {{ $item->quantite <= 1 ? '0.3' : '1' }};"
                                                >−</button>
                                                
                                                <span style="padding: 0 0.75rem; font-size: 0.85rem; min-width: 30px; text-align: center;">
                                                    {{ $item->quantite }}
                                                </span>
                                                
                                                <button type="submit" name="quantite" value="{{ $item->quantite + 1 }}"
                                                        {{ $item->quantite >= $vinyle->quantite ? 'disabled' : '' }}
                                                        style="background: none; border: none; padding: 0.5rem 0.75rem; cursor: pointer; font-size: 0.9rem; opacity: {{ $item->quantite >= $vinyle->quantite ? '0.3' : '1' }};"
                                                >+</button>
                                            </form>
                                        </div>
                                        
                                        {{-- Supprimer --}}
                                        <form action="{{ route('cart.remove', $item) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="submit" style="background: none; border: none; color: #999; font-size: 0.75rem; cursor: pointer; text-decoration: underline;"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                {{-- Prix --}}
                                <div style="text-align: right;">
                                    <p style="font-weight: 500;">€ {{ formatPrice($item->prix_unitaire * $item->quantite) }}</p>
                                    
                                    <p style="font-size: 0.8rem; color: #999;">
                                        € {{ formatPrice($item->prix_unitaire) }} / unité
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Retour collection --}}
                    <div style="margin-top: 2rem;">
                        <a  href="{{ route('kiosque.index') }} 
                          " style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; text-decoration: none;">
                           ← Continuer mes achats
                        </a>
                    </div>
                </div>
                
                {{-- Récapitulatif --}}
                <div>
                    <div style="background: #FAFAFA; padding: 2rem; border: 1px solid #e5e5e5;">
                        <h2 style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e5e5;">
                            Récapitulatif
                        </h2>
                        
                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem;">
                                <span>Sous-total</span>
                                <span>€ {{ formatPrice($total) }}</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem; color: #666;">
                                <span>Estimation livraison</span>
                                <span>Calculé à l'étape suivante</span>
                            </div>
                        </div>
                        
                        <div style="border-top: 1px solid #e5e5e5; padding-top: 1rem; margin-bottom: 2rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 500;">
                                <span>Total</span>
                                <span style="font-size: 1.25rem;">€ {{ formatPrice($total) }}</span>
                            </div>
                            
                            <p style="font-size: 0.75rem; color: #999; margin-top: 0.5rem;">
                                TTC • Hors frais de livraison
                            </p>
                        </div>
                        
                        {{-- CTA --}}
                        @if(auth()->check())
                            <a  href="{{ route('orders.create') }}" class="ap-btn ap-btn-dark" style="display: block; text-align: center; width: 100%; padding: 1rem;">
                                Valider ma commande →
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(route('cart.index')) }}" 
                               class="ap-btn ap-btn-dark" style="display: block; text-align: center; width: 100%; padding: 1rem;">
                                Se connecter pour commander
                            </a>
                            
                            <p style="text-align: center; font-size: 0.8rem; color: #666; margin-top: 1rem;">
                                Ou <a href="{{ route('register') }}" style="text-decoration: underline;">créer un compte</a>
                            </p>
                        @endif
                        
                        {{-- Vider panier --}}
                        <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            <button type="submit" 
                                    style="background: none; border: none; width: 100%; text-align: center; font-size: 0.75rem; color: #999; cursor: pointer; text-decoration: underline;">
                                Vider le panier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection