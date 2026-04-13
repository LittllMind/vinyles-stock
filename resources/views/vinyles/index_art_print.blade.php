{{-- resources/views/vinyles/index_art_print.blade.php --}}
{{-- Liste Vinyles - Admin ART PRINT --}}

@extends('layouts.admin-art-print')

@section('title', 'Vinyles')

@section('page-actions')
    <a href="{{ route('vinyles.create') }}" class="btn btn-primary">
        + Ajouter un vinyle
    </a>
@endsection

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="admin-card">
        <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Total</p>
        <p class="text-3xl font-light">{{ $vinyles->total() }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">En stock</p>
        <p class="text-3xl font-light text-green-600">{{ $vinyles->where('quantite', '>', 0)->count() }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Rupture</p>
        <p class="text-3xl font-light text-red-600">{{ $vinyles->where('quantite', '<=', 0)->count() }}</p>
    </div>
    
    <div class="admin-card">
        <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Valeur stock</p>
        <p class="text-3xl font-light">{{ number_format($vinyles->sum(fn($v) => $v->quantite * $v->prix) / 100, 2, ',', ' ') }} €</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Tous les vinyles</h2>
        <div class="flex gap-2">
            <input type="text" placeholder="Rechercher..." class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                <option>Tous les stocks</option>
                <option>En stock</option>
                <option>Rupture</option>
            </select>
        </div>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Vinyle</th>
                <th>Artiste</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vinyles ?? [] as $vinyle)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">💿</div>
                        <span class="font-medium">{{ $vinyle->nom ?? $vinyle->nom_complet ?? '#' . $vinyle->id }}</span>
                    </div>
                </td>
                <td>{{ $vinyle->artiste ?? '-' }}</td>
                <td>{{ number_format($vinyle->prix / 100, 2, ',', ' ') }} €</td>
                <td>{{ $vinyle->quantite }}</td>
                <td>
                    @if($vinyle->quantite > 0)
                        <span class="badge badge-ok">Disponible</span>
                    @else
                        <span class="badge badge-danger">Rupture</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <a href="#" class="btn-icon" title="Modifier">✏️</a>
                    <a href="#" class="btn-icon" title="Voir">👁️</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-400">
                    Aucun vinyle. <a href="#" class="underline">Créer le premier</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection