@extends('layouts.app')

@section('content')
	<div id="app" class="container">
		{{-- {{ Breadcrumbs::render('photo_bulk_edit', $photos) }} --}}
		<div class="section content has-text-centered">
			<h3>Editar imagens</h3>
		</div>

		@foreach ($photos as $photo)
			<photo-edit photo_id="{{$photo->id}}"></photo-edit>
			<hr></hr>
		@endforeach

		<div class="section has-text-centered">
			<a type="button" class="button is-primary is-medium" href="{{ route('photo_index') }}">
				Finalizar
			</a>
		</a>
	</div>
@endsection
