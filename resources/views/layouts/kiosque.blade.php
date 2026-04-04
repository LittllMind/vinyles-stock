{{-- resources/views/layouts/kiosque.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fundisc')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    <!-- Toast Notifications Container -->
    @if(session('toast'))
        <div id="session-toast-data" data-toast="{{ json_encode(session('toast')) }}" class="hidden"></div>
    @endif
    
    <div x-data="toastData" @toast-added.window="addToast($event.detail)" class="fixed top-20 right-4 z-50 flex flex-col gap-2 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="toast.show"
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
                :class="{
                    'bg-green-600 border-l-4 border-green-400': toast.type === 'success',
                    'bg-red-600 border-l-4 border-red-400': toast.type === 'error',
                    'bg-blue-600 border-l-4 border-blue-400': toast.type === 'info',
                    'bg-yellow-600 border-l-4 border-yellow-400': toast.type === 'warning'
                }"
                role="alert"
            >
                <div class="p-4 flex items-start gap-3">
                    <span class="text-xl flex-shrink-0" x-text="toast.icon"></span>
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-medium text-white" x-text="toast.message"></p>
                    </div>
                    <button 
                        @click="removeToast(toast.id)"
                        class="flex-shrink-0 ml-4 text-white/70 hover:text-white transition-colors"
                        aria-label="Fermer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="h-1 bg-white/20">
                    <div 
                        class="h-full bg-white/50 origin-left"
                        :style="{ animation: 'shrink 3000ms linear forwards' }"
                    ></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Navigation -->
    <nav class="bg-gray-800/90 backdrop-blur-sm border-b border-gray-700 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    💿 Fundisc
                </a>
                <div class="hidden sm:flex items-center gap-6">
                    <a href="/kiosque" class="hover:text-purple-400 transition">Catalogue</a>
                    <a href="/about" class="hover:text-purple-400 transition">Le Concept</a>
                    <a href="/contact" class="hover:text-purple-400 transition">Contact</a>
                    @auth
                        <a href="/cart" class="hover:text-purple-400 transition">Panier</a>
                        <a href="{{ route('orders.my') }}" class="hover:text-purple-400 transition">Mes commandes</a>
                        <a href="/dashboard" class="text-yellow-400 hover:text-yellow-300 font-semibold">🔧 Dashboard</a>
                        <a href="/addresses" class="hover:text-purple-400 transition" title="Mes adresses">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-400 hover:text-red-300 transition">Déconnexion</button>
                        </form>
                    @else
                        <a href="/login" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-4 py-2 rounded-lg transition">Connexion</a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-cloak class="sm:hidden mt-4 space-y-2">
                <a href="/kiosque" class="block text-purple-400 font-semibold py-2">Catalogue</a>
                <a href="/about" class="block hover:text-purple-400 py-2">Le Concept</a>
                <a href="/contact" class="block hover:text-purple-400 py-2">Contact</a>
                @auth
                    <a href="/cart" class="block hover:text-purple-400 py-2">Panier</a>
                    <a href="{{ route('orders.my') }}" class="block hover:text-purple-400 py-2">Mes commandes</a>
                    <a href="/dashboard" class="block text-yellow-400 py-2">🔧 Dashboard</a>
                    <a href="/addresses" class="block hover:text-purple-400 py-2">Mes adresses</a>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="text-red-400 py-2">Déconnexion</button>
                    </form>
                @else
                    <a href="/login" class="block bg-gradient-to-r from-purple-600 to-pink-600 px-4 py-2 rounded-lg text-center">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container mx-auto px-4 py-6 sm:py-8 flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-gray-700 py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Newsletter --}}
                <div class="newsletter-section">
                    <h3 class="text-lg font-semibold text-white mb-2">📧 Newsletter</h3>
                    <p class="text-gray-400 text-sm mb-4">Abonnez-vous pour recevoir nos nouveautés et offres exclusives !</p>
                    
                    <form action="{{ route('api.newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-2" x-data="{ submitted: false, loading: false }" @submit.prevent="
                        loading = true;
                        fetch('{{ route('api.newsletter.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                            },
                            body: JSON.stringify({ email: $refs.email.value })
                        })
                        .then(r => r.json())
                        .then(data => {
                            loading = false;
                            submitted = true;
                            $dispatch('toast-added', { type: 'success', message: data.message, icon: '✅' });
                        })
                        .catch(err => {
                            loading = false;
                            $dispatch('toast-added', { type: 'error', message: 'Erreur lors de l\'inscription.', icon: '❌' });
                        })
                    ">
                        @csrf
                        <input 
                            x-ref="email"
                            type="email" 
                            name="email" 
                            placeholder="Votre email" 
                            required
                            class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                        >
                        <button 
                            type="submit" 
                            :disabled="loading || submitted"
                            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg text-white font-medium transition"
                        >
                            <span x-show="!loading && !submitted">S'inscrire</span>
                            <span x-show="loading">Envoi...⏳</span>
                            <span x-show="submitted">✅ Envoyé !</span>
                        </button>
                    </form>
                    
                    <p class="text-gray-500 text-xs mt-2">
                        En vous inscrivant, vous acceptez notre politique de confidentialité. <br>
                        Vous pouvez vous désinscrire à tout moment depuis les emails reçus.
                    </p>
                </div>

                {{-- Copyright --}}
                <div class="text-center md:text-right flex flex-col justify-center">
                    <p class="text-gray-400">© 2026 Fundisc - Artisanat & Passion</p>
                    <p class="text-gray-500 text-sm mt-2">
                        <a href="/contact" class="hover:text-purple-400 transition">Contact</a> | 
                        <a href="/about" class="hover:text-purple-400 transition">À propos</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
