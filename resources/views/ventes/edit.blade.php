@extends('layouts.art-print')

@section('title', 'Modifier vente #' . $vente->id)

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Modifier vente #{{ $vente->id }}</h1>
    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">
        <p>La modification des ventes n'est pas encore implémentée.</p>
        <a href="{{ route('ventes.show', $vente) }}" class="mt-4 inline-block text-sm underline">Voir la vente</a>
    </div>
</div>
@endsection
