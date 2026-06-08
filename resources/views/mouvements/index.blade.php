@extends('layouts.admin-art-print')

@section('title', 'Historique des Mouvements')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="admin-card-title" style="color: #16a34a;">Total Entrées</p>
                    <p class="text-3xl font-bold mt-1" style="color: #16a34a;">+{{ $stats['total_entrees'] }}</p>
                </div>
                <div class="text-4xl">📥</div>
            </div>
        </div>
        <div class="admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="admin-card-title" style="color: #dc2626;">Total Sorties</p>
                    <p class="text-3xl font-bold mt-1" style="color: #dc2626;">-{{ $stats['total_sorties'] }}</p>
                </div>
                <div class="text-4xl">📤</div>
            </div>
        </div>
        <div class="admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="admin-card-title" style="color: #7c3aed;">Aujourd'hui</p>
                    <p class="text-3xl font-bold mt-1" style="color: #7c3aed;">{{ $stats['aujourdhui'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="admin-card mb-8">
        <form method="GET" action="{{ route('mouvements.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm text-gray-500 mb-2">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-2">Produit</label>
                <select name="produit_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    @foreach($produitTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('produit_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-2">Du</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-2">Au</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-2">Référence</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Réf..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">Réinit</a>
            </div>
        </form>
    </div>

    {{-- Export --}}
    <div class="mb-6 flex justify-end">
        <a href="{{ route('mouvements.export', request()->all()) }}" class="btn btn-secondary">
            📥 Exporter CSV
        </a>
    </div>

    {{-- Tableau --}}
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Produit</th>
                    <th>Qté</th>
                    <th>Par</th>
                    <th>Référence</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mouvement)
                    <tr>
                        <td>
                            <div class="font-medium">{{ $mouvement->date_mouvement->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $mouvement->date_mouvement->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($mouvement->type === 'entree')
                                <span class="badge badge-ok">📥 Entrée</span>
                            @else
                                <span class="badge badge-danger">📤 Sortie</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm">{{ $mouvement->produit_libelle }}</span>
                            <div class="text-xs text-gray-400">ID: {{ $mouvement->produit_id }}</div>
                        </td>
                        <td>
                            <span class="font-semibold {{ $mouvement->type === 'entree' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($mouvement->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <span>{{ $mouvement->user?->name ?? 'Système' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm font-mono text-gray-400">{{ $mouvement->reference ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-gray-400">{{ $mouvement->notes ?? '-' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-400">
                            Aucun mouvement trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($mouvements->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-200">
                {{ $mouvements->links() }}
            </div>
        @endif
    </div>
@endsection
