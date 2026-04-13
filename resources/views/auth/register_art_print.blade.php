{{-- resources/views/auth/register-art-print.blade.php --}}
{{-- Register ART PRINT - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Inscription')

@section('content')

<!-- Hero -->
<section class="ap-hero" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="ap-container" style="max-width: 500px; margin: 0 auto;">
        
        <p class="ap-hero-label">Nouveau membre</p>
        
        <h1 style="margin-bottom: 3rem;">Créer un compte</h1>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            
            @if(request('theme'))
                <input type="hidden" name="theme" value="{{ request('theme') }}">
            @endif
            
            {{-- Nom --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Nom complet</label>
                
                <input type="text" name="name" required autofocus
                       value="{{ old('name') }}"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="Votre nom">
                
                @if ($errors->has('name'))
                    <p style="color: #c00; font-size: 0.8rem; margin-top: 0.5rem;">{{ $errors->first('name') }}</p>
                @endif
            </div>
            
            {{-- Email --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Email</label>
                
                <input type="email" name="email" required
                       value="{{ old('email') }}"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="votre@email.com">
                
                @if ($errors->has('email'))
                    <p style="color: #c00; font-size: 0.8rem; margin-top: 0.5rem;">{{ $errors->first('email') }}</p>
                @endif
            </div>
            
            {{-- Password --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Mot de passe</label>
                
                <input type="password" name="password" required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="8 caractères minimum">
                
                @if ($errors->has('password'))
                    <p style="color: #c00; font-size: 0.8rem; margin-top: 0.5rem;">{{ $errors->first('password') }}</p>
                @endif
            </div>
            
            {{-- Confirm Password --}}
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Confirmer le mot de passe</label>
                
                <input type="password" name="password_confirmation" required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="••••••••">
            </div>
            
            {{-- Submit --}}
            <button type="submit" class="ap-btn ap-btn-dark" style="width: 100%; padding: 1rem;">
                Créer mon compte →
            </button>
        </form>
        
        {{-- Login link --}}
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e5e5;">
            <p style="font-size: 0.9rem; color: #666;">
                Déjà inscrit ? 
                <a  href="{{ route('login') }}" style="text-decoration: underline; color: inherit;">Se connecter</a>
            </p>
        </div>
    </div>
</section>

@endsection
