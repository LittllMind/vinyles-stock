<?php

namespace App\Http\Controllers;

use App\Models\Bougie;
use App\Models\MouvementStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MouvementStockController extends Controller
{
    /**
     * Affiche la liste des mouvements de stock.
     */
    public function index(): View
    {
        $mouvements = MouvementStock::with(['stockable', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.mouvements.index', compact('mouvements'));
    }

    /**
     * Affiche le formulaire de création d'un mouvement.
     */
    public function create(Request $request): View
    {
        $bougieId = $request->query('bougie_id');
        $bougies = Bougie::orderBy('nom')->get();

        return view('admin.mouvements.create', compact('bougies', 'bougieId'));
    }

    /**
     * Enregistre un nouveau mouvement de stock.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'stockable_id' => 'required|exists:bougies,id',
            'stockable_type' => 'required|in:App\Models\Bougie',
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'raison' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        MouvementStock::create($validated);

        return redirect()
            ->route('admin.mouvements.index')
            ->with('success', 'Mouvement de stock enregistré avec succès.');
    }

    /**
     * Affiche les détails d'un mouvement.
     */
    public function show(MouvementStock $mouvement): View
    {
        return view('admin.mouvements.show', compact('mouvement'));
    }
}
