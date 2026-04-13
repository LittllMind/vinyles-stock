{{-- resources/views/payment/success-art-print.blade.php --}}
{{-- Confirmation paiement réussi - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Commande confirmée')

@section('content')

<section class="ap-section ap-hero" style="min-height: 70vh; display: flex; align-items: center; text-align: center;">
    <div class="ap-container" style="max-width: 600px;">
        
        <!-- Icône confirmation -->
        <div style="width: 120px; height: 120px; margin: 0 auto 2rem; border: 2px solid #1A1A1A; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="2">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
        </div>
        
        <p class="ap-hero-label">Confirmation</p>
        
        <h1 style="margin-bottom: 1rem;">Commande confirmée</h1>
        
        <p style="color: #666; margin-bottom: 2rem;">
            Votre paiement a été validé avec succès.
        </p>
        
        @if(isset($payment) && $payment->order)
            <div style="border: 1px solid #E5E5E5; padding: 1.5rem; margin-bottom: 2rem; background: #FAFAFA;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Numéro de commande</p>
                <p style="font-size: 1.5rem; font-weight: 600;">#{{ $payment->order->id }}</p>
            </div>
        @endif
        
        <div style="margin-bottom: 2rem;">
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">
                Un email de confirmation vous a été envoyé.
            </p>
            <p style="font-size: 0.9rem; color: #666;">
                Votre commande sera expédiée sous 48h ouvrées.
            </p>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a  href="{{ route('orders.my') }}" class="ap-btn ap-btn-dark">
                Mes commandes →
            </a>
            
            <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-outline">
                Continuer les achats
            </a>
        </div>
        
    </div>
</section>

@endsection