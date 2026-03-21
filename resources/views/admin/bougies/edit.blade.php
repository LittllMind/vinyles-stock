@extends('layouts.app')

@section('title', 'Modifier la Bougie')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Modifier la Bougie</h1>
        <a href="{{ route('admin.bougies.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Retour à la liste
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.bougies.update', $bougie) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700">Référence *</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference', $bougie->reference) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom *</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $bougie->nom) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="parfum" class="block text-sm font-medium text-gray-700">Parfum *</label>
                        <input type="text" name="parfum" id="parfum" value="{{ old('parfum', $bougie->parfum) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="collection" class="block text-sm font-medium text-gray-700">Collection</label>
                        <input type="text" name="collection" id="collection" value="{{ old('collection', $bougie->collection) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700">Format</label>
                        <select name="format" id="format"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choisir...</option>
                            <option value="120g" {{ old('format', $bougie->format) == '120g' ? 'selected' : '' }}>120g</option>
                            <option value="200g" {{ old('format', $bougie->format) == '200g' ? 'selected' : '' }}>200g</option>
                            <option value="300g" {{ old('format', $bougie->format) == '300g' ? 'selected' : '' }}>300g</option>
                        </select>
                    </div>

                    <div>
                        <label for="type_cire" class="block text-sm font-medium text-gray-700">Type de cire</label>
                        <select name="type_cire" id="type_cire"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Choisir...</option>
                            <option value="soja" {{ old('type_cire', $bougie->type_cire) == 'soja' ? 'selected' : '' }}>Soja</option>
                            <option value="paraffine" {{ old('type_cire', $bougie->type_cire) == 'paraffine' ? 'selected' : '' }}>Paraffine</option>
                            <option value="cire végétale" {{ old('type_cire', $bougie->type_cire) == 'cire végétale' ? 'selected' : '' }}>Cire végétale</option>
                            <option value="beeswax" {{ old('type_cire', $bougie->type_cire) == 'beeswax' ? 'selected' : '' }}>Cire d'abeille</option>
                        </select>
                    </div>

                    <div>
                        <label for="temps_brulure" class="block text-sm font-medium text-gray-700">Temps de brûlure (minutes)</label>
                        <input type="number" name="temps_brulure" id="temps_brulure" value="{{ old('temps_brulure', $bougie->temps_brulure) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700">Prix *</label>
                        <input type="number" step="0.01" name="prix" id="prix" value="{{ old('prix', $bougie->prix) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité en stock</label>
                        <input type="number" name="quantite" id="quantite" value="{{ old('quantite', $bougie->quantite) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="seuil_alerte" class="block text-sm font-medium text-gray-700">Seuil d'alerte stock</label>
                        <input type="number" name="seuil_alerte" id="seuil_alerte" value="{{ old('seuil_alerte', $bougie->seuil_alerte) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes olfactives</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $bougie->notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.bougies.index') }}" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
