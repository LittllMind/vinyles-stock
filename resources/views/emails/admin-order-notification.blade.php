<x-mail::message>
# Nouvelle commande #{{ $order->numero_commande }}

Une nouvelle commande a été passée sur le site.

**Client :** {{ $order->user->name }} ({{ $order->user->email }})
**Montant :** {{ number_format($order->total / 100, 2, ',', ' ') }} €

**Articles :**
@foreach($order->items as $item)
- {{ $item->vinyle->nom_complet ?? 'Vinyle' }} x{{ $item->quantite }}
@endforeach

<x-mail::button :url="route('admin.orders.show', $order)">
Voir la commande
</x-mail::button>

Ce mail est généré automatiquement par l'application FUNDISC.
</x-mail::message>
