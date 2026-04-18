{{-- resources/views/landing-vinyl-cult.blade.php -- Landing Vinyl Cult Theme --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fundisc - Vinyles Hydrodécoupés Édition Limitée</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Theme CSS -->
    @vite(['resources/css/vinyl-cult-theme.css'])
</head>
<body class="vc-theme">
    
    <!-- Navigation -->
    <nav class="vc-nav">
        <div class="vc-nav-container">
            <a href="#" class="vc-nav-brand">
                <span>Fundisc</span>
            </a>
            
            <ul class="vc-nav-menu">
                <li><a href="#collection">Collection</a></li>
                <li><a href="#concept">Le Concept</a></li>
                <li><a href="#contact">Contact</a></li>
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
    <section style="min-height: 90vh; display: flex; align-items: center; background: linear-gradient(180deg, var(--vc-bg-secondary) 0%, var(--vc-bg-primary) 100%); border-bottom: 1px solid var(--vc-border); position: relative; overflow: hidden;">
        
        <!-- Decorative Elements -->
        <div style="position: absolute; top: 10%; right: 5%; width: 400px; height: 400px; border-radius: 50%; border: 1px solid var(--vc-border); opacity: 0.3; pointer-events: none;"></div>
        <div style="position: absolute; bottom: 20%; left: 10%; width: 200px; height: 200px; border-radius: 50%; border: 1px solid var(--vc-border); opacity: 0.2; pointer-events: none;"></div>
        
        <div class="vc-container" style="position: relative; z-index: 1;">
            <div style="max-width: 700px;">
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding: 0.5rem 1rem; background: rgba(255, 184, 0, 0.1); border: 1px solid var(--vc-label); border-radius: 2px;">
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--vc-label);">Édition Limitée</span>
                </div>
                
                <h1 style="font-size: clamp(2.5rem, 8vw, 5rem); font-weight: 800; line-height: 1; margin-bottom: 1.5rem; letter-spacing: -0.04em;">
                    L'ART DU<br>
                    <span style="color: var(--vc-label);">VINYLE</span><br>
                    DÉCOUPÉ
                </h1>
                
                <p style="font-size: 1.25rem; color: var(--vc-text-secondary); margin-bottom: 2rem; max-width: 500px;">
                    Des vinyles authentiques transformés en œuvres d'art. 
                    Chaque pièce est unique, hydrodécoupée à la main à l'effigie de vos artistes préférés.
                </p>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="#collection" class="vc-btn vc-btn-primary vc-btn-lg">
                        Découvrir la collection
                    </a>
                    <a href="#concept" class="vc-btn vc-btn-secondary vc-btn-lg">
                        Le savoir-faire
                    </a>
                </div>
                
                <div style="display: flex; gap: 3rem; margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--vc-border);">
                    <div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--vc-label);">50+</div>
                        <div style="font-size: 0.85rem; color: var(--vc-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Artistes</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--vc-label);">100%</div>
                        <div style="font-size: 0.85rem; color: var(--vc-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Fait main</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--vc-label);">🇫🇷</div>
                        <div style="font-size: 0.85rem; color: var(--vc-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Français</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section id="collection" class="vc-section" style="background: var(--vc-bg-primary);">
        <div class="vc-container">
            <div style="text-align: center; margin-bottom: 3rem;">
                <span class="vc-tag vc-tag-label" style="margin-bottom: 1rem;">Collection Exclusive</span>
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">Derniers arrivages</h2>
                <p style="color: var(--vc-text-secondary); max-width: 500px; margin: 0 auto;">Chaque vinyle est unique. Une fois vendu, le modèle n'est jamais reproduit à l'identique.</p>
            </div>
            
            @php
            $cartService = app(\App\Services\CartService::class);
            $cart = $cartService->getCart();
            $cartCount = $cart->items->sum('quantite');
            @endphp
            <!-- Script Alpine.js pour le modal quantité dans la landing -->
            <div x-data="{
                selectedVinyle: null,
                qty: 1,
                openModal(v) { this.selectedVinyle = v; this.qty = 1; },
                closeModal() { this.selectedVinyle = null; },
                addToCart() {
                    if (!this.selectedVinyle) return;
                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ vinyle_id: this.selectedVinyle.id, quantite: this.qty })
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) location.reload(); });
                }
            }" x-cloak>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                @forelse($featured as $vinyle)
                <article class="vc-card" @click="openModal({{ json_encode(['id' => $vinyle['id'], 'artiste' => $vinyle['artiste'], 'prix' => $vinyle['prix'], 'quantite' => $vinyle['quantite'], 'image' => $vinyle->getFirstMediaUrl('photos') ?: null]) }})" 
                    style="cursor: pointer;" >
                    
                    @if($vinyle['quantite'] > 0 && $vinyle['quantite'] <= 5)
                    <div class="vc-badge">🔥 Stock limité</div>
                    @endif
                    
                    <div style="aspect-ratio: 1; background: var(--vc-bg-elevated); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        @if($vinyle->getFirstMediaUrl('photos'))
                            <img src="{{ $vinyle->getFirstMediaUrl('photos') }}" alt="{{ $vinyle['artiste'] }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%) contrast(1.1);">
                        @else
                            <span style="color: var(--vc-text-muted); font-size: 3rem;">💿</span>
                        @endif
                        @if($vinyle['quantite'] <= 0)
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center;">
                            <span class="vc-tag">Rupture</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="vc-card-content">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h3 style="font-weight: 600; font-size: 1.1rem; margin: 0;">{{ $vinyle['artiste'] }}</h3>
                            <span style="color: var(--vc-label); font-weight: 700; font-size: 1.1rem;">{{ number_format($vinyle['prix'], 2, ',', ' ') }} €</span>
                        </div>
                        <p style="color: var(--vc-text-muted); font-size: 0.9rem; margin: 0;">{{ $vinyle['modele'] ?? 'Édition standard' }}</p>
                    </div>
                </article>
                @empty
                <p class="vc-text-muted" style="text-align: center;">Aucun vinyle disponible pour le moment.</p>
                @endforelse
            </div>
            
            <!-- Modal Quantité (Landing) -->
            <div x-show="selectedVinyle" x-transition style="display: none;"
                style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.8); align-items: center; justify-content: center; z-index: 100;"
                @keydown.escape.window="closeModal()" x-show.transition.opacity="selectedVinyle">
                <div class="vc-modal" @click.away="closeModal()" style="background: var(--vc-bg-card); border: 1px solid var(--vc-border); border-radius: 0.5rem; padding: 2rem; max-width: 400px; width: 90%;">
                    <div x-show="selectedVinyle">
                        <h3 class="vc-modal-title" x-text="selectedVinyle?.artiste"></h3>
                        <div class="vc-stock-status" style="margin-bottom: 1.5rem;">
                            <span class="vc-in-stock" x-show="selectedVinyle?.quantite > 0">✓ En stock</span>
                            <span class="vc-out-of-stock" x-show="selectedVinyle?.quantite <= 0">✗ Rupture de stock</span>
                        </div>
                        
                        <div x-show="selectedVinyle?.quantite > 0" style="margin-bottom: 1.5rem;">
                            <label class="vc-form-label">Quantité</label>
                            <div class="vc-quantity-selector">
                                <button type="button" class="vc-qty-btn" @click="qty = Math.max(1, qty - 1)">−</button>
                                <span class="vc-qty-value" x-text="qty">1</span>
                                <button type="button" class="vc-qty-btn" @click="qty = Math.min(selectedVinyle.quantite, qty + 1)">+</button>
                            </div>
                            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--vc-text-muted);">
                                Stock disponible: <span x-text="selectedVinyle?.quantite"></span>
                            </div>
                        </div>
                        
                        <div class="vc-modal-actions">
                            <button type="button" class="vc-btn vc-btn-secondary" @click="closeModal()">Annuler</button>
                            <button type="button" class="vc-btn vc-btn-primary" @click="addToCart()"
                                x-show="selectedVinyle?.quantite > 0">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Modal -->
            
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('kiosque.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-outline vc-btn-lg">
                    Voir tout le catalogue →
                </a>
            </div>
        </div>
    </section>
    
    <!-- Le Concept -->
    <section id="concept" class="vc-section" style="background: var(--vc-bg-secondary); border-top: 1px solid var(--vc-border); border-bottom: 1px solid var(--vc-border);">
        <div class="vc-container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
                <div>
                    <span class="vc-tag" style="margin-bottom: 1rem;">Le Savoir-Faire</span>
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1.5rem;">Hydrodécoupe de précision</h2>
                    <p style="color: var(--vc-text-secondary); margin-bottom: 1.5rem; line-height: 1.8;">
                        L'hydrodécoupe est une technique de découpe au jet d'eau à haute pression. 
                        Cette technologie nous permet de réaliser des découpes complexes avec une précision 
                        millimétrique, sans déformer le vinyle.
                    </p>
                    
                    <div style="space-y: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-left: 2px solid var(--vc-label);">
                            <div style="font-size: 1.5rem;">1️⃣</div>
                            <div>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Sélection du vinyle</h4>
                                <p style="font-size: 0.9rem; color: var(--vc-text-muted);">Nous récupérons des vinyles usagés ou endommagés</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-left: 2px solid var(--vc-border);">
                            <div style="font-size: 1.5rem;">2️⃣</div>
                            <div>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Design numérique</h4>
                                <p style="font-size: 0.9rem; color: var(--vc-text-muted);">Création du motif sur mesure pour chaque artiste</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-left: 2px solid var(--vc-border);">
                            <div style="font-size: 1.5rem;">3️⃣</div>
                            <div>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Découpe précise</h4>
                                <p style="font-size: 0.9rem; color: var(--vc-text-muted);">Hydrodécoupe avec un jet d'eau à 4000 bars</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-left: 2px solid var(--vc-label);">
                            <div style="font-size: 1.5rem;">4️⃣</div>
                            <div>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Finition artisanale</h4>
                                <p style="font-size: 0.9rem; color: var(--vc-text-muted);">Nettoyage, polissage et montage sur support</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="background: var(--vc-bg-card); border: 1px solid var(--vc-border); border-radius: 4px; padding: 2rem; text-align: center; position: relative;">
                    <div style="position: absolute; top: 1rem; right: 1rem; background: var(--vc-label); color: var(--vc-bg-primary); padding: 0.25rem 0.75rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                        Process
                    </div>
                    <div style="font-size: 6rem; margin-bottom: 1rem; filter: drop-shadow(0 0 20px var(--vc-label-glow));">💿</div>
                    <p style="color: var(--vc-text-secondary); font-style: italic;">
                        "Chaque vinyle raconte une histoire. 
                        Nous la préservons sous une nouvelle forme."
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section style="padding: 5rem 0; background: linear-gradient(135deg, var(--vc-bg-secondary) 0%, var(--vc-bg-tertiary) 100%); text-align: center; border-bottom: 1px solid var(--vc-border);">
        <div class="vc-container">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Prêt à compléter votre collection ?</h2>
            <p style="color: var(--vc-text-secondary); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                Rejoignez notre communauté de passionnés et soyez alerté des nouveaux arrivages.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('kiosque.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-primary vc-btn-lg">
                    Explorer le catalogue
                </a>
                <a href="{{ route('register') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" class="vc-btn vc-btn-secondary vc-btn-lg">
                    Créer un compte
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer id="contact" style="background: var(--vc-bg-secondary); padding: 3rem 0; border-top: 1px solid var(--vc-border);">
        <div class="vc-container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
                <div>
                    <div style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span>💿</span> Fundisc
                    </div>
                    <p style="color: var(--vc-text-secondary); font-size: 0.9rem; line-height: 1.7;">
                        Vinyles hydrodécoupés uniques.<br>
                        Made in France 🇫🇷
                    </p>
                </div>
                
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.1em; color: var(--vc-text-muted);">Navigation</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                        <li><a href="{{ route('kiosque.index') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" style="color: var(--vc-text-secondary); text-decoration: none; font-size: 0.9rem;">Catalogue</a></li>
                        <li><a href="{{ route('about') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" style="color: var(--vc-text-secondary); text-decoration: none; font-size: 0.9rem;">À propos</a></li>
                        <li><a href="{{ route('contact') . (request()->query('theme') ? '?theme='.request()->query('theme') : '') }}" style="color: var(--vc-text-secondary); text-decoration: none; font-size: 0.9rem;">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.1em; color: var(--vc-text-muted);">Suivez-nous</h4>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" style="width: 40px; height: 40px; border: 1px solid var(--vc-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--vc-text-secondary); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--vc-label)'; this.style.color='var(--vc-label)'" onmouseout="this.style.borderColor='var(--vc-border)'; this.style.color='var(--vc-text-secondary)'">IG</a>
                        <a href="#" style="width: 40px; height: 40px; border: 1px solid var(--vc-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--vc-text-secondary); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--vc-label)'; this.style.color='var(--vc-label)'" onmouseout="this.style.borderColor='var(--vc-border)'; this.style.color='var(--vc-text-secondary)'">FB</a>
                    </div>
                </div>
            </div>
            
            <div style="padding-top: 2rem; border-top: 1px solid var(--vc-border); text-align: center;">
                <p style="color: var(--vc-text-muted); font-size: 0.85rem;">
                    © 2025 Fundisc. Tous droits réservés. 
                    <span style="color: var(--vc-label);">❤</span> le vinyle
                </p>
            </div>
        </div>
    </footer>
    
</body>
</html>
