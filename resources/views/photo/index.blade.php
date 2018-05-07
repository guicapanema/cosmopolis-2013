@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('photo_index') }}
	<div class="content has-text-centered">
		<h3>Banco de imagens</h3>
	</div>

	<div id="app" class="has-margin-50">
		<photo-table></photo-table>
	</div>

@endsection
