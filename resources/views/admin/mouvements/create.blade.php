@extends('layouts.admin')

@section('title', 'Nouveau Mouvement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Nouveau mouvement de stock</h1>
                <a href="{{ route('admin.mouvements.index') }}" class="btn btn-outline-secondary">
                    Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Enregistrer un mouvement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mouvements.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="stockable_type" value="App\Models\Bougie">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="stockable_id" class="form-label">Bougie *</label>
                            <select name="stockable_id" id="stockable_id" class="form-select @error('stockable_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez une bougie...</option>
                                @foreach($bougies as $bougie)
                                    <option value="{{ $bougie->id }}" {{ ($bougieId ?? old('stockable_id')) == $bougie->id ? 'selected' : '' }}>
                                        {{ $bougie->nom }} - {{ $bougie->reference }} 
                                        (Stock: {{ $bougie->quantite }})
                                    </option>
                                @endforeach
                            </select>
                            @error('stockable_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type de mouvement *</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Choisissez le type...</option>
                                <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>
                                    📥 Entrée (réception / stock initial)
                                </option>
                                <option value="sortie" {{ old('type') == 'sortie' ? 'selected' : '' }}>
                                    📤 Sortie (vente / perte)
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité *</label>
                            <input type="number" name="quantite" id="quantite" min="1" 
                                   class="form-control @error('quantite') is-invalid @enderror"
                                   value="{{ old('quantite') }}" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="raison" class="form-label">Raison / Notes</label>
                            <textarea name="raison" id="raison" rows="3"
                                      class="form-control @error('raison') is-invalid @enderror"
                                      placeholder="Ex: Réception fournisseur, vente client #123...">{{ old('raison') }}</textarea>
                            @error('raison')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.mouvements.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg"></i> Enregistrer le mouvement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
