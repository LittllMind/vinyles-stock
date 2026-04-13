{{-- resources/views/orders/create-art-print.blade.php --}}
{{-- Checkout ART PRINT - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Livraison')

@section('content')

@php
if (!function_exists('formatPrice')) {
    function formatPrice($cents) {
        return number_format($cents / 100, 2, ',', ' ');
    }
}
$total = 0;
foreach ($cart->items as $item) {
    $total += $item->quantite * $item->prix_unitaire;
}
@endphp

<!-- Hero Étapes -->
<section class="ap-hero" style="min-height: auto; padding-top: 8rem; padding-bottom: 2rem;">
    <div class="ap-container">
        <p class="ap-hero-label">Étape 2 sur 3</p>
        <h1>Livraison</h1>
        
        <!-- Progression -->
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 2rem; max-width: 400px;">
            <div style="flex: 1; text-align: center;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #1A1A1A; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 0.8rem;">✓</div>
                <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666;">Panier</span>
            </div>
            
            <div style="flex: 0 0 40px; height: 1px; background: #1A1A1A;"></div>
            
            <div style="flex: 1; text-align: center;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #1A1A1A; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 0.8rem; font-weight: 500;">2</div>
                <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em;">Livraison</span>
            </div>
            
            <div style="flex: 0 0 40px; height: 1px; background: #ccc;"></div>
            
            <div style="flex: 1; text-align: center;">
                <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 0.8rem; color: #999;">3</div>
                <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #999;">Paiement</span>
            </div>
        </div>
    </div>
</section>

<!-- Formulaire -->
<section class="ap-section" style="padding-top: 2rem; padding-bottom: 6rem;">
    <div class="ap-container">
        
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 4rem;">
            
            {{-- Formulaire --}}
            <div>
                
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <input type="hidden" name="theme" value="art-print">
                    
                    {{-- Adresses enregistrées --}}
                    @if(Auth::check() && $addresses->count() > 0)
                        <div style="margin-bottom: 3rem; padding: 1.5rem; background: #FAFAFA; border: 1px solid #e5e5e5;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.75rem;">
                                Utiliser une adresse enregistrée
                            </label>
                            
                            <select name="address_id" id="address_select"
                                    onchange="fillAddress(this.value)"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; background: #fff; font-size: 0.9rem;">
                                <option value="">-- Nouvelle adresse --</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}
                                            data-nom="{{ $address->nom }}"
                                            data-email="{{ $address->email }}"
                                            data-telephone="{{ $address->telephone }}"
                                            data-adresse="{{ $address->adresse }}"
                                            data-code_postal="{{ $address->code_postal }}"
                                            data-ville="{{ $address->ville }}"
                                            data-pays="{{ $address->pays }}">
                                        {{ $address->label }} — {{ $address->ville }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    {{-- Contact --}}
                    <h2 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem;">Contact</h2>
                    
                    <div style="margin-bottom: 2rem;">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Noms <span style="color: #999;">*</span></label>
                            
                            <input type="text" name="nom" id="nom" required
                                   value="{{ old('nom', $tempShipping['nom'] ?? '') }}"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                                   placeholder="Nom et prénom">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Email <span style="color: #999;">*</span></label>
                                
                                <input type="email" name="email" id="email" required
                                       value="{{ old('email', $tempShipping['email'] ?? '') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;">
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Téléphone <span style="color: #999;">*</span></label>
                                
                                <input type="tel" name="telephone" id="telephone" required
                                       value="{{ old('telephone', $tempShipping['telephone'] ?? '') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Adresse --}}
                    <h2 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem;">Adresse de livraison</h2>
                    
                    <div style="margin-bottom: 2rem;">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Adresse <span style="color: #999;">*</span></label>
                            
                            <input type="text" name="adresse" id="adresse" required
                                   value="{{ old('adresse', $tempShipping['adresse'] ?? '') }}"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;"
                                   placeholder="N°, rue, appartement...">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 100px 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Code postal <span style="color: #999;">*</span></label>
                                
                                <input type="text" name="code_postal" id="code_postal" required
                                       value="{{ old('code_postal', $tempShipping['code_postal'] ?? '') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;">
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Ville <span style="color: #999;">*</span></label>
                                
                                <input type="text" name="ville" id="ville" required
                                       value="{{ old('ville', $tempShipping['ville'] ?? '') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem;">
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Pays <span style="color: #999;">*</span></label>
                            
                            <select name="pays" id="pays" required
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem; background: #fff;">
                                <option value="FR" {{ (old('pays', $tempShipping['pays'] ?? 'FR')) == 'FR' ? 'selected' : '' }}>France métropolitaine</option>
                                <option value="BE" {{ (old('pays', $tempShipping['pays'] ?? '')) == 'BE' ? 'selected' : '' }}>Belgique</option>
                                <option value="CH" {{ (old('pays', $tempShipping['pays'] ?? '')) == 'CH' ? 'selected' : '' }}>Suisse</option>
                                <option value="LU" {{ (old('pays', $tempShipping['pays'] ?? '')) == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 0.5rem;">Instructions de livraison (optionnel)</label>
                            
                            <textarea name="instructions" id="instructions" rows="3"
                                      style="width: 100%; padding: 0.75rem; border: 1px solid #e5e5e5; font-size: 0.9rem; resize: vertical;"
                                      placeholder="Code d'entrée, étage, etc.">{{ old('instructions', $tempShipping['instructions'] ?? '') }}</textarea>
                        </div>
                    </div>
                    
                    {{-- Sauvegarder adresse --}}
                    @if(Auth::check())
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem;">
                                <input type="checkbox" name="save_address" value="1" {{ old('save_address') ? 'checked' : '' }}>
                                Sauvegarder cette adresse
                            </label>
                        </div>
                        
                        <div style="margin-bottom: 3rem;">
                            <input type="text" name="address_label"
                                   value="{{ old('address_label', 'Maison') }}"
                                   placeholder="Label (Maison, Travail...)"
                                   style="width: 200px; padding: 0.5rem; border: 1px solid #e5e5e5; font-size: 0.85rem;">
                        </div>
                    @endif
                    
                    {{-- Boutons --}}
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <a  href="{{ route('cart.index') }} 
                          " style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: #666; text-decoration: none;">
                            ← Retour au panier
                        </a>
                        
                        <button type="submit" class="ap-btn ap-btn-dark" style="padding: 1rem 2rem;">
                            Continuer vers le paiement →
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Récapitulatif --}}
            <div>
                <div style="background: #FAFAFA; padding: 2rem; border: 1px solid #e5e5e5; position: sticky; top: 2rem;">
                    
                    <h2 style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e5e5;">
                        Récapitulatif
                    </h2>
                    
                    {{-- Articles --}}
                    <div style="margin-bottom: 1.5rem;">
                        @foreach($cart->items as $item)
                            <div style="display: flex; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid #e5e5e5;">
                                <div style="width: 50px; height: 50px; background: #F8F8F8; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    💿
                                </div>
                                
                                <div style="flex: 1;">
                                    <p style="font-size: 0.85rem; margin: 0;">{{ $item->vinyle->artiste }}</p>
                                    <p style="font-size: 0.75rem; color: #999; margin: 0;">{{ $item->vinyle->modele }} × {{ $item->quantite }}</p>
                                </div>
                                
                                <div style="text-align: right;">
                                    <p style="font-size: 0.85rem; margin: 0;">€ {{ formatPrice($item->prix_unitaire * $item->quantite) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Totaux --}}
                    <div style="border-top: 1px solid #e5e5e5; padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <span>Sous-total</span>
                            <span>€ {{ formatPrice($total) }}</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem; color: #666;">
                            <span>Livraison</span>
                            <span>À calculer</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e5e5; font-size: 1.1rem; font-weight: 500;">
                            <span>Total</span>
                            <span>€ {{ formatPrice($total) }}</span>
                        </div>
                        
                        <p style="font-size: 0.75rem; color: #999; margin-top: 0.5rem;">TTC • Hors frais de livraison</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function fillAddress(addressId) {
    if (!addressId) {
        // Reset si nouvelle adresse sélectionnée
        document.getElementById('nom').value = '';
        document.getElementById('email').value = '';
        document.getElementById('telephone').value = '';
        document.getElementById('adresse').value = '';
        document.getElementById('code_postal').value = '';
        document.getElementById('ville').value = '';
        return;
    }
    
    const select = document.getElementById('address_select');
    const option = select.options[select.selectedIndex];
    
    document.getElementById('nom').value = option.dataset.nom || '';
    document.getElementById('email').value = option.dataset.email || '';
    document.getElementById('telephone').value = option.dataset.telephone || '';
    document.getElementById('adresse').value = option.dataset.adresse || '';
    document.getElementById('code_postal').value = option.dataset.code_postal || '';
    document.getElementById('ville').value = option.dataset.ville || '';
    document.getElementById('pays').value = option.dataset.pays || 'FR';
}
</script>

@endsection