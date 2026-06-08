@extends('layouts.art-print')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Mon profil</h1>
    <div class="space-y-4">
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Role :</strong> {{ $user->role }}</p>
    </div>
    <a href="{{ route('profile.edit') }}" class="mt-8 inline-block bg-black text-white px-4 py-2">Modifier</a>
    <a href="{{ route('orders.my') }}" class="mt-8 inline-block ml-4 text-sm underline">Mes commandes</a>
</div>
@endsection
