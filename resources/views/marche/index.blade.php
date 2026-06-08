{{-- resources/views/marche/index.blade.php --}}
{{-- Mode Marché — thème ART PRINT unifié --}}

@extends('layouts.admin-art-print')

@section('title', 'Mode Marché — Vente sur place')

@section('page-actions')
    <span class="badge badge-warning">{{ now()->format('d/m/Y') }}</span>
@endsection

@section('content')
<div x-data="modeMarche()" @keydown.escape="showCart = false">

    {{-- Stats du jour --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="admin-card text-center">
            <div class="text-gray-400 text-sm uppercase tracking-wider">Ventes aujourd'hui</div>
            <div class="text-3xl font-bold mt-1" x-text="stats.nb_ventes">0</div>
        </div>
        <div class="admin-card text-center">
            <div class="text-gray-400 text-sm uppercase tracking-wider">Total journée</div>
            <div class="text-3xl font-bold mt-1 text-green-600" x-text="formatPrice(stats.total_jour)">0 €</div>
        </div>
        <div class="admin-card text-center">
            <div class="text-gray-400 text-sm uppercase tracking-wider">Panier en cours</div>
            <div class="text-3xl font-bold mt-1 text-purple-600" x-text="cartItemCount">0</div>
        </div>
    </div>

    {{-- Barre d'actions --}}
    <div class="flex gap-3 mb-6">
        <button @click="showCart = true"
            class="btn btn-primary"
            :class="cart.length > 0 ? 'ring-2 ring-purple-500/30' : ''">
            🛒 Panier <span x-show="cart.length > 0" x-text="'(' + cartItemCount + ')'" class="ml-1 bg-white/20 px-2 py-0.5 rounded-full text-sm"></span>
        </button>
        <button @click="loadVentesJour()" class="btn btn-secondary">
            📊 Ventes du jour
        </button>
    </div>

    {{-- Recherche --}}
    <div class="mb-6">
        <input type="text" x-model="search" placeholder="🔍 Rechercher un vinyle..."
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-200 transition"
            autofocus>
    </div>

    {{-- Grille de vinyles --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <template x-for="vinyle in filteredVinyles" :key="vinyle.id">
            <div @click="addToCart(vinyle)"
                class="admin-card p-0 overflow-hidden cursor-pointer transition-all hover:shadow-md active:scale-95"
                :class="isInCart(vinyle.id) ? 'ring-2 ring-purple-500' : ''">

                {{-- Image --}}
                <div class="aspect-square bg-gray-100 relative">
                    <img :src="vinyle.image_url || '/images/no-image.png'" :alt="vinyle.nom"
                        class="w-full h-full object-cover">
                    <div x-show="vinyle.quantite <= 3" x-cloak
                        class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded font-bold"
                        x-text="vinyle.quantite + ' restant'">
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <h3 class="font-semibold text-sm text-gray-900 truncate" x-text="vinyle.nom"></h3>
                    <p class="text-xs text-gray-500 truncate" x-text="vinyle.artiste_principale"></p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-lg font-bold text-purple-600" x-text="formatPrice(vinyle.prix)"></span>
                        <span x-show="isInCart(vinyle.id)" class="text-green-600 text-xl">✓</span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Message si vide --}}
    <div x-show="filteredVinyles.length === 0" x-cloak class="text-center py-12">
        <div class="text-4xl mb-2">🔍</div>
        <p class="text-gray-400">Aucun vinyle trouvé</p>
    </div>

    {{-- Drawer Panier --}}
    <div x-show="showCart" x-cloak
        class="fixed inset-0 z-50"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div @click="showCart = false" class="absolute inset-0 bg-black/50"></div>

        {{-- Drawer --}}
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white border-l border-gray-200 flex flex-col shadow-xl"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            {{-- Header --}}
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-xl font-bold">🛒 Panier</h2>
                <button @click="showCart = false" class="text-gray-400 hover:text-gray-900 text-2xl">&times;</button>
            </div>

            {{-- Items --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="admin-card p-3">
                        <div class="flex gap-3">
                            <img :src="item.image_url || '/images/no-image.png'" class="w-16 h-16 object-cover rounded-lg bg-gray-100">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm truncate" x-text="item.nom"></h4>
                                <p class="text-xs text-gray-500" x-text="item.artiste_principale"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <button @click="updateQuantity(index, -1)" class="w-8 h-8 rounded bg-gray-100 hover:bg-gray-200 flex items-center justify-center font-bold">-</button>
                                    <span x-text="item.quantite" class="w-8 text-center font-bold"></span>
                                    <button @click="updateQuantity(index, 1)" class="w-8 h-8 rounded bg-gray-100 hover:bg-gray-200 flex items-center justify-center font-bold">+</button>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-purple-600" x-text="formatPrice(item.prix * item.quantite)"></div>
                                <button @click="removeFromCart(index)" class="text-red-500 text-xs mt-1 hover:text-red-700">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Panier vide --}}
                <div x-show="cart.length === 0" class="text-center py-8 text-gray-400">
                    <div class="text-4xl mb-2">🛒</div>
                    <p>Panier vide</p>
                    <p class="text-sm">Cliquez sur un vinyle pour l'ajouter</p>
                </div>
            </div>

            {{-- Footer avec total et paiement --}}
            <div class="p-4 border-t border-gray-200 bg-gray-50 space-y-3">
                {{-- Réduction --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Réduction:</span>
                    <input type="number" x-model.number="reduction" min="0" step="0.5"
                        class="w-24 border border-gray-300 rounded-lg px-3 py-1 text-right">
                    <span class="text-gray-500">€</span>
                </div>

                {{-- Total --}}
                <div class="flex items-center justify-between text-xl">
                    <span class="font-bold">Total:</span>
                    <span class="font-bold text-purple-600" x-text="formatPrice(cartTotal - reduction)"></span>
                </div>

                {{-- Modes de paiement --}}
                <div class="grid grid-cols-2 gap-2">
                    <button @click="modePaiement = 'cash'"
                        :class="modePaiement === 'cash' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300'"
                        class="py-3 rounded-lg border-2 font-semibold transition">
                        💵 Espèces
                    </button>
                    <button @click="modePaiement = 'cb_terminal'"
                        :class="modePaiement === 'cb_terminal' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300'"
                        class="py-3 rounded-lg border-2 font-semibold transition">
                        💳 CB
                    </button>
                    <button @click="modePaiement = 'cheque'"
                        :class="modePaiement === 'cheque' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-700 border-gray-300'"
                        class="py-3 rounded-lg border-2 font-semibold transition">
                        📝 Chèque
                    </button>
                    <button @click="modePaiement = 'virement'"
                        :class="modePaiement === 'virement' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300'"
                        class="py-3 rounded-lg border-2 font-semibold transition">
                        🏦 Virement
                    </button>
                </div>

                {{-- Notes vendeur --}}
                <input type="text" x-model="notesVendeur" placeholder="Notes (optionnel)..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">

                {{-- Identifiant client --}}
                <input type="text" x-model="affichageClient" placeholder="Nom client / Table / Ref..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">

                {{-- Bouton valider --}}
                <button @click="validerVente()"
                    :disabled="cart.length === 0 || !modePaiement || loading"
                    :class="cart.length === 0 || !modePaiement ? 'bg-gray-300 cursor-not-allowed' : 'bg-gray-900 hover:bg-gray-800 text-white'"
                    class="w-full py-4 rounded-lg font-bold text-lg transition flex items-center justify-center gap-2">
                    <span x-show="loading" class="animate-spin">⏳</span>
                    <span x-text="loading ? 'Validation...' : '✓ Valider la vente'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Ventes du jour --}}
    <div x-show="showVentesJour" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @keydown.escape="showVentesJour = false">
        <div class="bg-white rounded-lg max-w-lg w-full max-h-[80vh] flex flex-col border border-gray-200 shadow-xl">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-bold">📊 Ventes du jour</h2>
                <button @click="showVentesJour = false" class="text-gray-400 hover:text-gray-900 text-2xl">&times;</button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <div class="space-y-2">
                    <template x-for="vente in ventesJour" :key="vente.id">
                        <div class="admin-card p-3 flex items-center justify-between">
                            <div>
                                <div class="font-semibold" x-text="vente.numero"></div>
                                <div class="text-sm text-gray-500">
                                    <span x-text="vente.heure"></span> -
                                    <span x-text="vente.client || 'Anonyme'"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-purple-600" x-text="formatPrice(vente.total)"></div>
                                <div class="text-xs text-gray-400" x-text="vente.mode_paiement"></div>
                            </div>
                        </div>
                    </template>
                    <div x-show="ventesJour.length === 0" class="text-center py-8 text-gray-400">
                        Aucune vente aujourd'hui
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total journée:</span>
                    <span class="text-purple-600" x-text="formatPrice(stats.total_jour)"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification succès --}}
    <div x-show="showSuccess" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-4 right-4 w-96 bg-green-600 text-white p-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center gap-3">
            <span class="text-2xl">✓</span>
            <div>
                <div class="font-bold">Vente enregistrée !</div>
                <div class="text-sm" x-text="'Commande ' + lastOrder.numero"></div>
                <div class="text-lg font-bold" x-text="formatPrice(lastOrder.total)"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function modeMarche() {
        return {
            vinyles: @json($vinyles),
            search: '',
            cart: [],
            showCart: false,
            modePaiement: 'cash',
            reduction: 0,
            notesVendeur: '',
            affichageClient: '',
            loading: false,
            showSuccess: false,
            showVentesJour: false,
            ventesJour: [],
            stats: { total_jour: 0, nb_ventes: 0 },
            lastOrder: {},

            get filteredVinyles() {
                const s = this.search.toLowerCase().trim();
                if (!s) return this.vinyles;
                return this.vinyles.filter(v =>
                    v.nom.toLowerCase().includes(s) ||
                    v.artiste_principale.toLowerCase().includes(s)
                );
            },

            get cartItemCount() {
                return this.cart.reduce((sum, item) => sum + item.quantite, 0);
            },

            get cartTotal() {
                return this.cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
            },

            addToCart(vinyle) {
                const existing = this.cart.find(item => item.id === vinyle.id);
                if (existing) {
                    if (existing.quantite < vinyle.quantite) {
                        existing.quantite++;
                    }
                } else {
                    this.cart.push({
                        id: vinyle.id,
                        nom: vinyle.nom,
                        artiste_principale: vinyle.artiste_principale,
                        prix: vinyle.prix,
                        quantite: 1,
                        image_url: vinyle.image_url,
                        stock: vinyle.quantite
                    });
                }
                this.showCart = true;
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            updateQuantity(index, delta) {
                const item = this.cart[index];
                const newQty = item.quantite + delta;
                if (newQty >= 1 && newQty <= item.stock) {
                    item.quantite = newQty;
                }
            },

            isInCart(vinyleId) {
                return this.cart.some(item => item.id === vinyleId);
            },

            formatPrice(amount) {
                return new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(amount || 0);
            },

            async validerVente() {
                if (this.cart.length === 0 || !this.modePaiement) return;

                this.loading = true;

                try {
                    const response = await fetch('/marche/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: this.cart.map(item => ({
                                vinyle_id: item.id,
                                quantite: item.quantite
                            })),
                            mode_paiement: this.modePaiement,
                            reduction: parseFloat(this.reduction) || 0,
                            notes_vendeur: this.notesVendeur,
                            affichage_client: this.affichageClient
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.lastOrder = data;
                        this.showSuccess = true;
                        this.showCart = false;
                        this.cart = [];
                        this.reduction = 0;
                        this.notesVendeur = '';
                        this.affichageClient = '';
                        this.loadStats();

                        setTimeout(() => this.showSuccess = false, 3000);
                    } else {
                        alert(data.message || 'Erreur lors de la vente');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion');
                } finally {
                    this.loading = false;
                }
            },

            async loadVentesJour() {
                try {
                    const response = await fetch('/marche/ventes-jour');
                    const data = await response.json();
                    this.ventesJour = data.ventes;
                    this.stats = { total_jour: data.total_jour, nb_ventes: data.nb_ventes };
                    this.showVentesJour = true;
                } catch (error) {
                    console.error('Erreur chargement ventes:', error);
                }
            },

            async loadStats() {
                try {
                    const response = await fetch('/marche/ventes-jour');
                    const data = await response.json();
                    this.stats = { total_jour: data.total_jour, nb_ventes: data.nb_ventes };
                } catch (error) {
                    console.error('Erreur stats:', error);
                }
            },

            init() {
                this.loadStats();
                setInterval(() => this.loadStats(), 30000);
            }
        }
    }
</script>
@endpush
