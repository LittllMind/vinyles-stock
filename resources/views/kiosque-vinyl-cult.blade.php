{{-- resources/views/kiosque-vinyl-cult.blade.php -- Thème Vinyl Cult (Dark Underground) --}}

@php
    use Illuminate\Pagination\Paginator;
    $cartService = app(\App\Services\CartService::class);
    $cart = $cartService->getCart();
    $cartCount = $cart->items->sum('quantite');
    
    $vinylesData = $vinyles->map(function($v) {
        return [
            'id' => $v->id,
            'artiste' => $v->artiste,
            'modele' => $v->modele,
            'prix' => $v->prix,
            'quantite' => $v->quantite,
            'image' => $v->getFirstMediaUrl('photos') ?: null,
        ];
    })->toArray();
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fundisc - Catalogue Vinyle Cult</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Theme CSS -->
    @vite(['resources/css/vinyl-cult-theme.css', 'resources/js/app.js'])
</head>
<body class="vc-theme" x-data="kiosqueComponent(@js($vinylesData))">
    
    <!-- Navigation -->
    <nav class="vc-nav">
        <div class="vc-nav-container">
            <a href="{{ route('landing') }}" class="vc-nav-brand">
                <span>Fundisc</span>
            </a>
            
            <ul class="vc-nav-menu">
                <li><a href="{{ route('landing') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}">Accueil</a></li>
                <li><a href="{{ route('kiosque.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="active">Catalogue</a></li>
                <li><a href="{{ route('about') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}">Le Concept</a></li>
                <li><a href="{{ route('contact') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}">Contact</a></li>
                @auth
                    <li><a href="{{ route('cart.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}">Panier</a></li>
                    <li><a href="{{ route('orders.my') }}">Mes commandes</a></li>
                @endauth
            </ul>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                @guest
                    <a href="{{ route('login') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-ghost">Connexion</a>
                    <a href="{{ route('register') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-primary">S'inscrire</a>
                @endguest
                @auth
                    <a href="{{ route('cart.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-ghost" style="position: relative;">
                        🛒 Panier
                        @if($cartCount > 0)
                            <span style="position: absolute; top: -8px; right: -8px; background: var(--vc-label); color: var(--vc-bg-primary); width: 20px; height: 20px; border-radius: 50%; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('orders.my') }}" style="color: var(--vc-text); font-size: 0.875rem;">{{ auth()->user()->name ?? 'Compte' }}</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="vc-btn vc-btn-ghost" style="border-color: var(--vc-border);">Déconnexion</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="vc-hero">
        <div class="vc-container">
            <h1 class="vc-hero-title"><span>VINYL</span> CULT</h1>
            <p class="vc-hero-subtitle">Collection exclusive d'artistes hydrodécoupés. Éditions limitées, qualité premium.</p>
        </div>
    </section>
    
    <!-- Main Content -->
    <main class="vc-container vc-section">
        <!-- Filters -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 280px; max-width: 400px;">
                <input type="text" x-model="search" placeholder="🔍 Rechercher un artiste..."
                    class="vc-form-input" style="margin-bottom: 0;">
            </div>
            
            <button type="button" @click="showAll = !showAll" 
                class="vc-btn" :class="showAll ? 'vc-btn-secondary' : 'vc-btn-outline'">
                <span x-text="showAll ? '🚫 Masquer ruptures' : '👁 Tout afficher'"></span>
            </button>
        </div>
        
        <!-- Products Grid -->
        <div class="vc-catalog-grid">
            <template x-for="vinyle in filteredVinyles" :key="vinyle.id">
                <article class="vc-card" @click="openQuantityModal(vinyle)"
                    :class="(vinyle.quantite ?? 0) <= 0 ? 'out-of-stock' : ''">
                    
                    <!-- Badge édition limitée -->
                    <div class="vc-badge" x-show="(vinyle.quantite ?? 0) > 0 && (vinyle.quantite ?? 0) <= 5" x-cloak>
                        🔥 Stock limité
                    </div>
                    
                    <!-- Image -->
                    <div class="vc-card-image" style="background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);">
                        <img :src="vinyle.image || '/images/no-image.png'" :alt="vinyle.artiste + ' - ' + vinyle.modele">
                        
                        <!-- Overlay rupture -->
                        <div x-show="(vinyle.quantite ?? 0) <= 0" x-cloak
                            style="position: absolute; inset: 0; background: rgba(0,0,0,0.85); display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.5rem;">
                            <span style="font-size: 2rem;">🏷️</span>
                            <span style="color: var(--vc-text-secondary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Rupture</span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="vc-card-content">
                        <h3 class="vc-card-title" x-text="vinyle.artiste"></h3>
                        <p class="vc-card-artist" x-text="vinyle.modele"></p>
                        
                        <div class="vc-card-footer">
                            <span class="vc-card-price">
                                <span class="currency">€</span><span x-text="vinyle.prix"></span>
                            </span>
                            <span class="vc-tag" x-show="(vinyle.quantite ?? 0) > 0">
                                Stock: <span x-text="vinyle.quantite"></span>
                            </span>
                        </div>
                        
                        <button type="button" 
                            :disabled="(vinyle.quantite ?? 0) <= 0"
                            class="vc-btn vc-btn-primary" 
                            style="width: 100%; margin-top: 1rem;"
                            @click.stop="openQuantityModal(vinyle)">
                            <span x-show="(vinyle.quantite ?? 0) > 0">🛒 Ajouter au panier</span>
                            <span x-show="(vinyle.quantite ?? 0) <= 0">Indisponible</span>
                        </button>
                    </div>
                </article>
            </template>
        </div>
        
        <!-- Empty State -->
        <div x-show="filteredVinyles.length === 0" x-cloak 
            style="text-align: center; padding: 4rem 2rem; border: 1px dashed var(--vc-border); border-radius: 4px;">
            <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">🔍</div>
            <h3 style="font-size: 1.25rem; color: var(--vc-text-secondary); margin-bottom: 0.5rem;">Aucun résultat</h3>
            <p style="color: var(--vc-text-muted);">Essayez un autre terme de recherche</p>
        </div>
    </main>
    
    <!-- Quantity Modal -->
    <div x-show="selectedVinyle !== null" x-cloak
        style="position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 200; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);"
        @click.self="closeQuantityModal()"
        @keydown.escape.window="closeQuantityModal()">
        
        <div style="background: var(--vc-bg-card); border: 1px solid var(--vc-border); border-radius: 4px; max-width: 450px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative;"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Close button -->
            <button @click="closeQuantityModal()" 
                style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: var(--vc-text-muted); font-size: 1.5rem; cursor: pointer;">
                ✕
            </button>
            
            <div style="padding: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center;"
                    x-text="selectedVinyle?.artiste + ' - ' + selectedVinyle?.modele"></h2>
                
                <!-- Image -->
                <div style="border-radius: 4px; overflow: hidden; margin-bottom: 1.5rem; background: var(--vc-bg-tertiary);">
                    <img :src="selectedVinyle?.image || '/images/no-image.png'" :alt="selectedVinyle?.artiste"
                        style="width: 100%; height: 220px; object-fit: contain; padding: 1rem;">
                </div>
                
                <!-- Quantity Selector -->
                <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <button @click="decrementQuantity()" class="vc-btn vc-btn-secondary" style="width: 44px; height: 44px; padding: 0; font-size: 1.5rem;">−</button>
                    <div style="font-size: 1.5rem; font-weight: 700; min-width: 3rem; text-align: center;" x-text="selectedQuantity"></div>
                    <button @click="incrementQuantity()" class="vc-btn vc-btn-secondary" style="width: 44px; height: 44px; padding: 0; font-size: 1.5rem;">+</button>
                </div>
                
                <!-- Background Selection -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="vc-form-label">Fond</label>
                    <select x-model="selectedFond" class="vc-form-select">
                        <option value="standard">Standard (inclus)</option>
                        <option value="miroir">Miroir (+8 €)</option>
                        <option value="dore">Doré (+13 €)</option>
                    </select>
                </div>
                
                <!-- Price -->
                <div style="background: var(--vc-bg-tertiary); padding: 1.5rem; border-radius: 4px; text-align: center; margin-bottom: 1.5rem; border: 1px solid var(--vc-border);">
                    <span style="font-size: 0.85rem; color: var(--vc-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Prix unitaire</span>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--vc-label); margin-top: 0.5rem;"
                        x-text="formatPrice(currentUnitPrice())"></div>
                </div>
                
                <!-- Actions -->
                <div style="display: flex; gap: 1rem;">
                    <button @click="closeQuantityModal()" class="vc-btn vc-btn-secondary" style="flex: 1;">
                        Annuler
                    </button>
                    <button @click="submitCart()" class="vc-btn vc-btn-primary" style="flex: 1;">
                        Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alpine Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kiosqueComponent', (vinyles) => ({
                search: '',
                showAll: false,
                selectedVinyle: null,
                selectedQuantity: 1,
                selectedFond: 'standard',
                vinyles: vinyles,
                
                get filteredVinyles() {
                    let filtered = this.vinyles;
                    
                    // Recherche
                    if (this.search.trim()) {
                        const term = this.search.toLowerCase();
                        filtered = filtered.filter(v => 
                            v.artiste.toLowerCase().includes(term) ||
                            v.modele.toLowerCase().includes(term)
                        );
                    }
                    
                    // Filtre rupture
                    if (!this.showAll) {
                        filtered = filtered.filter(v => (v.quantite ?? 0) > 0);
                    }
                    
                    return filtered;
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(price);
                },
                
                currentUnitPrice() {
                    let price = this.selectedVinyle?.prix || 0;
                    if (this.selectedFond === 'miroir') price += 8;
                    if (this.selectedFond === 'dore') price += 13;
                    return price;
                },
                
                openQuantityModal(vinyle) {
                    if ((vinyle.quantite ?? 0) <= 0) return;
                    this.selectedVinyle = vinyle;
                    this.selectedQuantity = 1;
                    this.selectedFond = 'standard';
                    document.body.style.overflow = 'hidden';
                },
                
                closeQuantityModal() {
                    this.selectedVinyle = null;
                    document.body.style.overflow = '';
                },
                
                incrementQuantity() {
                    const max = this.selectedVinyle?.quantite ?? 0;
                    if (this.selectedQuantity < max) {
                        this.selectedQuantity++;
                    }
                },
                
                decrementQuantity() {
                    if (this.selectedQuantity > 1) {
                        this.selectedQuantity--;
                    }
                },
                
                submitCart() {
                    if (!this.selectedVinyle) return;
                    
                    fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            vinyle_id: this.selectedVinyle.id,
                            quantite: this.selectedQuantity,
                            fond: this.selectedFond
                        })
                    })
                    .then(r => r.json())
                    .then(() => {
                        this.closeQuantityModal();
                        window.location.href = '/cart';
                    })
                    .catch(err => {
                        alert('Erreur lors de l\'ajout au panier');
                        console.error(err);
                    });
                }
            }));
        });
    </script>
</body>
</html>
