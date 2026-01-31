<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paiement</h2>
            <a href="{{ url('/kiosque') }}" class="text-blue-600 hover:text-blue-800">← Continuer mes achats</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Vérification des informations</h3>

                <p class="mb-4 text-gray-700">Veuillez vérifier vos informations de livraison ci-dessous avant de procéder au paiement.</p>

                <ul class="mb-6">
                    <li><strong>Prénom:</strong> {{ $prefill['prenom'] ?? '' }}</li>
                    <li><strong>Nom:</strong> {{ $prefill['nom'] ?? '' }}</li>
                    <li><strong>Email:</strong> {{ $prefill['email'] ?? '' }}</li>
                    <li><strong>Téléphone:</strong> {{ $prefill['telephone'] ?? '' }}</li>
                    <li><strong>Adresse:</strong> {{ $prefill['adresse'] ?? '' }}</li>
                    <li><strong>Code postal:</strong> {{ $prefill['code_postal'] ?? '' }}</li>
                    <li><strong>Ville:</strong> {{ $prefill['ville'] ?? '' }}</li>
                </ul>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Récapitulatif du panier</h3>

                    @if ($cart->isEmpty())
                        <p>Votre panier est vide.</p>
                    @else
                        <ul class="space-y-2 mb-4">
                            @foreach ($cart->items as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item->vinyle->nom }} x{{ $item->quantite }}{{ $item->fond ? ' (' . ucfirst($item->fond->type) . ')' : '' }}</span>
                                    <span>{{ number_format($item->prix_unitaire * $item->quantite, 2, ',', ' ') }} €</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="flex justify-between font-bold mb-6">
                            <span>Total</span>
                            <span>{{ number_format($cart->total(), 2, ',', ' ') }} €</span>
                        </div>

                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            {{-- Prefill order fields from last order --}}
                            <input type="hidden" name="prenom" value="{{ $prefill['prenom'] ?? '' }}">
                            <input type="hidden" name="nom" value="{{ $prefill['nom'] ?? '' }}">
                            <input type="hidden" name="email" value="{{ $prefill['email'] ?? '' }}">
                            <input type="hidden" name="telephone" value="{{ $prefill['telephone'] ?? '' }}">
                            <input type="hidden" name="adresse" value="{{ $prefill['adresse'] ?? '' }}">
                            <input type="hidden" name="code_postal" value="{{ $prefill['code_postal'] ?? '' }}">
                            <input type="hidden" name="ville" value="{{ $prefill['ville'] ?? '' }}">
                            <input type="hidden" name="notes_client" value="{{ $prefill['notes_client'] ?? '' }}">

                            <div class="flex gap-3 justify-end">
                                <a href="{{ route('orders.create') }}" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded">Modifier les informations</a>
                                <button type="submit" class="inline-block bg-green-600 text-white px-4 py-2 rounded">Payer</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>