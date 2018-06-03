<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/front.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js"></script>


    <!-- Styles -->
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">

</head>
<body>
	@if(!Route::currentRouteNamed('home'))
		<div class="columns is-gapless is-marginless">
			<div class="column is-one-quarter">
				<figure class="image">
					<a href="/principal">
						<img src="/images/logo.png" width="100%"></img>
					</a>
				</figure>
			</div>
		</div>
		<div class="columns is-gapless">
		<div class="column is-one-quarter">
			<div class="content has-text-centered is-marginless">
				<a href="/principal" class="has-text-grey-dark">PRINCIPAL</a>
			</div>
			<hr class="menu-separator">
			<div class="content has-text-centered is-marginless">
				<a href="/sobre" class="has-text-grey-dark">SOBRE</a>
			</div>
			<hr class="menu-separator">
			<div class="content has-text-centered is-marginless">
				<a href="/creditos" class="has-text-grey-dark">CRÉDITOS</a>
			</div>
			<hr class="menu-separator">
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
