<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public ContactMessage $contactMessage;
    public string $reponse;

    public function __construct(ContactMessage $contactMessage, string $reponse)
    {
        $this->contactMessage = $contactMessage;
        $this->reponse = $reponse;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . ($this->contactMessage->sujet ?: 'Votre message'),
            replyTo: [config('mail.from.address') => config('mail.from.name')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
        );
    }
}