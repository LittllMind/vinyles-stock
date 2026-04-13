{{-- resources/views/auth/login-art-print.blade.php --}}
{{-- Login ART PRINT - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Connexion')

@section('content')

<!-- Hero -->
<section class="ap-hero" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="ap-container" style="max-width: 500px; margin: 0 auto;">
        
        <p class="ap-hero-label">Accès membre</p>
        
        <h1 style="margin-bottom: 3rem;"
>Connexion</h1>

        @if (session('status'))
            <div style="background: #F8F8F8; border: 1px solid #e5e5e5; padding: 1rem; margin-bottom: 2rem; text-align: center;">
                {{ session('status') }}
            </div>
        @endif
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">

            @if(request('theme'))
                <input type="hidden" name="theme" value="{{ request('theme') }}">
            @endif
            
            {{-- Email --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Email</label>
                
                <input type="email" name="email" required autofocus
                       value="{{ old('email') }}"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="votre@email.com">
                
                @if ($errors->has('email'))
                    <p style="color: #c00; font-size: 0.8rem; margin-top: 0.5rem;">{{ $errors->first('email') }}</p>
                @endif
            </div>
            
            {{-- Password --}}
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;"
                >Mot de passe</label>
                
                <input type="password" name="password" required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                       placeholder="••••••••">
                
                @if ($errors->has('password'))
                    <p style="color: #c00; font-size: 0.8rem; margin-top: 0.5rem;">{{ $errors->first('password') }}</p>
                @endif
            </div>
            
            {{-- Remember Me + Forgot Password --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem;"
                >
                    <input type="checkbox" name="remember">
                    Se souvenir de moi
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #666; text-decoration: underline;"
                    >
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            
            {{-- Submit --}}
            <button type="submit" class="ap-btn ap-btn-dark" style="width: 100%; padding: 1rem;">
                Se connecter →
            </button>
        </form>
        
        {{-- Register link --}}
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e5e5;">
            <p style="font-size: 0.9rem; color: #666;">
                Pas encore de compte ? 
                <a  href="{{ route('register') }}" style="text-decoration: underline; color: inherit;">Créer un compte</a>
            </p>
        </div>
    </div>
</section>

@endsection