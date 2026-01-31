<p>Bonjour {{ $order->nomComplet() }},</p>

<p>Merci pour votre commande <strong>{{ $order->numero_commande }}</strong>.</p>

<p>Récapitulatif :</p>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->titre_vinyle }} x{{ $item->quantite }} — {{ number_format($item->total, 2, ',', ' ') }} €</li>
    @endforeach
</ul>

<p>Total : <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong></p>

<p>Nous vous contacterons si nécessaire. Merci !</p>
