@extends('layouts.app')

@section('content')

	{{ Breadcrumbs::render('home') }}
	<div class="columns">
		<div class="column is-8 is-offset-2">
			<div class="content has-text-centered">
				<h3>Painel</h3>
			</div>
			<div class="content">
				<p class="has-text-centered">
					Em construção
				</p>

				{{-- <a type="button" class="button is-primary" href="{{ route('photo_create') }}">
					Enviar imagens
				</a>

				<a type="button" class="button is-primary" href="{{ route('photo_index') }}">
					Listar imagens
				</a>

				<a type="button" class="button is-primary" href="{{ route('poster_index') }}">
					Listar cartazes
				</a> --}}
			</div>
		</div>
	</div>

@endsection
