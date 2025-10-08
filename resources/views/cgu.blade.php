<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Conditions Générales d'utilisation | Fanny Séraudie Portfolio</title>
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- SEO -->
    <meta name="description" content="Conditions Générales d'utilisation du site web Fanny Séraudie Portfolio.">
    <meta property="og:title" content="Conditions Générales d'utilisation | Fanny Séraudie Portfolio">
    <meta property="og:description" content="Conditions Générales d'utilisation du site web Fanny Séraudie Portfolio.">
    <meta property="og:image" content="{{ asset('img/logo-fanny-seraudie.webp') }}">
	<meta name="robots" content="noindex, nofollow">

	<!-- Styles and Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	
	<!-- Pass data to Vue -->
	<script>
		window.appData = {
			categories: @json($categories ?? []),
		};
	</script>
</head>
<body class="bg-brand-gray font-sans">
	<div id="app">
	</div>
</body>
</html>
