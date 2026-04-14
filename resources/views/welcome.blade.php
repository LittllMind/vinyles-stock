
@extends('layouts.public')

@section('title', 'Fundisc - Vinyles déco découpés artisanalement')

@section('content')<div class="relative overflow-hidden">
        <!-- Hero Section -->
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-purple-950 to-gray-900 py-20">
            <div class="container mx-auto px-4 text-center">
                <!-- Logo -->
                <div class="mb-8">
                    <div class="text-6xl mb-4">💿</div>
                    <h1 class="text-5xl sm:text-7xl font-bold mb-4">
                        <span class="bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                            Fundisc
                        </span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                        Vinyles recyclés en horloges murales uniques et décorations murales artisanales.
                        <br class="hidden sm:block">Chaque pièce est unique, choisie avec passion.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('kiosque.index') }}" 
                       class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition transform hover:scale-105 shadow-lg shadow-purple-500/25">
                        Découvrir la Collection
                    </a>
                    <a href="/about" 
                       class="bg-gray-800 hover:bg-gray-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition border border-gray-700">
                        Le Concept
                    </a>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-purple-500/30 transition">
                        <div class="text-4xl mb-4">♻️</div>
                        <h3 class="text-xl font-bold mb-2 text-white">Éco-responsable</h3>
                        <p class="text-gray-400">Vinyles recyclés, chaque pièce donnée une seconde vie</p>
                    </div>

                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-purple-500/30 transition">
                        <div class="text-4xl mb-4">✨</div>
                        <h3 class="text-xl font-bold mb-2 text-white">Unique</h3>
                        <p class="text-gray-400">Chaque article est découpé à la main, aucune pièce n'est identique</p>
                    </div>

                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700/50 hover:border-purple-500/30 transition">
                        <div class="text-4xl mb-4">🎵</div>
                        <h3 class="text-xl font-bold mb-2 text-white">Passion</h3>
                        <p class="text-gray-400">Sélection rigoureuse des vinyles pour leur histoire et leur beauté</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Section -->
        <div class="bg-gray-800/30 py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                        <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            Des pièces uniques pour votre intérieur
                        </span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                        Transformez vos vinyles préférés en véritables objets de décoration.
                        Horloges, porte-clés, tableaux originaux...
                    </p>
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('kiosque.index') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition inline-flex items-center gap-2">
                        Voir le catalogue
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-purple-900/50 to-pink-900/50 py-20">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Prêt à découvrir ?
                </h2>
                <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                    Parcourez notre collection et trouvez la pièce qui vous correspond.
                    <br>Livraison rapide et soignée dans toute la France.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('kiosque.index') }}" 
                           class="bg-white text-gray-900 hover:bg-gray-100 px-8 py-4 rounded-xl font-semibold text-lg transition">
                            Explorer le catalogue
                        </a>
                    @else
                        <a href="/login" 
                           class="bg-white text-gray-900 hover:bg-gray-100 px-8 py-4 rounded-xl font-semibold text-lg transition">
                            Se connecter
                        </a>
                        <a href="/register" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition">
                            Créer un compte
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
<style>
    .bg-dots-darker {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgb(156 163 175 / 0.15)'/%3E%3C/svg%3E");
    }
</style>
@endpush
