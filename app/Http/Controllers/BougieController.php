<?php

namespace App\Http\Controllers;

use App\Models\Bougie;
use Illuminate\Http\Request;

class BougieController extends Controller
{
    public function index()
    {
        $bougies = Bougie::paginate(10);
        return view('admin.bougies.index', compact('bougies'));
    }

    public function create()
    {
        return view('admin.bougies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|unique:bougies',
            'parfum' => 'required',
            'nom' => 'required',
            'collection' => 'nullable',
            'format' => 'nullable',
            'type_cire' => 'nullable',
            'temps_brulure' => 'nullable|integer',
            'notes' => 'nullable',
            'prix' => 'required|numeric',
            'quantite' => 'nullable|integer',
            'seuil_alerte' => 'nullable|integer',
        ]);

        Bougie::create($validated);

        return redirect()->route('admin.bougies.index')
            ->with('success', 'Bougie créée avec succès');
    }

    public function show(Bougie $bougie)
    {
        return view('admin.bougies.show', compact('bougie'));
    }

    public function edit(Bougie $bougie)
    {
        return view('admin.bougies.edit', compact('bougie'));
    }

    public function update(Request $request, Bougie $bougie)
    {
        $validated = $request->validate([
            'reference' => 'required|unique:bougies,reference,' . $bougie->id,
            'parfum' => 'required',
            'nom' => 'required',
            'collection' => 'nullable',
            'format' => 'nullable',
            'type_cire' => 'nullable',
            'temps_brulure' => 'nullable|integer',
            'notes' => 'nullable',
            'prix' => 'required|numeric',
            'quantite' => 'nullable|integer',
            'seuil_alerte' => 'nullable|integer',
        ]);

        $bougie->update($validated);

        return redirect()->route('admin.bougies.index')
            ->with('success', 'Bougie mise à jour avec succès');
    }

    public function destroy(Bougie $bougie)
    {
        $bougie->delete();

        return redirect()->route('admin.bougies.index')
            ->with('success', 'Bougie supprimée avec succès');
    }
}
