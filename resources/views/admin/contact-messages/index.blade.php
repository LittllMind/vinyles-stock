@extends('layouts.admin')

@section('title', 'Messages Contact')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header avec stats --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Messages de Contact</h1>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                {{ $stats['nouveaux'] }} nouveaux
            </span>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                {{ $stats['en_attente'] }} en attente
            </span>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="border rounded px-3 py-2">
                    <option value="">Tous</option>
                    <option value="non_lu" {{ request('statut') == 'non_lu' ? 'selected' : '' }}>🔴 Non lu</option>
                    <option value="lu" {{ request('statut') == 'lu' ? 'selected' : '' }}>🟡 Lu</option>
                    <option value="repondu" {{ request('statut') == 'repondu' ? 'selected' : '' }}>🟢 Répondu</option>
                    <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>⚪ Archivé</option>
                </select>
            </div>

            <div class="flex-grow max-w-md">
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email, sujet..." class="border rounded px-3 py-2 w-full">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrer
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Tableau des messages --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expéditeur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sujet</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aperçu</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($messages as $message)
                    <tr class="hover:bg-gray-50 {{ $message->statut === 'non_lu' ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-3">
                            {!! $message->statutBadge() !!}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $message->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $message->email }}</div>
                            @if($message->telephone)
                                <div class="text-xs text-gray-400">{{ $message->telephone }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $message->sujet ?? 'Sans sujet' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                            {{ $message->apercu(60) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                            <div class="text-xs">{{ $message->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    Voir
                                </a>
                                @if($message->statut === 'non_lu')
                                    <form method="POST" action="{{ route('admin.contact-messages.read', $message) }}"
                                          class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm">
                                            Marquer lu
                                        </button>
                                    </form>
                                @endif
                                @if($message->statut !== 'archive')
                                    <form method="POST" action="{{ route('admin.contact-messages.archive', $message) }}"
                                          class="inline" onsubmit="return confirm('Archiver ce message ?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-gray-400 hover:text-gray-600 text-sm">
                                            Archiver
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Aucun message trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection