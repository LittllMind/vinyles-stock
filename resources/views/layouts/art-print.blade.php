<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fundisc') • Vinyles ART PRINT</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="{{ asset('css/ap-global.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    @php
        $role = Auth::check() ? Auth::user()->role : null;
        $cartCount = session('cart_count', 0);
    @endphp

    {{-- Navigation --}}
    @include('components.art_print.ap-nav')

    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Modale sélecteur de fond --}}
    @if(!request()->routeIs('admin.*') && !request()->routeIs('marche.*'))
        @include('components.art_print.fond-selector-modal')
    @endif

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-12 mt-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-gray-500 text-sm">© 2026 FUNDISC • Vinyles en ART PRINT</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
