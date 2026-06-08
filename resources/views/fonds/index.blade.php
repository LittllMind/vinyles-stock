@extends('layouts.admin-art-print')

@section('title', 'Gestion des Pochettes (Fonds)')

@section('page-actions')
    @if(auth()->user()->isAdmin())
        <span class="badge badge-warning">Mode Admin</span>
    @else
        <span class="badge badge-info">Mode Employé (lecture seule)</span>
    @endif
@endsection

@section('content')
    <div x-data="{ 
        showStockModal: false, 
        selectedFond: null,
        stockAction: 'increment',
        stockQuantity: 1,
        
        openStockModal(fond) {
            this.selectedFond = fond;
            this.stockAction = 'increment';
            this.stockQuantity = 1;
            this.showStockModal = true;
        },
        
        submitStockForm() {
            if (this.selectedFond) {
                document.getElementById('stock-form-' + this.selectedFond.id).submit();
            }
        }
    }">
        <!-- Tableau des fonds -->
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Visuel</th>
                        <th class="text-center">Qté</th>
                        <th class="text-right">Prix achat</th>
                        <th class="text-right">Montant stock</th>
                        <th class="text-right">Prix vente</th>
                        <th class="text-right">Valeur stock</th>
                        <th class="text-center">Status</th>
                        @if(auth()->user()->isAdmin())
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($fonds as $fond)
                        <tr>
                            <td>
                                <div class="font-semibold {{ $fond['type'] === 'Miroir' ? 'text-blue-600' : 'text-yellow-600' }}">
                                    {{ $fond['type'] }}
                                </div>
                            </td>
                            <td class="text-sm text-gray-500">{{ $fond['visuel'] }}</td>
                            <td class="text-center">
                                <span class="text-xl font-bold {{ $fond['quantite'] === 0 ? 'text-red-600' : '' }}">
                                    {{ $fond['quantite'] }}
                                </span>
                            </td>
                            <td class="text-right">{{ number_format($fond['prix_achat'], 2, ',', ' ') }} €</td>
                            <td class="text-right text-orange-600 font-semibold">
                                {{ number_format($fond['montant_stock'], 2, ',', ' ') }} €
                            </td>
                            <td class="text-right">{{ number_format($fond['prix_vente'], 2, ',', ' ') }} €</td>
                            <td class="text-right text-green-600 font-bold">
                                {{ number_format($fond['valeur_stock'], 2, ',', ' ') }} €
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $fond['status_class'] }}">{{ $fond['status'] }}</span>
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="text-center">
                                    <button @click="openStockModal({{ json_encode($fond) }})" class="btn btn-primary">
                                        Modifier stock
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 9 : 8 }}" class="text-center py-8 text-gray-400">
                                Aucune pochette configurée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold">
                    <tr class="border-t-2 border-gray-200">
                        <td class="text-purple-600" colspan="2">TOTAL</td>
                        <td class="text-center">{{ $totaux['quantite_totale'] }}</td>
                        <td></td>
                        <td class="text-right text-orange-600">
                            {{ number_format($totaux['montant_investi'], 2, ',', ' ') }} €
                        </td>
                        <td></td>
                        <td class="text-right text-green-600">
                            {{ number_format($totaux['valeur_totale'], 2, ',', ' ') }} €
                        </td>
                        <td></td>
                        @if(auth()->user()->isAdmin())
                            <td></td>
                        @endif
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="text-purple-600" colspan="6">MARGE POTENTIELLE</td>
                        <td class="text-right text-pink-600 font-bold text-lg">
                            +{{ number_format($totaux['marge_totale'], 2, ',', ' ') }} €
                        </td>
                        <td colspan="{{ auth()->user()->isAdmin() ? 2 : 1 }}"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Résumé cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="admin-card">
                <div class="text-gray-400 text-sm">Stock total</div>
                <div class="text-2xl font-bold">{{ $totaux['quantite_totale'] }}</div>
            </div>
            <div class="admin-card">
                <div class="text-gray-400 text-sm">Investissement</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($totaux['montant_investi'], 2, ',', ' ') }} €</div>
            </div>
            <div class="admin-card">
                <div class="text-gray-400 text-sm">Valeur stock</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($totaux['valeur_totale'], 2, ',', ' ') }} €</div>
            </div>
            <div class="admin-card">
                <div class="text-gray-400 text-sm">Marge potentielle</div>
                <div class="text-2xl font-bold text-pink-600">+{{ number_format($totaux['marge_totale'], 2, ',', ' ') }} €</div>
            </div>
        </div>

        {{-- Modal modification stock (Admin uniquement) --}}
        @if(auth()->user()->isAdmin())
            <div x-show="showStockModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.away="showStockModal = false">
                <div class="bg-white p-6 rounded-lg border border-gray-200 max-w-md w-full mx-4" @click.stop>
                    <h3 class="text-xl font-bold mb-4">
                        Modifier le stock : <span x-text="selectedFond?.type" class="text-purple-600"></span>
                    </h3>
                    <p class="text-gray-500 mb-4">
                        Stock actuel : <span x-text="selectedFond?.quantite" class="font-bold"></span>
                    </p>
                    
                    <form :id="'stock-form-' + selectedFond?.id" :action="'/fonds/' + selectedFond?.id + '/stock'" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Action</label>
                            <select x-model="stockAction" name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="increment">➕ Ajouter au stock</option>
                                <option value="decrement">➖ Retirer du stock</option>
                                <option value="set">📝 Définir le stock</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Quantité</label>
                            <input type="number" x-model="stockQuantity" name="quantite" min="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" @click="showStockModal = false" class="flex-1 btn btn-secondary">Annuler</button>
                            <button type="submit" class="flex-1 btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
