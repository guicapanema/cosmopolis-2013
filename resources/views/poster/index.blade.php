@extends('layouts.app')

@section('content')
	<div class="container">
		{{ Breadcrumbs::render('poster_index') }}
		<div class="content has-text-centered">
			<h3>Banco de cartazes</h3>
		</div>
		<div id="app">
			<poster-table></poster-table>
		</div>
	</div>

@endsection
