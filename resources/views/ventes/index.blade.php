@extends('layouts.admin-art-print')

@section('title', 'Ventes du ' . $currentDate->format('d/m/Y'))

@section('page-actions')
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">⏮ Jour précédent</a>
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">Jour suivant ⏭</a>
    <a href="{{ route('ventes.create') }}" class="btn btn-primary">+ Nouvelle vente</a>
@endsection

@section('content')
    
    {{-- STATISTIQUES DU JOUR --}}
    <div class="admin-card mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-gray-400 text-sm">CA total du jour</div>
                <div class="text-3xl font-bold">{{ number_format($caTotal, 2, ',', ' ') }} €</div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Vinyles vendus</div>
                <div>
                    <strong>{{ $nbVinylesTotal }}</strong>
                    @if ($nbVinylesTotal > 0)
                        (dont <strong>{{ $nbMiroirs }}</strong> miroir)
                    @endif
                </div>
            </div>
            <div>
                <div class="text-gray-400 text-sm">Paiements</div>
                <div class="flex flex-wrap gap-2 mt-1">
                    @forelse($caParMode as $mode => $montant)
                        <span class="badge badge-info">
                            {{ ucfirst($mode) }} : {{ number_format($montant, 2, ',', ' ') }} €
                        </span>
                    @empty
                        <span class="text-gray-400">Aucun paiement ce jour</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- LISTE DES VENTES DU JOUR --}}
    <div class="admin-card mb-8">
        <h3 class="admin-card-title mb-4">Ventes du jour</h3>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventes as $vente)
                        <tr>
                            <td>{{ $vente->created_at->format('H:i') }}</td>
                            <td>{{ $vente->lignes->count() }} ligne(s)</td>
                            <td><strong>{{ number_format($vente->total, 2, ',', ' ') }} €</strong></td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($vente->mode_paiement) }}</span>
                            </td>
                            <td class="flex gap-2">
                                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-sm btn-secondary">Détails</a>
                                <form method="POST" action="{{ route('ventes.destroy', $vente) }}" onsubmit="return confirm('Annuler cette vente ? Les stocks seront restaurés.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Aucune vente pour ce jour</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STATS PAR ARTISTE / MODÈLE --}}
    @if ($parArtiste->count())
        <div class="admin-card mb-8">
            <h3 class="admin-card-title mb-4">Statistiques par artiste / modèle</h3>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Artiste / Modèle</th>
                            <th>Quantité</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parArtiste as $libelle => $stats)
                            <tr>
                                <td>{{ $libelle }}</td>
                                <td>{{ $stats['quantite'] }}</td>
                                <td>{{ number_format($stats['ca'], 2, ',', ' ') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- STATS PAR TYPE DE FOND --}}
    @if ($parFond->count())
        <div class="admin-card">
            <h3 class="admin-card-title mb-4">Statistiques par type de fond</h3>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Fond</th>
                            <th>Quantité</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parFond as $fond => $stats)
                            <tr>
                                <td>{{ ucfirst($fond) }}</td>
                                <td>{{ $stats['quantite'] }}</td>
                                <td>{{ number_format($stats['ca'], 2, ',', ' ') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
