{{-- resources/views/payment/cancel-art-print.blade.php --}}
{{-- Paiement annulé - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Paiement annulé')

@section('content')

<section class="ap-section ap-hero" style="min-height: 70vh; display: flex; align-items: center; text-align: center;">
    <div class="ap-container" style="max-width: 500px;">
        
        <p class="ap-hero-label">Paiement</p>
        
        <h1 style="margin-bottom: 1rem;">Commande annulée</h1>
        
        <p style="color: #666; margin-bottom: 2rem;">
            Le paiement a été annulé. Aucun montant n'a été débité.
        </p>
        
        <div style="border: 1px solid #E5E5E5; padding: 1.5rem; margin-bottom: 2rem;">
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">
                Votre panier est toujours disponible. Vous pouvez reprendre votre commande quand vous le souhaitez.
            </p>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a  href="{{ route('cart.index') }}" class="ap-btn ap-btn-dark">
                Retour au panier →
            </a>
            
            <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-outline">
                Continuer les achats
            </a>
        </div>
        
    </div>
</section>

@endsection