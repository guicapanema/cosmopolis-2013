@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('photo_edit', $photo) }}
	<div id="app" class="has-margin-50">

		<div class="content has-text-centered">
			<h3>Editar imagem</h3>
		</div>

		<photo-edit photo_id="{{$photo->id}}"></photo-edit>
	</div>
@endsection
