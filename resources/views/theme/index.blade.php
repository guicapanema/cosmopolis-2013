@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('temas.index') }}
	<div class="content has-text-centered">
		<h3>Temas</h3>
	</div>
	<div id="app" class="has-margin-50">
		<theme-index></theme-index>
	</div>
@endsection
