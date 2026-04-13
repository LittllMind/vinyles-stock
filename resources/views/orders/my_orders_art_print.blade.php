{{-- resources/views/orders/my-orders-art-print.blade.php --}}
{{-- Mes commandes - Style minimaliste galerie --}}

@extends('components.art_print.ap-layout')

@section('title', 'Mes commandes')

@section('content')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
@endphp

<section class="ap-section" style="padding-top: 6rem;">
    <div class="ap-container">
        
        <p class="ap-hero-label">Historique</p>
        
        <h1 style="margin-bottom: 3rem;">Mes commandes</h1>
        
        @if($orders->isEmpty())
            {{-- Aucune commande --}}
            
            <div style="max-width: 500px; margin: 4rem auto; text-align: center; padding: 3rem; border: 1px solid #E5E5E5;">
                
                <p style="color: #666; margin-bottom: 1.5rem;">Vous n'avez pas encore passé de commande.</p>
                
                <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-dark">
                    Découvrir la collection →
                </a>
            </div>
        @else
            {{-- Liste des commandes --}}
            
            <div style="max-width: 800px;">
                @foreach($orders as $order)
                    <div style="border: 1px solid #E5E5E5; padding: 1.5rem; margin-bottom: 1rem;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <p style="font-weight: 600; font-size: 1.1rem;">Commande #{{ $order->numero_commande }}</p>
                                
                                <p style="font-size: 0.85rem; color: #999;">{{ $order->created_at->format('d/m/Y') }}</p>
                            </div>
                            
                            <div style="text-align: right;">
                                @php
                                    $statusLabel = match($order->statut) {
                                        'en_attente' => 'En attente',
                                        'en_preparation' => 'En préparation',
                                        'prete' => 'Prête',
                                        'livree' => 'Livrée',
                                        'annulee' => 'Annulée',
                                        'paid' => 'Payée',
                                        default => $order->statut,
                                    };
                                    $statusColor = match($order->statut) {
                                        'en_attente' => '#CC9900',
                                        'en_preparation' => '#0066CC',
                                        'prete' => '#00AA44',
                                        'livree' => '#666',
                                        'annulee' => '#CC0000',
                                        'paid' => '#1A1A1A',
                                        default => '#999',
                                    };
                                @endphp
                                
                                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $statusColor }};">
                                    {{ $statusLabel }}
                                </span>
                                
                                <p style="font-weight: 600; margin-top: 0.25rem;">€ {{ formatPrice($order->total) }}</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #F0F0F0;">
                            <p style="font-size: 0.85rem; color: #666;">{{ $order->items->count() }} article(s)</p>
                            
                            <button onclick="document.getElementById('order-details-{{ $order->id }}').classList.toggle('hidden')"
                                    style="background: none; border: none; cursor: pointer; font-size: 0.85rem; text-decoration: underline; color: #666;"
                            >
                                Détails
                            </button>
                        </div>
                        
                        {{-- Détails cachés --}}
                        
                        <div id="order-details-{{ $order->id }}" class="hidden" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E5E5E5;">
                            
                            <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Articles</p>
                            
                            <div style="space-y: 1rem;">
                                @foreach($order->items as $item)
                                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #F5F5F5;">
                                        <div>
                                            <p style="font-size: 0.9rem;">{{ $item->titre_vinyle ?? 'Article' }}</p>
                                            
                                            <p style="font-size: 0.8rem; color: #999;">Qté: {{ $item->quantite }}</p>
                                        </div>
                                        
                                        <p style="font-size: 0.9rem;">€ {{ formatPrice($item->total) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem;">
                                <p style="font-weight: 600;">Total</p>
                                
                                <p style="font-weight: 600;">€ {{ formatPrice($order->total) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                {{-- Pagination --}}
                <div style="margin-top: 2rem;">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

@endsection