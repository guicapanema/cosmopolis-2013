@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('tag_edit', $tag) }}
	<div id="app" class="section">
		<div class="content has-text-centered">
			<h3>Editar tag</h3>
		</div>
		<div class="columns is-centered">
			<div class="column is-6">
				<form method="POST" action="{{ route('tag_update', ['tag' => $tag->id]) }}">
					@csrf
					@method('put')

					<div class="field">
						<label class="label">Texto</label>
						<div class="field is-grouped">
							<p class="control is-expanded">
								<input type="text" class="input{{ $errors->has('text') ? ' is-danger' : '' }}" name="text" value="{{ old('text') ? old('text') : $tag->text }}" autofocus>
							</p>
							<p class="control">
								<button type="submit" class="button is-success">
									Salvar
								</button>
							</p>
						</div>
						@if ($errors->has('text'))
							<p class="help is-danger">{{ $errors->first('text') }}</p>
						@endif
					</div>
				</form>
			</div>
		</div>

		<div class="has-margin-50 content">
			<h5>Cartazes:</h5>

			<ul>
				@foreach($tag->posters as $poster)
					<li>
						<a href="/cartazes/{{ $poster->id }}/editar">
							{{ $poster->text }}
						</a>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endsection
