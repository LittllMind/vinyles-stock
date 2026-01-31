<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Finaliser ma commande</h2>
            <a href="{{ url('/kiosque') }}" class="text-blue-600 hover:text-blue-800">← Continuer mes achats</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf

                    <h3 class="text-lg font-semibold mb-4">Informations client</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="prenom" :value="__('Prénom')" />
                            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" value="{{ old('prenom') }}" required />
                            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="nom" :value="__('Nom')" />
                            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" value="{{ old('nom') }}" required />
                            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') ?? (auth()->user()->email ?? '') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="telephone" :value="__('Téléphone')" />
                            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" value="{{ old('telephone') }}" />
                            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="adresse" :value="__('Adresse')" />
                            <x-text-input id="adresse" class="block mt-1 w-full" type="text" name="adresse" value="{{ old('adresse') }}" />
                            <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="code_postal" :value="__('Code postal')" />
                            <x-text-input id="code_postal" class="block mt-1 w-full" type="text" name="code_postal" value="{{ old('code_postal') }}" />
                        </div>

                        <div>
                            <x-input-label for="ville" :value="__('Ville')" />
                            <x-text-input id="ville" class="block mt-1 w-full" type="text" name="ville" value="{{ old('ville') }}" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes_client" :value="__('Notes (facultatif)')" />
                            <textarea id="notes_client" name="notes_client" class="block mt-1 w-full border rounded p-2">{{ old('notes_client') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-lg font-semibold mb-4">Récapitulatif de votre panier</h3>

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

                            <div class="flex justify-between font-bold mb-4">
                                <span>Total</span>
                                <span>{{ number_format($cart->total(), 2, ',', ' ') }} €</span>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>
                                    Confirmer la commande
                                </x-primary-button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>