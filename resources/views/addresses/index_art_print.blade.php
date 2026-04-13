{{-- resources/views/addresses/index-art-print.blade.php --}}
{{-- Liste des adresses - Style minimaliste --}}

@extends('components.art_print.ap-layout')

@section('title', 'Mes adresses')

@section('content')

<section class="ap-section" style="padding-top: 6rem;">
    <div class="ap-container">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <p class="ap-hero-label">Livraison</p>
            <a  href="{{ route('addresses.create') }}" class="ap-btn ap-btn-dark">
                + Nouvelle adresse
            </a>
        </div>
        
        <h1 style="margin-bottom: 3rem;">Mes adresses</h1>
        
        <!-- Messages flash -->
        @if(session('success'))
            <div style="margin-bottom: 2rem; padding: 1rem; border: 1px solid #22C55E; background: #F0FFF4;">
                <p style="margin: 0; font-size: 0.9rem;">{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div style="margin-bottom: 2rem; padding: 1rem; border: 1px solid #EF4444; background: #FFF5F5;">
                <p style="margin: 0; font-size: 0.9rem;">{{ session('error') }}</p>
            </div>
        @endif
        
        <!-- Liste des adresses -->
        @if($addresses->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
                @foreach($addresses as $address)
                    <div style="border: 1px solid #E5E5E5; padding: 1.5rem; {{ $address->is_default ? 'border-color: #1A1A1A;' : '' }}">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <p style="font-weight: 600;">{{ $address->label }}</p>
                                    @if($address->is_default)
                                        <span style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; background: #1A1A1A; color: white; padding: 0.2rem 0.5rem;">Par défaut</span>
                                    @endif
                                </div>
                                
                                <p style="font-weight: 500;">{{ $address->nom }}</p>
                            </div>
                            
                            <div style="display: flex; gap: 0.5rem;">
                                <a  href="{{ route('addresses.edit', $address) }}" style="font-size: 0.75rem; text-decoration: underline; color: #666;">Modifier</a>
                                
                                @if(!$address->is_default)
                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Supprimer cette adresse ?')" style="font-size: 0.75rem; text-decoration: underline; color: #999; background: none; border: none; cursor: pointer;">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <div style="color: #666; font-size: 0.9rem; line-height: 1.6;">
                            <p>{{ $address->adresse }}</p>
                            <p>{{ $address->code_postal }} {{ $address->ville }}</p>
                            <p style="text-transform: uppercase;">{{ $address->pays }}</p>
                        </div>
                        
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #F0F0F0; font-size: 0.85rem; color: #999;">
                            <p>{{ $address->telephone }}</p>
                            <p>{{ $address->email }}</p>
                        </div>
                        
                        @if($address->instructions)
                            <div style="margin-top: 1rem; padding: 0.75rem; background: #F5F5F5; font-size: 0.8rem;">
                                {{ $address->instructions }}
                            </div>
                        @endif
                        
                        @if(!$address->is_default)
                            <form action="{{ route('addresses.setDefault', $address) }}" method="POST" style="margin-top: 1rem;">
                                @csrf
                                <button type="submit" style="width: 100%; padding: 0.75rem; border: 1px solid #E5E5E5; background: none; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#F5F5F5'" onmouseout="this.style.background='none'">
                                    Définir comme par défaut
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem; border: 1px solid #E5E5E5;">
                <p style="color: #666; margin-bottom: 1.5rem;">Aucune adresse enregistrée</p>
                <a  href="{{ route('addresses.create') }}" class="ap-btn ap-btn-dark">
                    + Ajouter une adresse
                </a>
            </div>
        @endif
        
    </div>
</section>

@endsection