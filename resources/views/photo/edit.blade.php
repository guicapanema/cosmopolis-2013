@extends('layouts.app')

@section('content')
	<div id="app" class="has-margin-50">
		{{ Breadcrumbs::render('photo_edit', $photo) }}

		<div class="content has-text-centered">
			<h3>Editar imagem</h3>
		</div>

		<photo-edit photo_id="{{$photo->id}}"></photo-edit>
	</div>
@endsection
