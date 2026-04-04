<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterConfirmationMail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->confirmed) {
                return response()->json([
                    'message' => 'Cet email est déjà inscrit à la newsletter.',
                ], 422);
            }

            // Régénère le token et renvoie l'email
            $existing->regenerateConfirmationToken();
            Mail::to($existing->email)->send(new NewsletterConfirmationMail($existing));

            return response()->json([
                'message' => 'Un nouvel email de confirmation a été envoyé.',
            ]);
        }

        $subscriber = NewsletterSubscriber::create([
            'email' => $validated['email'],
            'confirmed' => false,
        ]);

        Mail::to($subscriber->email)->send(new NewsletterConfirmationMail($subscriber));

        return response()->json([
            'message' => 'Inscription initiée. Veuillez confirmer votre email.',
        ], 201);
    }
}
