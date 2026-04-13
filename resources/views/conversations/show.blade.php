@extends('layouts.art-print')

@section('title', $conversation->sujet)

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <div class="mb-6">
        <a href="{{ route('conversations.index') }}" class="text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux messages
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-xl font-medium">{{ $conversation->sujet }}</h1>
                @if($conversation->order_id)
                    <p class="text-sm text-gray-500 mt-1">
                        📦 Concernant <a href="{{ route('orders.my') }}" class="text-blue-600 hover:underline">commande #{{ $conversation->order->numero_commande ?? $conversation->order_id }}</a>
                    </p>
                @endif
            </div>
            
            @if($conversation->statut === 'active')
                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded">Active</span>
            @else
                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded">Fermée</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Messages thread --}}
    <div class="space-y-4 mb-8">
        @foreach($conversation->messages as $message)
            <div class="flex {{ $message->type === 'client' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $message->type === 'client' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-5 py-3">
                    <div class="flex items-center gap-2 mb-1 text-xs opacity-75">
                        <span>{{ $message->type === 'client' ? 'Vous' : ($message->user->name ?? 'Équipe FUNDISC') }}</span>
                        <span>•</span>
                        <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="whitespace-pre-wrap text-sm">{{ $message->contenu }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reply form --}}
    @if($conversation->statut === 'active')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('conversations.reply', $conversation) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Votre réponse</label>
                    <textarea name="contenu" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                              placeholder="Écrivez votre message...">{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded hover:bg-gray-800 transition">
                    Envoyer
                </button>
            </form>
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500">
            Cette conversation est fermée. Vous ne pouvez plus répondre.
        </div>
    @endif
</div>
@endsection
