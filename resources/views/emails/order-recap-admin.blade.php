<p>Nouvelle commande <strong>{{ $order->numero_commande }}</strong></p>
<p>Client : {{ $order->nomComplet() }} — {{ $order->email }} — {{ $order->telephone }}</p>

<p>Récapitulatif :</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->titre_vinyle }} x{{ $item->quantite }} ({{ $item->artiste_vinyle }}) — {{ number_format($item->total, 2, ',', ' ') }} €</li>
    @endforeach
</ul>

<p>Total : <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong></p>

<p>Voir l'administration pour les détails.</p>