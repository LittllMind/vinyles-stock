@extends('layouts.art-print')

@section('title', 'Modifier profil')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Modifier mon profil</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border px-3 py-2">
        </div>
        <button type="submit" class="bg-black text-white px-4 py-2">Enregistrer</button>
        <a href="{{ route('profile') }}" class="ml-4 text-sm underline">Annuler</a>
    </form>
</div>
@endsection
