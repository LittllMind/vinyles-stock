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
        // Supporte les soumissions simplifiées (email seul) et complètes
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255', // Alias pour sujet (form simplifié)
            'message' => 'nullable|string|max:5000',
            'website' => 'nullable', // Honeypot - doit rester vide
        ]);

        // Protection honeypot
        if (!empty($validated['website'])) {
            $returnTo = $request->input('return_to');
            if ($returnTo === 'landing') {
                return redirect()->route('landing')->with(['success' => 'Merci ! Vous serez alerté(e) dès que les vinyles seront disponibles.']);
            }
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

        // Déterminer nom et message (support formulaires simplifiés)
        $nom = $validated['nom'] ?? 'Visiteur';
        $sujet = $validated['sujet'] ?? $validated['subject'] ?? 'Contact FUN DISC';
        $messageContent = $validated['message'] ?? 'Demande client depuis le site';

        // Création du message
        $message = ContactMessage::create([
            'nom' => $nom,
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'sujet' => $sujet,
            'message' => $messageContent,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'statut' => 'non_lu',
        ]);

        // Email à l'admin
        Mail::to(config('mail.admin_address', 'contact@vinyle-hydrodecoupe.fr'))
            ->send(new ContactReceived($message));

        // Auto-réponse au client
        Mail::to($validated['email'])
            ->send(new ContactAutoReply($nom));

        // Redirection intelligente
        $returnTo = $request->input('return_to');
        if ($returnTo === 'landing') {
            return redirect()->route('landing')->with([
                'success' => 'Merci ! Vous serez alerté(e) dès que les vinyles seront disponibles.',
            ]);
        }

        return redirect()->route('contact')->with([
            'success' => 'Votre message a été envoyé avec succès. Nous vous répondrons sous 24-48h.',
        ]);
    }
}
