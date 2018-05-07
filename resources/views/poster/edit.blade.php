@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('poster_edit', $poster) }}
	<div id="app" class="section">
		<div class="content has-text-centered">
			<h3>Editar cartaz</h3>
		</div>

		<div class="columns">
			<div class="column is-4 is-offset-4">
				<form method="POST" action="{{ route('poster_update', ['poster' => $poster->id]) }}">
					@csrf
					@method('put')

					<div class="field">
						<label class="label">Texto</label>
						<div class="control">
							<input type="text" class="input{{ $errors->has('text') ? ' is-danger' : '' }}" name="text" value="{{ old('text') ? old('text') : $poster->text }}" required autofocus>
						</div>
						@if ($errors->has('text'))
							<p class="help is-danger">{{ $errors->first('text') }}</p>
						@endif

					</div>

					<div class="field">
						<p class="control">
							<button type="submit" class="button is-success">
								Salvar
							</button>
						</p>
					</div>
				</form>
			</div>
		</div>
		<div class="content">
			<h5>Aparece em:</h5>
		</div>
		<div class="columns is-multiline">
			@foreach($poster->photos as $photo)
				<div class="column is-one-quarter">
					<figure class="image">
						<a href="/fotos/{{$photo->id}}/editar">
							<img src="/storage/{{$photo->path}}"></img>
						</a>
					</figure>
				</div>
			@endforeach
		</div>
	</div>

@endsection
