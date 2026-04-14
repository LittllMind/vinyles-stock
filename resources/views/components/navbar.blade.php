<nav class="bg-gray-800/90 backdrop-blur-sm border-b border-gray-700 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                💿 Fundisc
            </a>
            
            {{-- Desktop Menu --}}
            <div class="hidden sm:flex items-center gap-6 text-gray-300">
                <a href="/" class="hover:text-purple-400 transition">Accueil</a>
                <a href="/kiosque" class="hover:text-purple-400 transition">Catalogue</a>
                <a href="/about" class="hover:text-purple-400 transition">Le Concept</a>
                <a href="/contact" class="hover:text-purple-400 transition">Contact</a>
                
                {{-- Panier - visible pour TOUS --}}
                <a href="/cart" class="relative hover:text-purple-400 transition flex items-center gap-1 group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="hidden lg:inline">Panier</span>
                    {{-- Badge compteur --}}
                    @php
                        $cartCount = app(\App\Services\CartService::class)->count();
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                            {{ $cartCount > 9 ? '9+' : $cartCount }}
                        </span>
                    @endif
                </a>
                
                @auth
                    <a href="{{ route('orders.my') }}" class="hover:text-purple-400 transition">Mes commandes</a>
                    <a href="{{ route('conversations.index') }}" class="hover:text-purple-400 transition">Messages</a>
                    <a href="/dashboard" class="text-yellow-400 hover:text-yellow-300 font-semibold text-sm">🔧 Admin</a>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-300 transition text-sm">Déconnexion</button>
                    </form>
                @else
                    <a href="/login" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition text-white">Connexion</a>
                @endauth
            </div>
            
            {{-- Mobile menu button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden text-gray-300 p-2">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        {{-- Mobile menu --}}
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="mobileMenuOpen = false"
             class="sm:hidden mt-4 space-y-1 border-t border-gray-700 pt-4">
            
            <a href="/" class="block hover:text-purple-400 py-2 text-gray-300">Accueil</a>
            <a href="/kiosque" class="block text-purple-400 font-semibold py-2">Catalogue</a>
            <a href="/about" class="block hover:text-purple-400 py-2 text-gray-300">Le Concept</a>
            <a href="/contact" class="block hover:text-purple-400 py-2 text-gray-300">Contact</a>
            
            {{-- Panier mobile --}}
            <a href="/cart" class="flex items-center gap-2 hover:text-purple-400 py-2 text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Panier
                @if($cartCount > 0)
                    <span class="bg-pink-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            
            @auth
                <a href="{{ route('orders.my') }}" class="block hover:text-purple-400 py-2 text-gray-300">Mes commandes</a>
                <a href="/dashboard" class="block text-yellow-400 py-2">🔧 Dashboard</a>
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 py-2">Déconnexion</button>
                </form>
            @else
                <a href="/login" class="block bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-center text-white mt-2">Connexion</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Flash Messages Globaux --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 max-w-md mx-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
        <button @click="show = false" class="ml-2 hover:text-green-200">✕</button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 max-w-md mx-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        {{ session('error') }}
        <button @click="show = false" class="ml-2 hover:text-red-200">✕</button>
    </div>
@endif
