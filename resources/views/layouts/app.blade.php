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
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
	<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
	<nav class="navbar is-primary">
		<div class="container">
			<div class="navbar-brand has-text-weight-bold">
				<a class="navbar-item">
					{{ config('app.name', 'Laravel') }}
				</a>
			</div>

			<div class="navbar-menu">
				<div class="navbar-end">
					@guest
						<a class="navbar-item" href="{{ route('login') }}">
							<span>{{ __('Login') }}</span>
						</a>
						<a class="navbar-item" href="{{ route('register') }}">
							<span>Registrar</span>
						</a>
					@else
						<div class="navbar-item has-dropdown is-hoverable">
							<a class="navbar-link">
								{{ Auth::user()->name }}
							</a>

							<div class="navbar-dropdown">
								<a href="{{ route('logout') }}" class="navbar-item" onclick="event.preventDefault();
											  document.getElementById('logout-form').submit();">
									{{ __('Logout') }}
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>

							</div>
						</div>
					@endguest
				</div>
			</div>
		</div>
	</nav>
	<div>
		@yield('content')
	</div>
</body>
</html>
