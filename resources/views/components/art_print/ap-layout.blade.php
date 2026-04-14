{{-- resources/views/components/art_print/ap-layout.blade.php --}}
{{-- Layout de base ART PRINT (galerie MoMA) --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Fundisc • Vinyles FUN DISC')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap" rel="stylesheet">

    <!-- Theme ART PRINT -->
    <link rel="stylesheet" href="{{ asset('css/art-print-theme.css') }}">

</head>
<body class="art-print-theme">
    
    <!-- Navigation Galerie -->
    @include('components.art_print.ap-nav')

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Galerie -->
    @include('components.art_print.ap-footer')

    <!-- Modale Sélecteur de Fond -->
    @include('components.art_print.fond-selector-modal')

</body>
</html>