{{-- resources/views/components/art-print/ap-nav.blade.php --}}
{{-- Navigation minimaliste galerie --}}

<nav class="ap-nav">
    <div class="ap-nav-container">
        <a href="{{ url('/') }}" class="ap-brand">ART PRINT</a>
        
        <ul class="ap-nav-links">
            <li><a href="{{ route('kiosque.index') }}"
                   class="{{ request()->routeIs('kiosque.index') ? 'active' : '' }}">Collection</a></li>
            <li><a href="{{ url('/about') }}">À propos</a></li>
            
            @if(Auth::check())
                <li><a href="{{ route('orders.my') }}"
                       style="font-size: 0.75rem;">Mes commandes</a></li>
                <li><a href="{{ route('cart.index') }}"
                       style="font-size: 0.75rem; position: relative;">
                        Panier @if($cartItemCount ?? 0 > 0)
                            <span style="position: absolute; top: -8px; right: -8px; background: #FFB800; color: #1A1A1A; font-size: 0.6rem; padding: 2px 5px; border-radius: 10px;">{{ $cartItemCount ?? 0 }}</span>
                        @endif
                    </a></li>
                
                <!-- Dropdown compte -->
                <li class="relative group">
                    <a href="#" style="font-size: 0.75rem;">{{ Auth::user()->name ?? 'Compte' }} ▼</a>
                    <ul class="absolute hidden group-hover:block top-full right-0 bg-white border border-e5e5e5 shadow-lg min-w-[150px] p-2 z-50">
                        <li><a href="{{ url('/profil') }}" style="display: block; padding: 0.5rem 1rem; font-size: 0.8rem; white-space: nowrap;">Mon profil</a></li>
                        <li><a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               style="display: block; padding: 0.5rem 1rem; font-size: 0.8rem; white-space: nowrap;">Déconnexion</a></li>
                    </ul>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" style="font-size: 0.75rem;">Connexion</a></li>
            @endif
        </ul>
    </div>
</nav>

<style>
.ap-nav-links li.relative:hover > ul,
.ap-nav-links li.relative.group:hover > ul,
.ap-nav-links li > ul {
    display: none;
}
.ap-nav-links li.relative:hover > ul,
.ap-nav-links li.group:hover > ul {
    display: block !important;
}
</style>