@php
    /** @var \App\Services\CartService $cartService */
    $cartService = app(\App\Services\CartService::class);
    $cart = $cartService->getCart();
    $cartCount = $cart->items->sum('quantite');
@endphp

@extends('layouts.kiosque')

@section('title', $vinyle->nom_complet . ' - Vinyle Hydrodécoupé')

@section('content')
<div class="min-h-screen bg-gray-900">
    {{-- Header avec retour et panier --}}
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('kiosque.index') }}" class="flex items-center gap-2 text-gray-400 hover:text-purple-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Retour au catalogue</span>
            </a>
            
            <a href="{{ route('cart.index') }}" class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="bg-purple-600 px-2 py-0.5 rounded-full text-sm">{{ $cartCount }}</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Section Image --}}
            <div class="space-y-4">
                <div class="bg-gray-800 rounded-3xl overflow-hidden border border-gray-700 shadow-2xl">
                    @if($vinyle->getMedia('photo')->count() > 0)
                        <div class="aspect-square relative">
                            <img id="mainImage" 
                                 src="{{ $vinyle->getFirstMediaUrl('photo', 'medium') }}" 
                                 alt="{{ $vinyle->nom_complet }}"
                                 class="w-full h-full object-cover">
                            
                            {{-- Badge Stock --}}
                            <div class="absolute top-4 right-4">
                                @if($vinyle->quantite <= 0)
                                    <span class="bg-red-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-semibold">
                                        Rupture de stock
                                    </span>
                                @elseif($vinyle->isLowStock())
                                    <span class="bg-orange-500/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-semibold">
                                        Stock faible: {{ $vinyle->quantite }} restant(s)
                                    </span>
                                @else
                                    <span class="bg-green-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl font-semibold">
                                        En stock: {{ $vinyle->quantite }} disponible(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Galerie miniatures --}}
                        @if($vinyle->getMedia('photo')->count() > 1)
                            <div class="p-4 bg-gray-800 border-t border-gray-700">
                                <div class="flex gap-3 overflow-x-auto pb-2">
                                    @foreach($vinyle->getMedia('photo') as $media)
                                        <button type="button" 
                                                onclick="document.getElementById('mainImage').src='{{ $media->getUrl('medium') }}'"
                                                class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition hover:border-purple-500 {{ $loop->first ? 'border-purple-500' : 'border-gray-600' }}">
                                            <img src="{{ $media->getUrl('thumb') }}" 
                                                 alt="Image {{ $loop->iteration }}"
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="aspect-square bg-gray-700 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500">Aucune image disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section Informations --}}
            <div class="space-y-6">
                {{-- Titre et Artiste --}}
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
                        {{ $vinyle->artiste }}
                    </h1>
                    @if($vinyle->modele)
                        <p class="text-2xl text-gray-400">{{ $vinyle->modele }}</p>
                    @endif
                </div>

                {{-- Référence --}}
                <div class="text-sm text-gray-500">
                    Référence: <span class="font-mono text-gray-400">{{ $vinyle->reference }}</span>
                </div>

                {{-- Tags Genre/Style --}}
                @if($vinyle->genre || $vinyle->style)
                    <div class="flex flex-wrap gap-2">
                        @if($vinyle->genre)
                            <span class="bg-purple-600/20 text-purple-400 border border-purple-500/30 px-3 py-1 rounded-full text-sm">
                                {{ $vinyle->genre }}
                            </span>
                        @endif
                        @if($vinyle->style)
                            <span class="bg-pink-600/20 text-pink-400 border border-pink-500/30 px-3 py-1 rounded-full text-sm">
                                {{ $vinyle->style }}
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Prix --}}
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            {{ number_format($vinyle->prix, 2) }} €
                        </span>
                        <span class="text-gray-500">TTC</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Prix unitaire hors options de fond</p>
                </div>

                {{-- État du vinyle --}}
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-200 mb-4">État du produit</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $vinyle->quantite > 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
                            <span class="text-gray-300">
                                @if($vinyle->quantite > 0)
                                    Disponible - Expédition sous 24-48h
                                @else
                                    Indisponible - Réapprovisionnement en cours
                                @endif
                            </span>
                        </div>
                        
                        @if($vinyle->quantite > 0 && $vinyle->quantite <= 3)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-orange-400 text-sm">
                                    Stock limité - Plus que {{ $vinyle->quantite }} exemplaire(s)
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Formulaire Ajouter au panier --}}
                @if($vinyle->quantite > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="vinyle_id" value="{{ $vinyle->id }}">

                        {{-- Sélection Fond --}}
                        <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4">Choisir votre fond</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition bg-gray-700/50 border-purple-500">
                                    <input type="radio" name="fond" value="standard" checked 
                                           class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-200">Fond Standard</div>
                                        <div class="text-sm text-gray-400">Design original inclus</div>
                                    </div>
                                    <div class="text-gray-300 font-semibold">Inclus</div>
                                </label>
                                
                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition border-gray-600 hover:border-purple-500/50">
                                    <input type="radio" name="fond" value="miroir" 
                                           class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-200">Fond Miroir</div>
                                        <div class="text-sm text-gray-400">Effet réfléchissant élégant</div>
                                    </div>
                                    <div class="text-purple-400 font-semibold">+8 €</div>
                                </label>
                                
                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition border-gray-600 hover:border-purple-500/50">
                                    <input type="radio" name="fond" value="dore" 
                                           class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-200">Fond Doré</div>
                                        <div class="text-sm text-gray-400">Finition premium luxe</div>
                                    </div>
                                    <div class="text-purple-400 font-semibold">+13 €</div>
                                </label>
                            </div>
                        </div>

                        {{-- Quantité et Bouton --}}
                        <div class="flex gap-4">
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Quantité</label>
                                <select name="quantite" 
                                        class="w-full bg-gray-700 border border-gray-600 rounded-xl px-4 py-4 text-gray-100 focus:outline-none focus:border-purple-500">
                                    @for($i = 1; $i <= min($vinyle->quantite, 5); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-300 mb-2 opacity-0">Action</label>
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-4 px-6 rounded-xl transition flex items-center justify-center gap-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    {{-- Bouton indisponible --}}
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                        <button disabled 
                                class="w-full bg-gray-700 text-gray-500 font-semibold py-4 px-6 rounded-xl cursor-not-allowed flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Indisponible - Rupture de stock
                        </button>
                        
                        <p class="text-center text-gray-400 mt-4">
                            Ce produit est actuellement indisponible. 
                            <a href="{{ route('contact') }}" class="text-purple-400 hover:text-purple-300 underline">Contactez-nous</a> 
                            pour être informé de sa disponibilité.
                        </p>
                    </div>
                @endif

            @if($reviews->count() > 0)
                {{-- Section Avis Clients --}}
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-200 mb-6">Avis clients</h2>
                    
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-gray-200 font-medium">{{ $review->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-300">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

                {{-- Formulaire Avis (Utilisateur connecté) --}}
                @auth
                    @if(!$userHasReviewed)
                        <div class="mt-8 bg-gray-800 rounded-2xl p-6 border border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-200 mb-4">Laisser un avis</h3>
                            
                            <form action="{{ route('reviews.store', $vinyle) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Note</label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" required class="hidden peer">
                                                <svg class="w-8 h-8 text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-400 transition star-rating" data-value="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Commentaire (optionnel)</label>
                                    <textarea name="comment" rows="3" maxlength="1000"
                                              class="w-full bg-gray-700 border border-gray-600 rounded-xl px-4 py-3 text-gray-100 focus:outline-none focus:border-purple-500"
                                              placeholder="Partagez votre expérience...">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" 
                                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-xl transition">
                                    Soumettre mon avis
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-8 bg-gray-800/50 rounded-2xl p-6 border border-gray-700">
                            <p class="text-gray-400 text-center">Vous avez déjà laissé un avis pour ce vinyle.</p>
                        </div>
                    @endif
                @else
                    <div class="mt-8 bg-gray-800/50 rounded-2xl p-6 border border-gray-700">
                        <p class="text-gray-400 text-center">
                            <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 underline">Connectez-vous</a> 
                            pour laisser un avis.
                        </p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection