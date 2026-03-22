{{-- resources/views/admin/bougies/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvelle Bougie')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-6">
        <a href="{{ route('bougies.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Nouvelle Bougie</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bougies.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Référence *</label>
                <input type="text" name="reference" value="{{ old('reference') }}" class="w-full rounded-lg border-gray-300 shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="nom" value="{{ old('nom') }}" class="w-full rounded-lg border-gray-300 shadow-sm" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parfum *</label>
                <input type="text" name="parfum" value="{{ old('parfum') }}" class="w-full rounded-lg border-gray-300 shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collection</label>
                <input type="text" name="collection" value="{{ old('collection') }}" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                <select name="format" class="w-full rounded-lg border-gray-300 shadow-sm">
                    <option value="">Sélectionner</option>
                    <option value="120g" {{ old('format') == '120g' ? 'selected' : '' }}>120g</option>
                    <option value="200g" {{ old('format') == '200g' ? 'selected' : '' }}>200g</option>
                    <option value="300g" {{ old('format') == '300g' ? 'selected' : '' }}>300g</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de Cire</label>
                <select name="type_cire" class="w-full rounded-lg border-gray-300 shadow-sm">
                    <option value="">Sélectionner</option>
                    <option value="Soja" {{ old('type_cire') == 'Soja' ? 'selected' : '' }}>Soja</option>
                    <option value="Paraffine" {{ old('type_cire') == 'Paraffine' ? 'selected' : '' }}>Paraffine</option>
                    <option value="Cire d'abeille" {{ old('type_cire') == "Cire d'abeille" ? 'selected' : '' }}>Cire d'abeille</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Temps de brûlure (min)</label>
                <input type="number" name="temps_brulure" value="{{ old('temps_brulure') }}" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix * (€)</label>
                <input type="number" step="0.01" name="prix" value="{{ old('prix') }}" class="w-full rounded-lg border-gray-300 shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock</label>
                <input type="number" name="quantite" value="{{ old('quantite', 0) }}" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Seuil d'alerte</label>
                <input type="number" name="seuil_alerte" value="{{ old('seuil_alerte', 5) }}" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes olfactives</label>
            <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">Créer</button>
            <a href="{{ route('bougies.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">Annuler</a>
        </div>
    </form>
</div>
@endsection
