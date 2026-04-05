{{-- resources/views/errors/404.blade.php --}}
{{-- Vue erreur 404 personnalisée - Design Vinyle/Thème --}}

@extends('layouts.app')

@section('title', '404 - Page non trouvée')

@section('content')
<div class="min-h-screen bg-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        {{-- Vinyl Record Animation --}}
        <div class="relative mb-8 mx-auto w-48 h-48">
            {{-- Vinyl disc --}}
            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-slate-800 to-black shadow-2xl animate-spin-slow"
            >
                {{-- Grooves --}}
                <div class="absolute inset-2 rounded-full border border-slate-700/50"></div>
                <div class="absolute inset-4 rounded-full border border-slate-700/50"></div>
                <div class="absolute inset-6 rounded-full border border-slate-700/50"></div>
                <div class="absolute inset-8 rounded-full border border-slate-700/50"></div>
                <div class="absolute inset-10 rounded-full border border-slate-700/50"></div>
                
                {{-- Center label --}}
                <div class="absolute inset-16 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center"
                >
                    <span class="text-slate-900 font-bold text-2xl">404</span>
                </div>
                
                {{-- Scratch marks --}}
                <div class="absolute top-8 left-12 w-16 h-0.5 bg-slate-600/30 transform rotate-45"></div>
                <div class="absolute bottom-12 right-8 w-12 h-0.5 bg-slate-600/30 transform -rotate-12"></div>
            </div>
            
            {{-- Needle arm (static) --}}
            <div class="absolute -top-4 -right-4 w-16 h-2 bg-slate-600 rounded-full transform rotate-45 origin-left shadow-lg"></div>
        </div>

        {{-- Error text --}}
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
            Vinyl
            <span class="text-amber-500">404</span>
        </h1>
        
        <p class="text-xl text-slate-400 mb-2">Oups ! Ce morceau n'existe pas</p>
        <p class="text-slate-500 mb-8">La page que vous recherchez a été rayée ou n'a jamais été pressée.</p>

        {{-- Navigation suggestions --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('landing') }}" 
               class="group flex flex-col items-center p-4 bg-slate-800 rounded-xl border border-slate-700/50 hover:border-amber-500/50 hover:bg-slate-750 transition-all duration-300"
            >
                <div class="w-12 h-12 mb-3 rounded-full bg-slate-700 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                    <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="text-white font-medium">Accueil</span>
                <span class="text-sm text-slate-500">Retour au début</span>
            </a>

            <a href="{{ route('kiosque.index') }}" 
               class="group flex flex-col items-center p-4 bg-slate-800 rounded-xl border border-slate-700/50 hover:border-amber-500/50 hover:bg-slate-750 transition-all duration-300"
            >
                <div class="w-12 h-12 mb-3 rounded-full bg-slate-700 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                    <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <span class="text-white font-medium">Kiosque</span>
                <span class="text-sm text-slate-500">Explorer le catalogue</span>
            </a>

            <a href="{{ route('contact') }}" 
               class="group flex flex-col items-center p-4 bg-slate-800 rounded-xl border border-slate-700/50 hover:border-amber-500/50 hover:bg-slate-750 transition-all duration-300"
            >
                <div class="w-12 h-12 mb-3 rounded-full bg-slate-700 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                    <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-white font-medium">Contact</span>
                <span class="text-sm text-slate-500">Une question ?</span>
            </a>
        </div>

        {{-- FAQ link --}}
        <div class="pt-6 border-t border-slate-800">
            <p class="text-slate-500 mb-3">Vous cherchez quelque chose en particulier ?</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('faq') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Consulter la FAQ
                </a>
                
                <button onclick="history.back()" class="inline-flex items-center px-4 py-2 bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Page précédente
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
@endpush
@endsection