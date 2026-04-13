{{-- resources/views/orders/payment-art-print.blade.php --}}
{{-- Paiement - Style minimaliste galerie --}}

@extends('components.art_print.ap-layout')

@section('title', 'Paiement')

@section('content')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
@endphp

<section class="ap-section" style="padding-top: 6rem;">
    <div class="ap-container" style="max-width: 900px;">
        
        {{-- Header --}}
        <p class="ap-hero-label" style="text-align: center;">Étape 3 sur 3</p>
        <h1 style="text-align: center; margin-bottom: 3rem;">Paiement sécurisé</h1>
        
        {{-- Progress Steps --}}
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 4rem; gap: 1rem;">
            {{-- Step 1: Panier (done) --}}
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 2rem; height: 2rem; border-radius: 50%; background: #1A1A1A; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">1</span>
                <span style="font-size: 0.75rem; color: #666;">Panier</span>
            </div>
            <div style="width: 3rem; height: 1px; background: #1A1A1A;"></div>
            
            {{-- Step 2: Livraison (done) --}}
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 2rem; height: 2rem; border-radius: 50%; background: #1A1A1A; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
                <span style="font-size: 0.75rem; color: #666;">Livraison</span>
            </div>
            <div style="width: 3rem; height: 1px; background: #1A1A1A;"></div>
            
            {{-- Step 3: Paiement (current) --}}
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 2rem; height: 2rem; border-radius: 50%; background: #FFB800; color: #1A1A1A; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">3</span>
                <span style="font-size: 0.75rem; font-weight: 600;">Paiement</span>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
            
            {{-- Left Column --}}
            <div>
                {{-- Adresse de livraison --}}
                <div style="border: 1px solid #E5E5E5; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666;">Adresse de livraison</p>
                        <a  href="{{ route('orders.create') }}" style="font-size: 0.75rem; text-decoration: underline;">Modifier</a>
                    </div>
                    
                    @if(isset($shipping) && is_array($shipping))
                        <p style="font-weight: 500;">{{ $shipping['nom'] ?? '' }}</p>
                        <p style="color: #666;">{{ $shipping['adresse'] ?? '' }}</p>
                        <p style="color: #666;">{{ ($shipping['code_postal'] ?? '') . ' ' . ($shipping['ville'] ?? '') }}</p>
                        <p style="color: #666; font-size: 0.9rem; margin-top: 0.5rem;">{{ $shipping['email'] ?? '' }}</p>
                    @endif
                </div>
                
                {{-- Articles --}}
                <div style="border: 1px solid #E5E5E5; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1.5rem;">Articles commandés</p>
                    
                    @if(isset($cart) && $cart->items)
                        @foreach($cart->items as $item)
                            @php
                                $vinyle = $item->vinyle ?? null;
                                $fond = $item->fond ?? null;
                            @endphp
                            <div style="display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #E5E5E5;">
                                <div style="width: 80px; height: 80px; background: #F5F5F5; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 0.7rem; color: #999;">Vinyle</span>
                                </div>
                                <div style="flex: 1;">
                                    <p style="font-weight: 500;">{{ $vinyle ? $vinyle->nom : 'Vinyle' }}</p>
                                    <p style="font-size: 0.85rem; color: #666;">Qté: {{ $item->quantite }}</p>
                                    @if($fond)
                                        <p style="font-size: 0.8rem; color: #999;">Avec fond {{ $fond->nom }}</p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <p style="font-weight: 500;">€ {{ formatPrice($item->prix_unitaire * $item->quantite) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                {{-- Paiement sécurisé --}}
                <div style="border: 1px solid #E5E5E5; padding: 1.5rem;">
                    <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Mode de paiement</p>
                    
                    <div style="background: #F8F8F8; padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="background: white; padding: 0.5rem 1rem; border: 1px solid #E5E5E5;">
                            <span style="font-size: 0.75rem; font-weight: 600;">CB</span>
                        </div>
                        <div>
                            <p style="font-weight: 500;">Carte bancaire</p>
                            <p style="font-size: 0.8rem; color: #999;">Paiement sécurisé par Stripe</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Right Column: Récapitulatif --}}
            <div style="position: sticky; top: 100px;">
                <div style="border: 1px solid #E5E5E5; padding: 1.5rem;">
                    <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1.5rem;">Récapitulatif</p>
                    
                    @php
                        $count = isset($cart->items) ? $cart->items->count() : 0;
                        $subtotal = isset($cart->total) ? $cart->total : 0;
                    @endphp
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem;">
                        <span style="color: #666;">Sous-total ({{ $count }} article{{ $count > 1 ? 's' : '' }})</span>
                        <span>€ {{ formatPrice($subtotal) }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem;">
                        <span style="color: #666;">Livraison</span>
                        <span style="color: #22C55E;">Gratuite</span>
                    </div>
                    
                    <div style="border-top: 1px solid #E5E5E5; margin-top: 1rem; padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; font-weight: 600;">
                            <span>Total à payer</span>
                            <span>€ {{ formatPrice($subtotal) }}</span>
                        </div>
                    </div>
                    
                    {{-- Bouton paiement --}}
                    <form action="{{ route('payment.checkout') }}" method="POST" style="margin-top: 1.5rem;">
                        @csrf
                        @if(isset($order) && $order->id)
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @endif
                        
                        <button type="submit" class="ap-btn ap-btn-dark" style="width: 100%;">
                            Payer maintenant →
                        </button>
                    </form>
                    
                    <a  href="{{ route('orders.create') }}
                      " style="display: block; text-align: center; margin-top: 1rem; font-size: 0.85rem; text-decoration: underline; color: #666;">
                        ← Retour
                    </a>
                    
                    {{-- Garanties --}}
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E5E5E5;">
                        <p style="font-size: 0.75rem; color: #999; margin-bottom: 0.25rem;">
                            Paiement crypté SSL
                        </p>
                        <p style="font-size: 0.75rem; color: #999; margin-bottom: 0.25rem;">
                            Satisfait ou remboursé (14 jours)
                        </p>
                        <p style="font-size: 0.75rem; color: #999;">
                            Livraison garantie
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

@endsection