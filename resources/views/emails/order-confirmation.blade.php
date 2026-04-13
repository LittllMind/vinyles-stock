<x-mail::message>
# Confirmation de commande #{{ $order->numero_commande }}

Bonjour {{ $order->user->prenom ?? $order->user->name }},

Votre commande a été confirmée avec succès !

**Récapitulatif :**
- Numéro : #{{ $order->numero_commande }}
- Date : {{ $order->created_at->format('d/m/Y') }}
- Montant total : {{ number_format($order->total / 100, 2, ',', ' ') }} €

**Articles commandés :**
@foreach($order->items as $item)
- {{ $item->vinyle->nom_complet ?? 'Vinyle' }} x{{ $item->quantite }} — {{ number_format($item->prix_unitaire * $item->quantite / 100, 2, ',', ' ') }} €
@endforeach

Nous vous tiendrons informé de l'avancement de votre commande.

<x-mail::button :url="route('orders.my')">
Voir mes commandes
</x-mail::button>

Merci pour votre confiance !
</x-mail::message>
