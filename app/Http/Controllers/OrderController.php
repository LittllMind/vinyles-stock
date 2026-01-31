<?php

namespace App\Http\Controllers;

use App\Services\CartService;

class OrderController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function create(\Illuminate\Http\Request $request)
    {
        // If the user just logged in and merge cookies exist, try merging now to ensure cart is ready
        if (auth()->check()) {
            $source = $request->cookie('cart_merge_source_id');
            $anonId = $request->cookie('anon_cart_id') ? intval($request->cookie('anon_cart_id')) : null;

            try {
                $merged = $this->cartService->mergeAnonymousCart($source, $anonId);
                if ($merged) {
                    \Illuminate\Support\Facades\Log::info('Orders.create: merged anonymous cart on page load', ['merged' => $merged, 'user_id' => auth()->id()]);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Orders.create: merge failed', ['error' => $e->getMessage()]);
            }
        }

        $cart = $this->cartService->getCart();

        // Log cart when rendering checkout page
        \Illuminate\Support\Facades\Log::info('Orders.create cart debug', ['cart_id' => $cart->id ?? null, 'count' => $cart->items()->count()]);

        // Render checkout form (requires auth)
        return view('orders.create', [
            'cart' => $cart,
        ]);
    }

    /**
     * Prepare the checkout flow: decide where to redirect based on auth and saved addresses.
     */
    public function prepare()
    {
        if (!auth()->check()) {
            // Ensure user is redirected back to checkout after login
            session(['url.intended' => route('orders.create')]);
            return redirect()->route('login');
        }

        $user = auth()->user();
        $lastOrder = $user->orders()->latest()->first();

        // If we have a recent order with an address, go directly to payment
        if ($lastOrder && !empty($lastOrder->adresse)) {
            return redirect()->route('orders.payment');
        }

        // Otherwise ask user to verify/add delivery information on the checkout form
        return redirect()->route('orders.create')->with('info', 'Veuillez vérifier vos informations et renseigner une adresse de livraison.');
    }

    /**
     * Payment page (simple placeholder for payment step). Prefills data from last order.
     */
    public function payment()
    {
        $cart = $this->cartService->getCart();

        $user = auth()->user();
        $lastOrder = $user->orders()->latest()->first();

        if (!$lastOrder || empty($lastOrder->adresse)) {
            return redirect()->route('orders.create')->with('info', 'Veuillez renseigner une adresse avant de procéder au paiement.');
        }

        $prefill = [
            'prenom' => $lastOrder->prenom,
            'nom' => $lastOrder->nom,
            'email' => $lastOrder->email,
            'telephone' => $lastOrder->telephone,
            'adresse' => $lastOrder->adresse,
            'code_postal' => $lastOrder->code_postal,
            'ville' => $lastOrder->ville,
            'notes_client' => $lastOrder->notes_client,
        ];

        return view('orders.payment', [
            'cart' => $cart,
            'prefill' => $prefill,
        ]);
    }

    /**
     * Liste des commandes pour l'utilisateur connecté (espace Mon compte)
     */
    public function myOrders()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Détail d'une commande appartenant à l'utilisateur connecté
     */
    public function myOrdersShow(\App\Models\Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('orders.show', compact('order'));
    }

    public function store()
    {
        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:1000',
            'code_postal' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'notes_client' => 'nullable|string|max:2000',
        ]);

        $cart = $this->cartService->getCart();

        // Check stock
        $stockErrors = $this->cartService->checkStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')->with('error', implode(' ', $stockErrors));
        }

        // Create order
        $order = \App\Models\Order::create([
            'numero_commande' => \App\Models\Order::generateNumero(),
            'user_id' => auth()->id() ?? null,
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'code_postal' => $validated['code_postal'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'total' => $cart->total(),
            'statut' => 'en_attente',
        ]);

        foreach ($cart->items as $item) {
            // Log the item being processed
            \Illuminate\Support\Facades\Log::info('Checkout processing item', [
                'cart_id' => $cart->id,
                'item' => $item->toArray(),
                'vinyle' => $item->vinyle ? [
                    'id' => $item->vinyle->id ?? null,
                    'nom' => $item->vinyle->nom ?? null,
                    'quantite' => $item->vinyle->quantite ?? null,
                ] : null,
            ]);

            $order->items()->create([
                'vinyle_id' => $item->vinyle_id,
                'fond_id' => $item->fond_id,
                'titre_vinyle' => $item->vinyle->nom ?? 'Inconnu',
                'artiste_vinyle' => $item->vinyle->artiste ?? null,
                'reference_vinyle' => $item->vinyle->referance ?? null,
                'quantite' => $item->quantite,
                'prix_unitaire' => $item->prix_unitaire,
                'total' => $item->prix_unitaire * $item->quantite,
            ]);

            // Decrement stock
            if ($item->vinyle) {
                $item->vinyle->decrement('quantite', $item->quantite);
            }
            if ($item->fond) {
                $item->fond->decrement('quantite', $item->quantite);
            }
        }

        // Clear cart
        $this->cartService->clear();

        // Send mails
        try {
            \Illuminate\Support\Facades\Mail::to($order->email)->send(new \App\Mail\OrderConfirmationToCustomer($order));
            $adminEmail = config('app.admin_email') ?? env('ADMIN_EMAIL');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\OrderRecapToAdmin($order));
            }
        } catch (\Throwable $e) {
            // don't fail the request if mailing fails; log and continue
            \Illuminate\Support\Facades\Log::error('Failed to send order mails', ['error' => $e->getMessage()]);
        }

        return redirect()->route('vinyles.index')->with('success', 'Commande enregistrée, numéro : ' . $order->numero_commande);

        // Check stock
        $stockErrors = $this->cartService->checkStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')->with('error', implode(' ', $stockErrors));
        }

        // Create order
        $order = \App\Models\Order::create([
            'numero_commande' => \App\Models\Order::generateNumero(),
            'user_id' => auth()->id() ?? null,
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'code_postal' => $validated['code_postal'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'total' => $cart->total(),
            'statut' => 'en_attente',
        ]);

        foreach ($cart->items as $item) {
            // TEMP LOG: inspect item at checkout
            \Illuminate\Support\Facades\Log::info('Checkout processing item', ['cart_id' => $cart->id, 'item' => $item->toArray(), 'vinyle' => $item->vinyle?->toArray()]);

            $order->items()->create([
                'vinyle_id' => $item->vinyle_id,
                'fond_id' => $item->fond_id,
                'titre_vinyle' => $item->vinyle->nom ?? 'Inconnu',
                'artiste_vinyle' => $item->vinyle->artiste ?? null,
                'reference_vinyle' => $item->vinyle->referance ?? null,
                'quantite' => $item->quantite,
                'prix_unitaire' => $item->prix_unitaire,
                'total' => $item->prix_unitaire * $item->quantite,
            ]);

            // Decrement stock
            if ($item->vinyle) {
                $item->vinyle->decrement('quantite', $item->quantite);
            }
            if ($item->fond) {
                $item->fond->decrement('quantite', $item->quantite);
            }
        }

        // Clear cart
        $this->cartService->clear();

        // Send mails
        try {
            \Illuminate\Support\Facades\Mail::to($order->email)->send(new \App\Mail\OrderConfirmationToCustomer($order));
            $adminEmail = config('app.admin_email') ?? env('ADMIN_EMAIL');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\OrderRecapToAdmin($order));
            }
        } catch (\Throwable $e) {
            // don't fail the request if mailing fails; log and continue
            \Illuminate\Support\Facades\Log::error('Failed to send order mails', ['error' => $e->getMessage()]);
        }

        return redirect()->route('vinyles.index')->with('success', 'Commande enregistrée, numéro : ' . $order->numero_commande);
    }
}
