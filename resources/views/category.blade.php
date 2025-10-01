<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $category->name ?? 'Unknown' }} | Fanny Séraudie Portfolio</title>
	
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- SEO -->
	<meta name="title" content="{{ $category->meta_title ?? $category->name ?? 'Fanny Séraudie' }}">
    <meta name="description" content="{{ $category->meta_description ?? 'Retrouvez la liste de nos articles sur des sujets variés afin de prendre soin de votre animal de compagnie' }}">
    <meta property="og:title" content="{{ $category->meta_title ?? $category->name ?? 'Fanny Séraudie' }}">
    <meta property="og:description" content="{{ $category->meta_description ?? 'Retrouvez la liste de nos articles sur des sujets variés afin de prendre soin de votre animal de compagnie' }}">
    <meta property="og:image" content="{{ $category->thumbnail ?? asset('img/logo-fanny-seraudie.webp') }}">
	<meta name="robots" content="{{ ($category->no_index ?? 0) ? 'noindex, nofollow' : 'index, follow' }}">

	<!-- Styles and Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	
	<!-- Pass data to Vue -->
	<script>
		window.appData = {
			categories: @json($categories ?? []),
			category: @json($category ?? null),
			projects: @json($projects ?? [])
		};
	</script>
</head>
<body class="bg-brand-gray font-sans">
	<div id="app">
	</div>
</body>
</html>
