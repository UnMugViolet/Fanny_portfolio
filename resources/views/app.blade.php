<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Fanny Séraudie - Illustratrice et animatrice 2D | Portfolio' }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta -->
    <meta name="description" content="Découvrez le portfolio de Fanny Séraudie, illustratrice et animatrice 2D. Explorez ses projets créatifs et son univers artistique unique.">
    <meta property="og:title" content="Fanny Séraudie - Illustratrice et animatrice 2D | Portfolio">
    <meta property="og:description" content="Découvrez le portfolio de Fanny Séraudie, illustratrice et animatrice 2D. Explorez ses projets créatifs et son univers artistique unique.">
    <meta property="og:image" content="{{ asset('img/logo-fanny-seraudie.webp') }}">
    <meta name="robots" content="index, follow">
    
    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Pass data to Vue -->
    <script>
        window.appData = {
            categories: @json($categories ?? [])
        };
    </script>
</head>
<body class="bg-brand-gray font-sans">
    <div id="app"></div>
</body>
</html>
