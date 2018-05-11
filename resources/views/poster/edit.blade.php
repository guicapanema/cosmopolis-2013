@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('poster_edit', $poster) }}
	<div id="app" class="section">
		<div class="content has-text-centered">
			<h3>Editar cartaz</h3>
		</div>

		<poster-edit poster_id="{{ $poster->id }}"></poster-edit>
	</div>
@endsection
