@extends('layouts.admin')

@section('title', 'Mouvements de Stock')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Mouvements de Stock</h1>
        <a href="{{ route('admin.mouvements.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Nouveau mouvement
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Par</th>
                            <th>Raison</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mouvements as $mouvement)
                            <tr>
                                <td>{{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($mouvement->stockable)
                                        <a href="{{ route('admin.bougies.show', $mouvement->stockable) }}">
                                            {{ $mouvement->stockable->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">Produit supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mouvement->isEntree())
                                        <span class="badge bg-success">Entrée</span>
                                    @else
                                        <span class="badge bg-danger">Sortie</span>
                                    @endif
                                </td>
                                <td>{{ $mouvement->quantite }}</td>
                                <td>{{ $mouvement->user?->name ?? 'Système' }}</td>
                                <td>{{ $mouvement->raison ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.mouvements.show', $mouvement) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Aucun mouvement de stock enregistré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $mouvements->links() }}
        </div>
    </div>
</div>
@endsection
