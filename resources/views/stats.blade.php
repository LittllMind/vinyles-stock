@extends('layouts.admin-art-print')

@section('title', 'Statistiques - ' . $periodeLabel)

@section('content')
    <!-- Filtre de période -->
    <div class="flex items-center justify-end mb-8">
        <form method="GET" action="{{ route('stats') }}">
            <select name="periode" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="30j" {{ $periode === '30j' ? 'selected' : '' }}>30 derniers jours</option>
                <option value="3m" {{ $periode === '3m' ? 'selected' : '' }}>3 derniers mois</option>
                <option value="12m" {{ $periode === '12m' ? 'selected' : '' }}>12 derniers mois</option>
                <option value="all" {{ $periode === 'all' ? 'selected' : '' }}>Depuis le début</option>
            </select>
        </form>
    </div>

    {{-- Cartes principales --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title">Chiffre d'affaires</span>
                <span class="text-2xl">💰</span>
            </div>
            <div class="text-2xl font-bold">{{ number_format($chiffreAffaires, 2, ',', ' ') }} €</div>
            @if($caMoyenParJour > 0)
                <div class="text-sm text-gray-400 mt-2">~{{ number_format($caMoyenParJour, 2, ',', ' ') }} €/jour</div>
            @endif
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title">Ventes</span>
                <span class="text-2xl">📦</span>
            </div>
            <div class="text-2xl font-bold">{{ $totalVentes }}</div>
            @if($panierMoyen > 0)
                <div class="text-sm text-gray-400 mt-2">Panier moyen : {{ number_format($panierMoyen, 2, ',', ' ') }} €</div>
            @endif
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title">Vinyles vendus</span>
                <span class="text-2xl">💿</span>
            </div>
            <div class="text-2xl font-bold">{{ $nbVinylesVendus }}</div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title">Marge brute</span>
                <span class="text-2xl">📈</span>
            </div>
            <div class="text-2xl font-bold {{ $margeBrute >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format($margeBrute, 2, ',', ' ') }} €
            </div>
        </div>
    </div>

    {{-- Stock alerts --}}
    @if($stockBas > 0 || $rupturesStock > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @if($stockBas > 0)
                <div class="admin-card border-l-4 border-yellow-400">
                    <div class="text-sm text-gray-400">Stock bas (<= 3)</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stockBas }} vinyle(s)</div>
                </div>
            @endif
            @if($rupturesStock > 0)
                <div class="admin-card border-l-4 border-red-400">
                    <div class="text-sm text-gray-400">Ruptures de stock</div>
                    <div class="text-2xl font-bold text-red-600">{{ $rupturesStock }} vinyle(s)</div>
                </div>
            @endif
        </div>
    @endif

    {{-- Répartition par mode de paiement --}}
    @if($paiements->count() > 0)
        <div class="admin-card mb-8">
            <h3 class="admin-card-title mb-4">Répartition par mode de paiement</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($paiements as $paiement)
                    <div class="p-4 rounded-lg border text-center">
                        <div class="text-sm text-gray-400 mb-1">{{ ucfirst($paiement->mode_paiement) }}</div>
                        <div class="text-xl font-bold">{{ number_format($paiement->total, 2, ',', ' ') }} €</div>
                        <div class="text-xs text-gray-400">{{ $paiement->nb_ventes }} vente(s)</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Top modèles vendus --}}
    @if($topModelesVendus->count() > 0)
        <div class="admin-card mb-8">
            <h3 class="admin-card-title mb-4">Top Modèles Vendus</h3>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Modèle</th>
                            <th>Qté vendue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topModelesVendus as $index => $modele)
                            <tr>
                                <td class="font-bold">#{{ $index + 1 }}</td>
                                <td>{{ $modele->modele }}</td>
                                <td>{{ $modele->total_vendus }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Évolution par période --}}
    @if($ventesParPeriode->count() > 0)
        <div class="admin-card">
            <h3 class="admin-card-title mb-4">Évolution ({{ $periodeLabel }})</h3>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Période</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventesParPeriode as $periodeData)
                            <tr>
                                <td>{{ $periodeData->periode }}</td>
                                <td>{{ number_format($periodeData->ca, 2, ',', ' ') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
