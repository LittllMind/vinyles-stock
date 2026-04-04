@extends('layouts.app')

@section('title', 'Mes Favoris - Fundisc')

@section('content')
<div class="min-h-screen bg-gray-900 pt-20 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">❤️ Mes Favoris</h1>
            <p class="text-gray-400">Vos vinyles préférés, sauvegardés pour plus tard.</p>
        </div>

        <!-- Message si liste vide -->
        @if($wishlist->isEmpty())
            <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-12 text-center">
                <div class="text-6xl mb-4">💔</div>
                <h2 class="text-xl font-semibold text-white mb-2">Aucun favori</h2>
                <p class="text-gray-400 mb-6">Vous n'avez encore aucun vinyle dans vos favoris.</p>
                <a href="{{ route('kiosque.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-medium transition">
                    🎵 Découvrir le catalogue
                </a>
            </div>
        @else
            <!-- Grid des favoris -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlist as $item)
                    <div class="bg-gray-800/50 border border-gray-700 rounded-xl overflow-hidden hover:border-purple-500/50 transition group">
                        <!-- Image -->
                        <div class="relative aspect-square overflow-hidden">
                            <img src="{{ $item->vinyle->image }}" 
                                 alt="{{ $item->vinyle->nom_complet }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <!-- Bouton retirer -->
                            <form action="{{ route('wishlist.remove', $item->vinyle) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500/80 hover:bg-red-600 text-white p-2 rounded-full transition shadow-lg"
                                        title="Retirer des favoris">
                                    ❌
                                </button>
                            </form>
                        </div>
                        
                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="text-white font-semibold mb-1 truncate" title="{{ $item->vinyle->artiste }}">
                                {{ $item->vinyle->artiste }}
                            </h3>
                            <p class="text-gray-400 text-sm mb-2 truncate" title="{{ $item->vinyle->modele }}">
                                {{ $item->vinyle->modele ?: 'Sans titre' }}
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-pink-400 font-bold text-lg">{{ number_format($item->vinyle->prix, 2) }} €</span>
                                
                                @if($item->vinyle->isOutOfStock())
                                    <span class="text-red-400 text-sm">Rupture</span>
                                @elseif($item->vinyle->isLowStock())
                                    <span class="text-yellow-400 text-sm">Stock faible</span>
                                @else
                                    <span class="text-green-400 text-sm">En stock</span>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="space-y-2">
                                @if(!$item->vinyle->isOutOfStock())
                                    <form action="{{ route('wishlist.to-cart', $item->vinyle) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center space-x-2">
                                            <span>🛒</span>
                                            <span>Ajouter au panier</span>
                                        </button>
                                    </form>
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-700 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed">
                                        Indisponible
                                    </button>
                                @endif
                                
                                <a href="{{ route('kiosque.vinyle.show', $item->vinyle) }}" 
                                   class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition">
                                    Voir le détail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
