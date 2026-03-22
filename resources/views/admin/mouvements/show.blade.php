@extends('layouts.admin')

@section('title', 'Détails du Mouvement')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Détails du mouvement</h1>
        <a href="{{ route('admin.mouvements.index') }}" class="btn btn-outline-secondary">
            ← Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header {{ $mouvement->isEntree() ? 'bg-success' : 'bg-danger' }} text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-{{ $mouvement->isEntree() ? 'down' : 'up' }}"></i>
                        Mouvement {{ $mouvement->isEntree() ? 'd\'entrée' : 'de sortie' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">ID</div>
                        <div class="col-sm-8">#{{ $mouvement->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Type</div>
                        <div class="col-sm-8">
                            @if($mouvement->isEntree())
                                <span class="badge bg-success">Entrée</span>
                            @else
                                <span class="badge bg-danger">Sortie</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Quantité</div>
                        <div class="col-sm-8">{{ $mouvement->quantite }} unité(s)</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Date</div>
                        <div class="col-sm-8">{{ $mouvement->created_at->format('d/m/Y \à H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Par</div>
                        <div class="col-sm-8">{{ $mouvement->user?->name ?? 'Système' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Raison</div>
                        <div class="col-sm-8">{{ $mouvement->raison ?? 'Non spécifiée' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box"></i> Produit concerné</h5>
                </div>
                <div class="card-body">
                    @if($mouvement->stockable)
                        <h5><a href="{{ route('admin.bougies.show', $mouvement->stockable) }}">
                            {{ $mouvement->stockable->nom }}
                        </a></h5>
                        <p class="text-muted">{{ $mouvement->stockable->reference }}</p>
                        
                        <div class="mt-4">
                            <h6>Stock actuel</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 25px;">
                                    @php
                                        $stock = $mouvement->stockable;
                                        $pourcentage = min(100, ($stock->quantite / max($stock->seuil_alerte * 4, 10)) * 100);
                                        $class = $stock->isEnAlerte() ? 'bg-danger' : 'bg-success';
                                    @endphp
                                    <div class="progress-bar {{ $class }}" role="progressbar" 
                                         style="width: {{ $pourcentage }}%"
                                         aria-valuenow="{{ $stock->quantite }}" aria-valuemin="0">
                                        {{ $stock->quantite }}
                                    </div>
                                </div>
                                <span class="ms-2 fw-bold" style="min-width: 60px;">{{ $stock->quantite }}</span>
                            </div>
                            @if($stock->isEnAlerte())
                                <div class="alert alert-warning mt-2 py-2">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Stock sous le seuil ({{ $stock->seuil_alerte }})
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            <i class="bi bi-info-circle"></i> Produit supprimé ou indisponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
