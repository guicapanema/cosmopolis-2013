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
	<div id="app">
		@yield('content')
	</div>
</body>
</html>
