{{-- resources/views/contact-art-print.blade.php --}}
{{-- Page Contact - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Contact')

@section('content')

<section class="ap-section ap-hero" style="padding-top: 3rem;">
    <div class="ap-container">
        
        <p class="ap-hero-label">Contact</p>
        
        <h1 style="margin-bottom: 6rem;">
            Une question ?
        </h1>
        
        <div style="max-width: 500px;">
            
            <div style="margin-bottom: 3rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Email</p>
                
                <a href="mailto:contact@fundisc.fr" style="font-size: 1.25rem; text-decoration: none; border-bottom: 1px solid #1A1A1A;">
                    contact@fundisc.fr
                </a>
            </div>
            
            <div>
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Horaires</p>
                
                <p style="font-size: 1rem; color: #333;">
                    Lundi - Vendredi : 9h - 18h
                </p>
            </div>
        </div>
    </div>
</section>

<section style="padding: 6rem 0; border-top: 1px solid #E5E5E5;">
    <div class="ap-container" style="text-align: center;">
        
        <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-dark">
            Retour au catalogue →
        </a>
    </div>
</section>

@endsection