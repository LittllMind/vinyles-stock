<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function confirm(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->first();

        if (!$subscriber) {
            return redirect('/')->with('error', 'Lien de confirmation invalide ou expiré.');
        }

        $subscriber->confirm();

        return redirect('/')->with('success', 'Votre inscription à la newsletter est confirmée !');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect('/')->with('error', 'Lien de désinscription invalide.');
        }

        $subscriber->delete();

        return redirect('/')->with('info', 'Vous avez été désinscrit de la newsletter.');
    }
}
