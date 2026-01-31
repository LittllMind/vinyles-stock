<x-app-layout>
    <x-slot name="header">
        <h2>Mes commandes</h2>
    </x-slot>

    <div class="page-content">
        @if($orders->isEmpty())
            <p>Vous n'avez pas encore passé de commande.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->numero_commande }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{!! $order->statutBadge() !!}</td>
                            <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                            <td><a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-secondary">Voir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
