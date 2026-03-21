<?php

namespace App\Http\Controllers;

use App\Models\Bougie;
use Illuminate\Http\Request;

class BougieController extends Controller
{
    public function index()
    {
        $bougies = Bougie::orderBy('reference')->paginate(10);
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
            'nom' => 'required|string|max:200',
            'parfum' => 'required|string|max:100',
            'collection' => 'nullable|string|max:100',
            'format' => 'nullable|in:120g,200g,300g',
            'type_cire' => 'nullable|in:soja,paraffine,cire végétale,beeswax',
            'temps_brulure' => 'nullable|integer|gte:0',
            'notes' => 'nullable|string',
            'prix' => 'required|numeric|gte:0',
            'quantite' => 'integer|gte:0',
            'seuil_alerte' => 'integer|gte:0',
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
            'reference' => 'required|unique:bougies,reference,'.$bougie->id,
            'nom' => 'required|string|max:200',
            'parfum' => 'required|string|max:100',
            'collection' => 'nullable|string|max:100',
            'format' => 'nullable|in:120g,200g,300g',
            'type_cire' => 'nullable|in:soja,paraffine,cire végétale,beeswax',
            'temps_brulure' => 'nullable|integer|gte:0',
            'notes' => 'nullable|string',
            'prix' => 'required|numeric|gte:0',
            'quantite' => 'integer|gte:0',
            'seuil_alerte' => 'integer|gte:0',
        ]);

        $bougie->update($validated);
        
        return redirect()->route('admin.bougies.index')
            ->with('success', 'Bougie modifiée avec succès');
    }

    public function destroy(Bougie $bougie)
    {
        $bougie->delete();
        
        return redirect()->route('admin.bougies.index')
            ->with('success', 'Bougie supprimée avec succès');
    }
}
