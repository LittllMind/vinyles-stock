@extends('layouts.app')

@section('title', 'Détails de la Bougie')

@section('content')<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Détails de la Bougie</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.bougies.edit', $bougie) }}" class="btn btn-primary">
                Modifier
            </a>
            <a href="{{ route('admin.bougies.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Retour à la liste
            </a>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Référence</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->reference }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->nom }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Parfum</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->parfum }}</dd>
                        </div>
                        @if($bougie->collection)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Collection</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->collection }}</dd>
                        </div>
                        @endif
                        @if($bougie->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Notes olfactives</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Caractéristiques</h3>
                    <dl class="space-y-4">
                        @if($bougie->format)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Format</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->format }}</dd>
                        </div>
                        @endif
                        @if($bougie->type_cire)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type de cire</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->type_cire }}</dd>
                        </div>
                        @endif
                        @if($bougie->temps_brulure)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Temps de brûlure</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bougie->temps_brulure }} minutes</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prix</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-bold">{{ number_format($bougie->prix, 2, ',', ' ') }} €</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock</h3>
                <div class="flex items-center gap-6">
                    <div>
                        <span class="text-sm text-gray-500">Quantité disponible</span>
                        <div class="text-2xl font-bold {{ $bougie->quantite <= $bougie->seuil_alerte ? 'text-red-600' : 'text-green-600' }}">
                            {{ $bougie->quantite }}
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Seuil d'alerte</span>
                        <div class="text-2xl font-bold text-gray-700">
                            {{ $bougie->seuil_alerte }}
                        </div>
                    </div>
                    @if($bougie->quantite <= $bougie->seuil_alerte)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                        ⚠️ Stock faible
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
