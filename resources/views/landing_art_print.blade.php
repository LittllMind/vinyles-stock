@extends('layouts.art-print')

@section('title', 'Accueil')
@section('meta_description', 'FUN DISC - Vinyles découpés en œuvres d\'art uniques. Découvrez notre collection de disques vinyles transformés en pièces de décoration contemporaine.')
@section('og_title', 'FUN DISC - Vinyles découpés en œuvres d\'art')
@section('og_description', 'Chaque disque devient une œuvre unique. Découvrez notre collection de vinyles découpés.')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
@endphp

@section('content')

{{-- Hero Section --}}
<div class="ap-hero" style="background-color: #1a1a1a; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; padding: 2rem;">
        {{-- Grand FUN DISC en typographie --}}
        <h1 style="font-size: clamp(4rem, 15vw, 12rem); font-weight: 900; color: #f8f7f2; line-height: 0.9; letter-spacing: -0.02em; margin: 0;">
            FUN
        </h1>
        <h1 style="font-size: clamp(4rem, 15vw, 12rem); font-weight: 900; color: #f8f7f2; line-height: 0.9; letter-spacing: -0.02em; margin: 0;">
            DISC
        </h1>
        
        <p style="font-size: 1rem; color: #b8a77d; text-transform: uppercase; letter-spacing: 0.3em; margin-top: 2rem;">
            Vinyles découpés
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 3rem;">
            <a href="{{ route('kiosque.index') }}" class="ap-btn" style="background: #f8f7f2; color: #1a1a1a; border: none; min-width: 12rem;">
                Découvrir
            </a>
            
            <a href="{{ auth()->check() ? route('orders.index') : route('login') }}" class="ap-btn" style="background: transparent; color: #f8f7f2; border: 1px solid #f8f7f2; min-width: 12rem;">
                {{ auth()->check() ? 'Mes commandes' : 'Connexion' }}
            </a>
        </div>
    </div>
</div>

{{-- Featured Section --}}
<div style="max-width: 80rem; margin: 0 auto; padding: 4rem 1.5rem;">
    
    <div style="text-align: center; margin-bottom: 3rem;">
        <h2 style="font-size: 1.75rem; font-weight: 400; color: #1a1a1a; margin-bottom: 0.75rem;">
            En vedette
        </h2>
        
        <p style="color: #666;">
            {{ $stats['total'] }} vinyles disponibles
        </p>
    </div>
    
    @if($featured->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            @foreach($featured as $vinyle)
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
                            <span class="ap-card-year" style="color: #999; font-size: 0.85rem;">{{ $vinyle->genre ?? 'Vinyle' }}</span>
                        </div>                        
                        
                        <p class="ap-card-artist" style="padding: 0 1.5rem; margin: 0.5rem 0 0; color: #666; min-height: 2.5em;">{{ $vinyle->modele }}</p>
                    </a>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 1.5rem 1.5rem; margin-top: 1rem;">
                        <p class="ap-card-price" style="font-size: 1rem; font-weight: 600;">€ {{ formatPrice($vinyle->prix) }}</p>
                        
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
        
        <div style="text-align: center;">
            <a href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-outline" style="min-width: 16rem;">
                Voir tous les vinyles ({{ $stats['total'] }})
            </a>
        </div>
        
    @else
        <div style="text-align: center; padding: 4rem 0; max-width: 500px; margin: 0 auto;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🔜</div>
            <h3 style="font-size: 1.5rem; font-weight: 500; color: #1a1a1a; margin-bottom: 1rem;">
                Nouvelle collection en préparation
            </h3>
            <p style="color: #666; margin-bottom: 2rem; line-height: 1.6;">
                Nos vinyles découpés sont en cours de fabrication. 
                Soyez les premiers informés de la mise en ligne.
    {{-- Formulaire d'alerte stock - envoi direct via contact --}}
            <form action="{{ route('contact.store') }}" method="POST" style="margin-bottom: 2rem;">
                @csrf
                <input type="hidden" name="subject" value="Alerte stock FUN DISC">
                <input type="hidden" name="message" value="Je souhaite être alerté(e) quand les vinyles seront de nouveau disponibles sur fundisc.fr">
                <input type="hidden" name="return_to" value="landing">
                
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
                    <input type="email" name="email" placeholder="votre@email.com" required
                        style="padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 4px; min-width: 250px; font-size: 1rem;"
                        aria-label="Adresse email pour alerte stock">
                    
                    <button type="submit" class="ap-btn" style="background: #1a1a1a; color: #f8f7f2; border: none;">
                        M'alerter
                    </button>
                </div>
                @if(session('success'))
                    <p style="color: #4ade80; font-size: 0.875rem; margin-top: 0.75rem;">
                        {{ session('success') }}
                    </p>
                @endif
            </form>
            
            <p style="font-size: 0.875rem; color: #999;">
                Suivez-nous sur Instagram pour les coulisses de la fabrication
            </p>
            <a href="https://instagram.com/fundisc" target="_blank" rel="noopener"
                style="display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem; color: #1a1a1a; text-decoration: none; font-weight: 500;">
                <span>📷 @fundisc</span>
            </a>
        </div>
    @endif
    
</div>

@endsection