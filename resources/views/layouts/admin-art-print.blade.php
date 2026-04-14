{{-- resources/views/layouts/admin-art-print.blade.php --}}
{{-- Layout Admin ART PRINT avec sidebar intégrée --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin') • FUN DISC</title>
    
    {{-- Tailwind CDN pour le style --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Styles communs --}}
    <link href="{{ asset('css/ap-global.css') }}" rel="stylesheet">
    
    <style>
        /* Layout avec sidebar */
        .admin-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }
        
        @media (max-width: 1024px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }
        }
        
        /* Zone de contenu principale */
        .admin-main {
            margin-left: 240px;
            padding: 2rem;
            min-height: 100vh;
            background: white;
        }
        
        @media (max-width: 1024px) {
            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
        }
        
        /* Header de page */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #E5E5E5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 300;
            color: #1A1A1A;
            letter-spacing: -0.02em;
        }
        
        .page-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        /* Cards admin */
        .admin-card {
            background: white;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .admin-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        
        .admin-card-title {
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
        }
        
        /* Tables */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            text-align: left;
            padding: 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #999;
            font-weight: 500;
            border-bottom: 1px solid #E5E5E5;
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid #F0F0F0;
            font-size: 0.9rem;
        }
        
        .admin-table tr:hover td {
            background: #FAFAFA;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            padding: 0.25rem 0.625rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-ok { background: #D4EDDA; color: #155724; }
        .badge-warning { background: #FFF3CD; color: #856404; }
        .badge-danger { background: #F8D7DA; color: #721C24; }
        .badge-info { background: #E7F3FF; color: #004085; }
        
        /* Boutons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #1A1A1A;
            color: white;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .btn-secondary {
            background: #F5F5F5;
            color: #666;
            border: 1px solid #E5E5E5;
        }
        
        .btn-secondary:hover {
            background: #E5E5E5;
        }
        
        .btn-icon {
            padding: 0.625rem;
            background: #F5F5F5;
            border-radius: 6px;
        }
        
        /* Mobile toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 200;
            padding: 0.75rem;
            background: #1A1A1A;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        @media (max-width: 1024px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    {{-- Toggle mobile --}}
    <button class="mobile-toggle" onclick="document.querySelector('.ap-sidebar').classList.toggle('open')">
        ☰
    </button>

    {{-- Sidebar --}}
    @include('components.art_print.ap-sidebar')
    
    {{-- Contenu principal --}}
    <main class="admin-main">
        <header class="page-header">
            <h1 class="page-title">@yield('title', 'Administration')</h1>
            <div class="page-actions">
                @yield('page-actions')
            </div>
        </header>
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
    
    {{-- Scripts --}}
    <script>
        // Fermer sidebar mobile au click sur le contenu
        document.querySelector('.admin-main').addEventListener('click', function() {
            document.querySelector('.ap-sidebar').classList.remove('open');
        });
    </script>
    
    @stack('scripts')
</body>
</html>