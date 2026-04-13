<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,employe']);
    }

    /**
     * Liste des conversations
     */
    public function index(Request $request)
    {
        $query = Conversation::with(['client', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->withCount(['messages' => function ($q) {
            $q->whereNull('lu_at')->where('type', 'client');
        }])->orderBy('dernier_message_at', 'desc');

        // Filtres
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('non_lues') && $request->non_lues) {
            $query->whereHas('messages', function ($q) {
                $q->whereNull('lu_at')->where('type', 'client');
            });
        }

        $conversations = $query->paginate(20);

        return view('admin.conversations.index', compact('conversations'));
    }

    /**
     * Afficher une conversation
     */
    public function show(Conversation $conversation)
    {
        $conversation->load(['client', 'order', 'messages.user', 'fermeePar']);

        // Marquer tous les messages non lus comme lus
        $conversation->messages()
            ->whereNull('lu_at')
            ->where('type', 'client')
            ->update(['lu_at' => now()]);

        return view('admin.conversations.show', compact('conversation'));
    }

    /**
     * Répondre à une conversation
     */
    public function reply(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'contenu' => 'required|string|min:2|max:5000',
        ]);

        // Créer le message
        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'admin',
            'contenu' => $validated['contenu'],
            'lu_at' => now(), // Les messages admin sont considérés comme lus
        ]);

        // Mettre à jour le timestamp
        $conversation->mettreAJourDernierMessage();

        return redirect()
            ->route('admin.conversations.show', $conversation)
            ->with('success', 'Réponse envoyée');
    }

    /**
     * Fermer une conversation
     */
    public function close(Conversation $conversation)
    {
        $conversation->update([
            'statut' => 'fermee',
            'fermee_at' => now(),
            'fermee_par' => Auth::id(),
        ]);

        // Message système
        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'systeme',
            'contenu' => 'Conversation fermée par ' . Auth::user()->name,
            'lu_at' => now(),
        ]);

        return redirect()
            ->route('admin.conversations.index')
            ->with('success', 'Conversation fermée');
    }

    /**
     * Marquer un message comme lu
     */
    public function markMessageRead(Conversation $conversation, Message $message)
    {
        $message->marquerLu();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}