{{-- resources/views/addresses/edit-art-print.blade.php --}}
{{-- Édition adresse - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Modifier l\'adresse')

@section('content')

<section class="ap-section" style="padding-top: 6rem;">
    <div class="ap-container" style="max-width: 600px;">
        
        <p class="ap-hero-label">Livraison</p>
        
        <h1 style="margin-bottom: 3rem;">Modifier l'adresse</h1>
        
        <form action="{{ route('addresses.update', $address) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="theme" value="art-print">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Libellé</label>
                
                <input type="text" name="label" value="{{ old('label', $address->label) }}" placeholder="ex: Maison, Bureau..." required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                @error('label')
                    <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Nom complet</label>
                
                <input type="text" name="nom" value="{{ old('nom', $address->nom) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                @error('nom')
                    <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Adresse</label>
                
                <input type="text" name="adresse" value="{{ old('adresse', $address->adresse) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                @error('adresse')
                    <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1.5rem;">
                
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Code postal</label>
                    
                    <input type="text" name="code_postal" value="{{ old('code_postal', $address->code_postal) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                    @error('code_postal')
                        <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Ville</label>
                    
                    <input type="text" name="ville" value="{{ old('ville', $address->ville) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                    @error('ville')
                        <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Pays</label>
                
                <select name="pays" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; background: white; box-sizing: border-box;">
                    <option value="FR" {{ old('pays', $address->pays) == 'FR' ? 'selected' : '' }}>France</option>
                    <option value="BE" {{ old('pays', $address->pays) == 'BE' ? 'selected' : '' }}>Belgique</option>
                    <option value="CH" {{ old('pays', $address->pays) == 'CH' ? 'selected' : '' }}>Suisse</option>
                    <option value="LU" {{ old('pays', $address->pays) == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Téléphone</label>
                
                <input type="tel" name="telephone" value="{{ old('telephone', $address->telephone) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                @error('telephone')
                    <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Email</label>
                
                <input type="email" name="email" value="{{ old('email', $address->email) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box;">
                @error('email')
                    <p style="color: #CC0000; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Instructions de livraison (optionnel)</label>
                
                <textarea name="instructions" rows="3" placeholder="Code porte, étage, etc." style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; font-size: 1rem; box-sizing: border-box; resize: vertical;">{{ old('instructions', $address->instructions) }}</textarea>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                    <span style="font-size: 0.9rem;">Définir comme adresse par défaut</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="ap-btn ap-btn-dark">
                    Enregistrer →
                </button>
                
                <a  href="{{ route('addresses.index') }}" style="align-self: center; font-size: 0.9rem; text-decoration: underline; color: #666;">Annuler</a>
            </div>
        </form>
    </div>
</section>

@endsection