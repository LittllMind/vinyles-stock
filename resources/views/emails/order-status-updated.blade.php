<x-mail::message>
# Mise à jour de votre commande #{{ $order->numero_commande }}

Bonjour {{ $order->user->prenom ?? $order->user->name }},

Le statut de votre commande a été mis à jour.

**Nouveau statut :** {{ $order->statut_label ?? ucfirst($order->status) }}

@switch($order->status)
    @case('pending')
        Votre commande est en attente de validation.
        @break
    @case('processing')
        Votre commande est en cours de préparation.
        @break
    @case('shipped')
        Votre commande a été expédiée !
        @break
    @case('delivered')
        Votre commande vous a été livrée.
        @break
    @case('cancelled')
        Votre commande a été annulée.
        @break
@endswitch

<x-mail::button :url="route('orders.my')">
Voir le détail
</x-mail::button>

À bientôt !
</x-mail::message>
