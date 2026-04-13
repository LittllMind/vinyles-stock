<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des conversations du client connecté
     */
    public function index()
    {
        $conversations = Conversation::with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->withCount(['messages' => function ($q) {
                $q->whereNull('lu_at')->where('type', 'admin');
            }])
            ->where('client_id', Auth::id())
            ->whereIn('statut', ['active', 'fermee'])
            ->orderBy('dernier_message_at', 'desc')
            ->paginate(10);

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Afficher une conversation
     */
    public function show(Conversation $conversation)
    {
        // Vérifier que le client est propriétaire
        if ($conversation->client_id !== Auth::id()) {
            abort(403, 'Accès interdit');
        }

        $conversation->load(['messages.user', 'order']);

        // Marquer les messages de l'admin comme lus
        $conversation->messages()
            ->whereNull('lu_at')
            ->where('type', 'admin')
            ->update(['lu_at' => now()]);

        return view('conversations.show', compact('conversation'));
    }

    /**
     * Créer une nouvelle conversation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string|min:10|max:5000',
        ]);

        $conversation = Conversation::create([
            'client_id' => Auth::id(),
            'sujet' => $validated['sujet'],
            'statut' => 'active',
        ]);

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'client',
            'contenu' => $validated['contenu'],
        ]);

        $conversation->mettreAJourDernierMessage();

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Message envoyé. Nous vous répondrons dès que possible.');
    }

    /**
     * Répondre à une conversation
     */
    public function reply(Request $request, Conversation $conversation)
    {
        // Vérifier propriétaire
        if ($conversation->client_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier conversation active
        if ($conversation->statut !== 'active') {
            return redirect()
                ->back()
                ->with('error', 'Cette conversation est fermée.');
        }

        $validated = $request->validate([
            'contenu' => 'required|string|min:2|max:5000',
        ]);

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'client',
            'contenu' => $validated['contenu'],
        ]);

        $conversation->mettreAJourDernierMessage();

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Réponse envoyée');
    }

    /**
     * Créer une conversation depuis une commande
     */
    public function storeFromOrder(Request $request, Order $order)
    {
        // Vérifier propriétaire
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'contenu' => 'required|string|min:10|max:5000',
        ]);

        $conversation = Conversation::create([
            'client_id' => Auth::id(),
            'order_id' => $order->id,
            'sujet' => 'Question sur commande #' . $order->numero_commande,
            'statut' => 'active',
        ]);

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'type' => 'client',
            'contenu' => $validated['contenu'],
        ]);

        $conversation->mettreAJourDernierMessage();

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Message envoyé concernant votre commande.');
    }
}