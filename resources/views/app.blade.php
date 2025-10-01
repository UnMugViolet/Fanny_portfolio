<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Fanny Portfolio') }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
