{{-- resources/views/admin/bougies/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Bougies')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Bougies</h1>
        <a href="{{ route('bougies.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            + Nouvelle Bougie
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom / Parfum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Format</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($bougies as $bougie)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $bougie->reference }}</td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900">{{ $bougie->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $bougie->parfum }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $bougie->format ?? 'N/A' }} / {{ $bougie->type_cire ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ number_format($bougie->prix, 2, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bougie->quantite <= $bougie->seuil_alerte ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $bougie->quantite }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('bougies.show', $bougie) }}" class="text-blue-600 hover:underline">Voir</a>
                            <a href="{{ route('bougies.edit', $bougie) }}" class="text-yellow-600 hover:underline">Modifier</a>
                            <form action="{{ route('bougies.destroy', $bougie) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Aucune bougie enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $bougies->links() }}
        </div>
    </div>
</div>
@endsection
