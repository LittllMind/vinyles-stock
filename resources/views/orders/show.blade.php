<x-app-layout>
    <x-slot name="header">
        <h2>Détail de la commande {{ $order->numero_commande }}</h2>
    </x-slot>

    <div class="page-content">
        <div class="mb-4">
            <strong>Client :</strong> {{ $order->nomComplet() }}<br>
            <strong>Email :</strong> {{ $order->email }}<br>
            <strong>Adresse :</strong> {{ $order->adresse ?? '—' }}
        </div>

        <h3>Articles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->titre_vinyle }}</td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ number_format($item->prix_unitaire, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($item->total, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <strong>Total : {{ number_format($order->total, 2, ',', ' ') }} €</strong>
        </div>
    </div>
</x-app-layout>
