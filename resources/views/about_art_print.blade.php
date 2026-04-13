{{-- resources/views/about-art-print.blade.php --}}
{{-- Page À propos - Style minimaliste galerie --}}

@extends('components.art_print.ap-layout')

@section('title', 'Le Concept')

@section('content')

<section class="ap-section ap-hero" style="padding-top: 3rem;">
    <div class="ap-container">
        
        {{-- Hero --}}
        <p class="ap-hero-label">À propos</p>
        
        <h1 style="max-width: 700px; margin-bottom: 6rem;">
            Chaque vinyle raconte une histoire
        </h1>
        
        <!-- Sections -->
        <div style="max-width: 700px;">
            
            {{-- Notre savoir-faire --}}
            <div style="margin-bottom: 5rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Savoir-faire</p>
                
                <p style="font-size: 1.25rem; line-height: 1.7; color: #333;">
                    Nous sélectionnons chaque vinyle pour son potentiel artistique et son histoire musicale.
                    Chaque pièce est unique et transformée avec soin pour devenir un objet de décoration contemporain.
                </p>
            </div>
            
            {{-- Pourquoi le vinyle --}}
            <div style="margin-bottom: 5rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 1rem;">Philosophie</p>
                
                <p style="font-size: 1.25rem; line-height: 1.7; color: #333;">
                    Le vinyle n'est pas seulement un support musical, c'est un objet chargé d'émotion et de nostalgie.
                    Nous donnons une seconde vie à ces disques, créant des pièces uniques qui préservent l'âme de la musique qu'ils contenaient.
                </p>
            </div>
            
            {{-- Comment acheter --}}
            <div style="margin-bottom: 5rem;">
                <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 2rem;">Processus</p>
                
                <div style="display: grid; gap: 2rem;">
                    
                    <div>
                        <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                            <span style="width: 2.5rem; height: 2.5rem; border: 1px solid #1A1A1A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 600;">01</span>
                            
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.5rem;">Explorez le catalogue</p>
                                
                                <p style="color: #666;">Parcourez notre collection de vinyles uniques disponibles.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                            <span style="width: 2.5rem; height: 2.5rem; border: 1px solid #1A1A1A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 600;">02</span>
                            
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.5rem;">Créez votre compte</p>
                                
                                <p style="color: #666;">Inscrivez-vous pour stocker vos adresses et suivre vos commandes.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                            <span style="width: 2.5rem; height: 2.5rem; border: 1px solid #1A1A1A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 600;">03</span>
                            
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.5rem;">Commandez en ligne</p>
                                
                                <p style="color: #666;">Ajoutez vos pièces favorites au panier et finalisez votre achat en toute sécurité.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 6rem 0; border-top: 1px solid #E5E5E5;">
    <div class="ap-container" style="text-align: center;">
        
        <a  href="{{ route('kiosque.index') }}" class="ap-btn ap-btn-dark">
            Découvrir la collection →
        </a>
    </div>
</section>

@endsection