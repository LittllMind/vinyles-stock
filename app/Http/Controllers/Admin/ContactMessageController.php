<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Mail\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,employe']);
    }

    /**
     * Liste des messages de contact avec filtres
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Recherche textuelle
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sujet', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Tri par date décroissante
        $query->orderBy('created_at', 'desc');

        $messages = $query->paginate(20);
        $stats = [
            'total' => ContactMessage::count(),
            'nouveaux' => ContactMessage::where('statut', 'non_lu')->count(),
            'en_attente' => ContactMessage::whereIn('statut', ['non_lu', 'lu'])->count(),
            'repondus' => ContactMessage::where('statut', 'repondu')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * Afficher un message en détail
     */
    public function show(ContactMessage $message)
    {
        // Marquer automatiquement comme lu si c'est la première fois
        $message->marquerLu();

        $message->load('userRepondu');

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Marquer un message comme lu (AJAX)
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->marquerLu();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message marqué comme lu');
    }

    /**
     * Envoyer une réponse au client
     */
    public function reply(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'reponse' => 'required|string|min:10|max:5000',
        ]);

        // Mettre à jour le message avec la réponse
        $message->repondre($validated['reponse'], Auth::id());

        // Envoyer l'email au client
        try {
            Mail::to($message->email)
                ->queue(new ContactReply($message, $validated['reponse']));
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas bloquer
            \Log::error('Erreur envoi réponse contact: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Réponse envoyée avec succès à ' . $message->email);
    }

    /**
     * Archiver un message
     */
    public function archive(ContactMessage $message)
    {
        $message->update(['statut' => 'archive']);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message archivé');
    }

    /**
     * Récupérer le nombre de messages non lus (pour badge)
     */
    public static function unreadCount(): int
    {
        return ContactMessage::where('statut', 'non_lu')->count();
    }
}