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
	<div class="columns is-gapless">
		<div class="column is-one-quarter">
			<figure class="image">
				<img src="/images/logo.png" width="100%"></img>
			</figure>
			<div class="content has-text-centered">
				PRINCIPAL | SOBRE | CRÉDITOS
			</div>
			<div class="buttons has-addons is-centered">
				<span class="button">Imagem</span>
				<span class="button">Texto</span>
			</div>
			<div class="field has-margin-100">
				<p class="control has-icons-left">
					<input class="input" type="text" placeholder="Pesquisar">
					<span class="icon is-small is-left">
						<i class="fas fa-search"></i>
					</span>
				</p>
			</div>
			<aside class="menu has-margin-100">
				{{-- <p class="menu-label">
					Geral
				</p>
				<ul class="menu-list">
					<li><a href="/admin" class="{{ Request::is('admin') ? "is-active" : "" }}">Painel</a></li>
				</ul>
				<p class="menu-label">
					Imagens
				</p>
				<ul class="menu-list">
					<li><a href="{{ route('photo_create') }}" class="{{ Request::is('fotos/criar') ? "is-active" : "" }}">Enviar imagens</a></li>
					<li><a href="{{ route('photo_index') }}" class="{{ Request::is('fotos/indice') ? "is-active" : "" }}">Listar imagens</a></li>
				</ul>
				<p class="menu-label">
					Cartazes
				</p>
				<ul class="menu-list">
					<li><a href="{{ route('poster_index') }}" class="{{ Request::is('cartazes/indice') ? "is-active" : "" }}">Listar cartazes</a></li>
				</ul>
				<p class="menu-label">
					Tags
				</p>
				<ul class="menu-list">
					<li><a href="{{ route('tag_index') }}" class="{{ Request::is('tags/indice') ? "is-active" : "" }}">Listar tags</a></li>
				</ul> --}}
			</aside>
		</div>
		<div class="column">
			@yield('content')
		</div>
	</div>
</body>
</html>
