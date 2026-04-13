<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\ContactReceived;
use App\Mail\ContactAutoReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Affiche le formulaire de contact
     */
    public function index()
    {
        return view('contact', ['success' => session('success')]);
    }

    /**
     * Traite l'envoi du formulaire
     */
    public function store(Request $request)
    {
        // Validation avec honeypot anti-spam
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000|min:10',
            'website' => 'nullable', // Honeypot - doit rester vide
        ]);

        // Protection honeypot
        if (!empty($validated['website'])) {
            return redirect()->route('contact')->with(['success' => 'Message envoyé avec succès.']);
        }

        // Protection rate limiting
        $ip = $request->ip();
        $recentCount = ContactMessage::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentCount >= 5) {
            return back()->withErrors(['email' => 'Trop de messages envoyés. Veuillez réessayer plus tard.'])
                ->withInput();
        }

        // Création du message
        $message = ContactMessage::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'sujet' => $validated['sujet'] ?? 'Contact sans sujet',
            'message' => $validated['message'],
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'statut' => 'non_lu',
        ]);

        // Email à l'admin
        Mail::to(config('mail.admin_address', 'contact@vinyle-hydrodecoupe.fr'))
            ->send(new ContactReceived($message));

        // Auto-réponse au client
        Mail::to($validated['email'])
            ->send(new ContactAutoReply($validated['nom']));

        return redirect()->route('contact')->with([
            'success' => 'Votre message a été envoyé avec succès. Nous vous répondrons sous 24-48h.',
        ]);
    }
}
