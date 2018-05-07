@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('poster_index') }}
	<div class="content has-text-centered">
		<h3>Banco de cartazes</h3>
	</div>
	<div id="app" class="has-margin-50">
		<poster-table></poster-table>
	</div>
@endsection
