<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $ancienStatut;
    public string $nouveauStatut;

    public function __construct(Order $order, string $ancienStatut)
    {
        $this->order = $order;
        $this->ancienStatut = $ancienStatut;
        $this->nouveauStatut = $order->statut;
    }

    public function envelope(): Envelope
    {
        $statuts = [
            'en_preparation' => '🔧 Votre commande est en préparation',
            'prete' => '📦 Votre commande est prête !',
            'livree' => '🚚 Votre commande a été livrée',
            'annulee' => '❌ Votre commande a été annulée',
        ];

        $sujet = $statuts[$this->nouveauStatut] ?? 'Mise à jour de votre commande';

        return new Envelope(
            subject: $sujet . ' #' . $this->order->numero_commande,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
        );
    }
}
