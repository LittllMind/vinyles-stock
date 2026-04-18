<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fundisc')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <script src="{{ asset('build/assets/app.js') }}"></script>
    @stack('head')
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    <!-- Navigation -->
    @include('components.navbar')

    <!-- Page Content -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        @if (session('success'))
            <div class="alert alert-success bg-green-600 text-white px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error bg-red-600 text-white px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-gray-700 py-8 mt-auto">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; 2026 Fundisc - Artisanat &amp; Passion</p>
        </div>
    </footer>

</body>
</html>