<x-mail::message>
# Réponse à votre message

Bonjour {{ $contactMessage->prenom ?? $contactMessage->nom }},

Vous nous avez contacté concernant : **{{ $contactMessage->sujet ?? 'Demande générale' }}**

Voici notre réponse :

---
{{ $replyMessage }}
---

@if($contactMessage->order_id)
Concernant votre commande #{{ $contactMessage->order->numero_commande }}
@endif

<x-mail::button :url="route('home')">
Visiter notre site
</x-mail::button>

Cordialement,
L'équipe FUNDISC

---
*Votre message original :*
> {{ Str::limit($contactMessage->message, 200) }}
</x-mail::message>
