<!DOCTYPE html>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-950 to-gray-900 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec animation -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold mb-4">
                <span class="bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                    Contactez-nous
                </span>
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Une question ? Un projet personnalisé ? Nous sommes là pour vous aider.
            </p>
        </div>

        <!-- Message de succès -->
        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl mb-8 animate-fade-in">
            <div class="flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <div>
                    <p class="font-semibold">Message envoyé !</p>
                    <p class="text-sm opacity-80">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Erreurs validation -->
        @if($errors->any())
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-8">
            <div class="flex items-start">
                <span class="text-2xl mr-3">⚠️</span>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Infos de contact -->
            <div class="space-y-6">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-2xl">📍</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Localisation</h2>
                    </div>
                    <p class="text-gray-400 pl-16">
                        Le Rozier<br>
                        48150, France
                    </p>
                </div>

                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-2xl">📧</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Email</h2>
                    </div>
                    <p class="text-gray-400 pl-16">
                        <a href="mailto:contact@vinyle-hydrodecoupe.fr" class="hover:text-purple-400 transition-colors">
                            contact@vinyle-hydrodecoupe.fr
                        </a>
                    </p>
                </div>

                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-2xl">🕐</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Horaires</h2>
                    </div>
                    <p class="text-gray-400 pl-16">
                        Du lundi au samedi<br>
                        9h00 - 18h00
                    </p>
                </div>

                <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 rounded-2xl p-8 border border-purple-500/30">
                    <p class="text-center text-gray-300 text-sm">
                        ⏱️ <strong>Délai de réponse garanti :</strong> 48h maximum
                    </p>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50">
                <h2 class="text-2xl font-bold mb-6 text-white">Envoyez-nous un message</h2>
                
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Honeypot anti-spam (invisible) -->
                    <div style="position: absolute; left: -9999px;">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-gray-300 mb-2 font-medium">
                                Nom *
                            </label>
                            <input type="text" id="nom" name="nom" required
                                value="{{ old('nom') }}"
                                class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"
                                placeholder="Votre nom">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-300 mb-2 font-medium">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" required
                                value="{{ old('email') }}"
                                class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"
                                placeholder="votre@email.com">
                        </div>
                    </div>

                    <!-- Téléphone (optionnel) -->
                    <div>
                        <label for="telephone" class="block text-gray-300 mb-2 font-medium">
                            Téléphone
                        </label>
                        <input type="tel" id="telephone" name="telephone"
                            value="{{ old('telephone') }}"
                            class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"
                            placeholder="06 12 34 56 78">
                    </div>

                    <!-- Sujet -->
                    <div>
                        <label for="sujet" class="block text-gray-300 mb-2 font-medium">
                            Sujet
                        </label>
                        <input type="text" id="sujet" name="sujet"
                            value="{{ old('sujet') }}"
                            class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all"
                            placeholder="Demande de devis, question sur un produit...">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-gray-300 mb-2 font-medium">
                            Message *
                        </label>
                        <textarea id="message" name="message" rows="5" required minlength="10"
                            class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all resize-none"
                            placeholder="Décrivez votre projet ou votre question...">{{ old('message') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 caractères</p>
                    </div>

                    <!-- Bouton envoi -->
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg hover:shadow-purple-500/25 flex items-center justify-center gap-2">
                        <span>📨</span>
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>
@endpush
