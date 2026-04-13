@extends('layouts.art-print')

@section('title', 'Mes conversations')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-light">Mes messages</h1>
        <button onclick="document.getElementById('new-conv').classList.remove('hidden')" 
                class="bg-gray-900 text-white px-6 py-3 rounded hover:bg-gray-800 transition">
            + Nouveau message
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- New conversation form --}}
    <div id="new-conv" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="POST" action="{{ route('conversations.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                <input type="text" name="sujet" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                       placeholder="De quoi s'agit-il ?">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea name="contenu" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                          placeholder="Décrivez votre demande..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded hover:bg-gray-800 transition">
                    Envoyer
                </button>
                <button type="button" onclick="document.getElementById('new-conv').classList.add('hidden')" 
                        class="text-gray-600 hover:text-gray-900 px-4 py-2">
                    Annuler
                </button>
            </div>
        </form>
    </div>

    {{-- Conversations list --}}
    <div class="space-y-4">
        @forelse($conversations as $conversation)
            @php $nonLus = $conversation->messages_count; @endphp
            
            <a href="{{ route('conversations.show', $conversation) }}" 
               class="block bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-medium text-gray-900">{{ $conversation->sujet ?: 'Sans sujet' }}</h3>
                            
                            @if($nonLus > 0)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $nonLus }} nouveaux
                                </span>
                            @endif
                            
                            @if($conversation->statut === 'active')
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Fermée</span>
                            @endif
                        </div>
                        
                        @if($conversation->order_id)
                            <p class="text-sm text-gray-500 mb-2">
                                📦 Concernant commande #{{ $conversation->order->numero_commande ?? $conversation->order_id }}
                            </p>
                        @endif
                        
                        <p class="text-sm text-gray-500">
                            Dernier message {{ $conversation->dernier_message_at ? $conversation->dernier_message_at->diffForHumans() : 'jamais' }}
                        </p>
                    </div>
                    
                    <div class="text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                <p class="text-gray-500 mb-2">Vous n'avez pas encore de conversations.</p>
                <p class="text-sm text-gray-400">Cliquez sur « Nouveau message » pour nous contacter.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $conversations->links() }}
    </div>
</div>
@endsection
