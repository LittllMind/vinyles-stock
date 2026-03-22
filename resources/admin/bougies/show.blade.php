{{-- resources/views/admin/bougies/show.blade.php --}}
@extends('layouts.app')

@section('title', $bougie->nom)

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-6">
        <a href="{{ route('bougies.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ $bougie->nom }}</h1>
                <span class="text-lg font-medium text-gray-600">{{ $bougie->reference }}</span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Caractéristiques</h2>
                    <div class="space-y-3">
                        <dl class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Parfum</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->parfum }}</dd>

                            <dt class="text-sm font-medium text-gray-500">Collection</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->collection ?? 'Non spécifiée' }}</dd>

                            <dt class="text-sm font-medium text-gray-500">Format</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->format ?? 'Non spécifié' }}</dd>

                            <dt class="text-sm font-medium text-gray-500">Type de Cire</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->type_cire ?? 'Non spécifié' }}</dd>

                            <dt class="text-sm font-medium text-gray-500">Temps de brûlure</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->temps_brulure ? $bougie->temps_brulure . ' minutes' : 'Non spécifié' }}</dd>
                        </dl>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Gestion</h2>
                    <div class="space-y-3">
                        <dl class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Prix</dt>
                            <dd class="text-xl font-bold text-green-600 col-span-2">{{ number_format($bougie->prix, 2, ',', ' ') }} €</dd>

                            <dt class="text-sm font-medium text-gray-500">Stock</dt>
                            <dd class="col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bougie->quantite <= $bougie->seuil_alerte ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $bougie->quantite }} unités
                                </span>
                                @if($bougie->quantite <= $bougie->seuil_alerte)
                                    <span class="text-red-600 text-sm ml-2">⚠ Stock faible (seuil: {{ $bougie->seuil_alerte }})</span>
                                @endif
                            </dd>

                            <dt class="text-sm font-medium text-gray-500">Seuil d'alerte</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->seuil_alerte }} unités</dd>

                            <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="text-sm font-medium text-gray-500">Modifiée le</dt>
                            <dd class="text-gray-900 col-span-2">{{ $bougie->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            @if($bougie->notes)
                <div class="mt-6 pt-6 border-t">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Notes olfactives</h2>
                    <p class="text-gray-600">{{ $bougie->notes }}</p>
                </div>
            @endif

            <div class="mt-6 pt-6 border-t flex gap-4">
                <a href="{{ route('bougies.edit', $bougie) }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">Modifier</a>
                <form action="{{ route('bougies.destroy', $bougie) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition" onclick="return confirm('Confirmer la suppression de cette bougie ?')">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
