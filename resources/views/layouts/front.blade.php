<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<meta name="description" content="Um chamado pela memória coletiva acerca das passeatas de 2013.">
	<meta name="keywords" content="junho 2013,manifestações,protestos,fotografia,cartazes,política,jornadas de junho,brasil">
	<meta name="author" content="Grafias de Junho">

	<!-- <link rel="shortcut icon" type="image/x-icon" href="./img/favicon.ico"> -->
	<link rel="home" href="https://www.grafiasdejunho.org/">

	<meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">
	<meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
	<meta property="og:description" content="Um chamado pela memória coletiva acerca das passeatas de 2013.">
	<meta property="og:image" content="https://www.grafiasdejunho.org/images/logo.png">
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://www.grafiasdejunho.org/">
	<link rel="canonical" href="https://www.grafiasdejunho.org/">



	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#00aba9">
	<meta name="theme-color" content="#ffffff">




    <!-- Scripts -->
    <script src="{{ asset('js/front.js') }}" defer></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120302693-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-120302693-1');
	</script>

    <!-- Fonts -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">

</head>
<body>
	@if(!Route::currentRouteNamed('home'))
		<div class="columns is-gapless is-marginless">
			<div class="column is-one-quarter">
				<figure class="image logo">
					<a href="/principal">
						<img src="/images/logo.png" width="100%"></img>
					</a>
				</figure>
			</div>
		</div>
		<div class="columns is-gapless">
		<div class="column is-one-quarter is-size-7">
			<div class="content has-text-centered is-marginless">
				<a href="/principal" class="has-text-grey-dark">PRINCIPAL</a>
			</div>
			<hr class="menu-separator">
			<div class="content has-text-centered is-marginless">
				<a href="/sobre" class="has-text-grey-dark">SOBRE</a>
			</div>
			<hr class="menu-separator">
			<div class="content has-text-centered is-marginless">
				<a href="/colabore" class="has-text-grey-dark">COLABORE</a>
			</div>
			<hr class="menu-separator">
			<div class="has-text-centered">
				<a href="https://www.facebook.com/grafiasdejunho/" target="_blank" class="has-text-grey">
					<span class="icon">
						<i class="fab fa-facebook"></i>
					</span>
				</a>
			</div>
	    </div>
	@endif
	<div id="app" {{ Route::currentRouteNamed('home') ? 'class="column"' : ''}}>
		@yield('content')
	</div>
	@if(!Route::currentRouteNamed('home'))
		</div>
	@endif
</body>
</html>
