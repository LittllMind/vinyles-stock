@extends('layouts.admin')

@section('title', 'Message Contact')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Retour à la liste
            </a>
            <h1 class="text-2xl font-bold mt-2">Message de {{ $message->nom }}</h1>
        </div>
        <div class="flex gap-2">
            @if($message->statut !== 'archive')
                <form method="POST" action="{{ route('admin.contact-messages.archive', $message) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Archiver
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Colonne gauche: Message --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Message original --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $message->statut === 'non_lu' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $message->statut === 'lu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $message->statut === 'repondu' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $message->statut === 'archive' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($message->statut) }}
                        </span>
                        <span class="text-sm text-gray-500 ml-2">
                            Reçu {{ $message->created_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </div>

                <div class="border-b pb-4 mb-4">
                    <h2 class="text-lg font-semibold mb-2">{{ $message->sujet ?? 'Sans sujet' }}</h2>
                </div>

                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $message->message }}</div>
            </div>

            {{-- Historique des réponses --}}
            @if($message->reponse)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-semibold text-green-800">Votre réponse</h3>
                        <span class="text-sm text-green-600">
                            Envoyée {{ $message->repondu_at?>format('d/m/Y à H:i') : '' }}
                            @if($message->userRepondu)
                                par {{ $message->userRepondu->name }}
                            @endif
                        </span>
                    </div>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $message->reponse }}</div>
                </div>
            @endif

            {{-- Formulaire de réponse --}}
            @if(!$message->reponse && $message->statut !== 'archive')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-4">Répondre à {{ $message->nom }}</h3>
                    <form method="POST" action="{{ route('admin.contact-messages.reply', $message) }}">
                        @csrf
                        <div class="mb-4">
                            <textarea name="reponse" rows="6"
                                      class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"
                                      placeholder="Votre réponse..."
                                      required>{{ old('reponse') }}</textarea>
                            @error('reponse')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500">
                                La réponse sera envoyée à {{ $message->email }}
                            </p>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Colonne droite: Infos --}}
        <div class="space-y-6">
            {{-- Coordonnées --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4 flex items-center">
                    <span class="mr-2">👤</span> Coordonnées
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500 block">Nom</span>
                        <span class="font-medium">{{ $message->nom }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Email</span>
                        <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline">
                            {{ $message->email }}
                        </a>
                    </div>
                    @if($message->telephone)
                        <div>
                            <span class="text-gray-500 block">Téléphone</span>
                            <a href="tel:{{ $message->telephone }}" class="text-blue-600 hover:underline">
                                {{ $message->telephone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informations techniques --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-semibold mb-4 flex items-center">
                    <span class="mr-2">🔧</span> Informations
                </h3>
                <div class="space-y-2 text-xs text-gray-500">
                    <div>ID: {{ $message->id }}</div>
                    <div>IP: {{ $message->ip_address ?? 'N/A' }}</div>
                    <div class="truncate">User-Agent: {{ $message->user_agent ?? 'N/A' }}</div>
                </div>
            </div>

            {{-- Actions rapides --}}
            @if($message->statut === 'non_lu' || $message->statut === 'lu')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800 mb-2">
                        Message en attente de réponse
                    </p>
                    <form method="POST" action="{{ route('admin.contact-messages.archive', $message) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-sm text-yellow-700 hover:text-yellow-900 underline">
                            Marquer comme résolu sans répondre
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection