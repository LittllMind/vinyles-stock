{{-- resources/views/components/art_print/ap-nav.blade.php --}}
{{-- Navigation adaptative : Public | Client | Employé | Admin --}}

@php
// Navigation adaptative : Public | Client | Employé | Admin

$role = Auth::check() ? Auth::user()->role : null;

// Compter items panier via CartService (même pour visiteurs)
$cartService = app(\App\Services\CartService::class);
$cartCount = $cartService->count();
@endphp

<nav class="ap-nav">
    <div class="ap-nav-container">
        {{-- Brand toujours visible --}}
        <a href="{{ url('/') }}" class="ap-brand">FUN<br>DISC</a>
        
        <ul class="ap-nav-links">
            {{-- PUBLIC : Collection & À propos --}}
            <li>
                <a href="{{ route('kiosque.index') }}" class="{{ request()->routeIs('kiosque.*') ? 'active' : '' }}">
                    Collection
                </a>
            </li>
            <li>
                <a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">
                    À propos
                </a>
            </li>
            
            {{-- CONTACT (Public) --}}
            <li>
                <a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">
                    Contact
                </a>
            </li>
            
            {{-- ADMIN : Tableau de bord --}}
            @if($role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
            @endif
            
            {{-- EMPLOYÉ/ADMIN : Menu Gestion --}}
            @if(in_array($role, ['employe', 'admin']))
                <li class="dropdown-container">
                    <a href="#" class="nav-dropdown-toggle" onclick="toggleDropdown(event, 'gestion-menu')">
                        Gestion ▼
                    </a>
                    <ul id="gestion-menu" class="dropdown-menu">
                        <li><a href="{{ route('vinyles.index') }}">📀 Vinyles</a></li>
                        <li><a href="{{ route('admin.orders.index') }}">📦 Commandes</a></li>
                        <li><a href="{{ route('mouvements.index') }}">📊 Mouvements</a></li>
                        @if($role === 'admin')
                            <li class="dropdown-separator"></li>
                            <li><a href="{{ route('fonds.index') }}">🖼️ Fonds</a></li>
                            <li><a href="{{ route('ventes.index') }}">💰 Ventes</a></li>
                            <li><a href="{{ route('admin.stats.charts') }}">📈 Statistiques</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            
            {{-- ADMIN : Menu Administration --}}
            @if($role === 'admin')
                <li class="dropdown-container">
                    <a href="#" class="nav-dropdown-toggle" onclick="toggleDropdown(event, 'admin-menu')">
                        Admin ▼
                    </a>
                    <ul id="admin-menu" class="dropdown-menu">
                        <li><a href="{{ route('admin.users.index') }}">👥 Utilisateurs</a></li>
                        <li>
                            <a href="{{ route('admin.reviews.pending') }}">
                                ⭐ Avis en attente
                                @php
                                    $pendingReviewsCount = \App\Models\Review::where('status', 'pending')->count();
                                @endphp
                                @if($pendingReviewsCount > 0)
                                    <span class="nav-badge">{{ $pendingReviewsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="dropdown-separator"></li>
                        <li><a href="{{ route('admin.conversations.index') }}">💬 Conversations</a></li>
                        <li><a href="{{ route('admin.contact-messages.index') }}">📨 Messages contact</a></li>
                        <li class="dropdown-separator"></li>
                        <li><a href="{{ route('admin.reports.monthly') }}">📋 Rapports</a></li>
                    </ul>
                </li>
            @endif
            
            {{-- MODE MARCHÉ (Admin uniquement) --}}
            @if($role === 'admin')
                <li>
                    <a href="{{ route('marche.index') }}" class="highlight-mode">
                        🛒 Mode Marché
                    </a>
                </li>
            @endif
            
            {{-- CLIENT : Menu déroulant Espace Client --}}
            @if($role === 'client')
                <li class="dropdown-container">
                    <a href="#" class="nav-dropdown-toggle" onclick="toggleDropdown(event, 'client-menu')">
                        Mon compte ▼
                    </a>
                    <ul id="client-menu" class="dropdown-menu">
                        <li><a href="{{ route('profile') }}">👤 Mon profil</a></li>
                        <li><a href="{{ route('orders.my') }}">📦 Mes commandes</a></li>
                        <li><a href="{{ route('addresses.index') }}">📍 Mes adresses</a></li>
                        <li class="dropdown-separator"></li>
                        <li><a href="{{ route('conversations.index') }}">💬 Mes conversations</a></li>
                    </ul>
                </li>
            @endif
            
            {{-- PANIER - Visible pour tous (visiteurs + client + employé + admin) --}}
            <li class="cart-nav-item">
                <a href="{{ route('cart.index') }}" class="cart-link" title="Mon panier">
                    <svg class="cart-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    @endif
                </a>
            </li>
            
            {{-- COMPTE / CONNEXION --}}
            @if(Auth::check())
                <li class="dropdown-container">
                    <a href="#" class="nav-dropdown-toggle" onclick="toggleDropdown(event, 'compte-menu')">
                        {{ Auth::user()->name ?? 'Compte' }} ▼
                    </a>
                    <ul id="compte-menu" class="dropdown-menu right-aligned">
                        @if($role !== 'client')
                            <li>
                                <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                                    <span class="dropdown-icon">👤</span> Mon profil
                                </a>
                            </li>
                        @endif
                        <li class="dropdown-separator"></li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="dropdown-icon">🚪</span> Déconnexion
                            </a>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="btn-auth">
                        Connexion
                    </a>
                </li>
            @endif
        </ul>
    </div>
    
    {{-- Form logout caché --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav>

{{-- Styles inline pour la nav --}}
<style>
.ap-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid #e5e5e5;
}

.ap-nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 60px;
}

.ap-brand {
    font-size: 1.2rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    color: #1a1a1a;
    text-decoration: none;
}

.ap-nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.ap-nav-links > li {
    position: relative;
}

.ap-nav-links a {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #666;
    text-decoration: none;
    transition: color 0.2s;
    padding: 0.5rem 0;
}

.ap-nav-links a:hover,
.ap-nav-links a.active {
    color: #1a1a1a;
}

/* Bouton connexion */
.btn-auth {
    background: #1a1a1a;
    color: white !important;
    padding: 0.5rem 1rem !important;
    transition: opacity 0.2s;
}

.btn-auth:hover {
    opacity: 0.8;
}

/* Panier */
.cart-nav-item {
    position: relative;
}

.cart-link {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0.5rem !important;
}

.cart-icon {
    width: 22px;
    height: 22px;
    transition: transform 0.2s ease;
}

.cart-link:hover .cart-icon {
    transform: scale(1.1);
}

.cart-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: linear-gradient(135deg, #FFB800 0%, #FF6B00 100%);
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 6px;
    border-radius: 10px;
    min-width: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(255, 184, 0, 0.4);
    animation: cart-pulse 2s ease-in-out infinite;
}

@keyframes cart-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.cart-badge:empty {
    display: none;
}

/* Badge pour navigation */
.nav-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ef4444;
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 6px;
    min-width: 18px;
}

/* Mode Marché highlight */
.highlight-mode {
    background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%);
    color: white !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 4px;
}

/* Dropdowns */
.dropdown-container {
    position: relative;
}

.nav-dropdown-toggle {
    cursor: pointer;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 0.5rem;
    background: white;
    border: 1px solid #e5e5e5;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    min-width: 200px;
    padding: 0.5rem 0;
    list-style: none;
}

.dropdown-menu.right-aligned {
    left: auto;
    right: 0;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu li {
    margin: 0;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
    text-transform: none;
    color: #666;
    text-decoration: none;
    transition: background 0.2s;
}

.dropdown-menu a:hover {
    background: #f5f5f5;
    color: #1a1a1a;
}

.dropdown-icon {
    font-size: 1rem;
}

.dropdown-separator {
    height: 1px;
    background: #e5e5e5;
    margin: 0.5rem 0;
}

/* Mobile responsive */
@media (max-width: 1024px) {
    .ap-nav-container {
        padding: 0 1rem;
    }
    
    .ap-nav-links {
        gap: 1rem;
    }
    
    .ap-nav-links a {
        font-size: 0.75rem;
    }
}
</style>

{{-- JavaScript pour les dropdowns --}}
<script>
function toggleDropdown(event, menuId) {
    event.preventDefault();
    event.stopPropagation();
    
    const menu = document.getElementById(menuId);
    const allMenus = document.querySelectorAll('.dropdown-menu');
    
    // Fermer tous les autres menus
    allMenus.forEach(m => {
        if (m.id !== menuId) {
            m.classList.remove('show');
        }
    });
    
    // Toggle le menu cliqué
    menu.classList.toggle('show');
}

// Fermer les menus quand on clique ailleurs
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
</script>
