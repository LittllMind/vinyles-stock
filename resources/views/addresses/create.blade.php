@extends('layouts.art-print')

@section('title', 'Nouvelle Adresse')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-light mb-8 text-center">Nouvelle Adresse</h1>
    
    <form action="{{ route('addresses.store') }}" method="POST" class="space-y-6">
        @csrf
        @include('addresses._form_fields')
        
        <div class="flex gap-4 pt-6">
            <button type="submit" class="flex-1 bg-stone-900 text-white py-3 px-6 font-light tracking-wider hover:bg-stone-800 transition-colors">
                Enregistrer
            </button>
            <a href="{{ route('addresses.index') }}" class="flex-1 border border-stone-300 text-stone-600 py-3 px-6 text-center font-light hover:bg-stone-50 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
