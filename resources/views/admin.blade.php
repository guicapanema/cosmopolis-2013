@extends('layouts.app')

@section('content')

	{{ Breadcrumbs::render('admin') }}
	<div class="content has-text-centered">
		<h3>Painel</h3>
	</div>
	<nav class="level">
		<div class="level-item has-text-centered">
			<div>
				<p class="heading">Fotos</p>
				<p class="title">{{ $photos->count() }}</p>
			</div>
		</div>
		<div class="level-item has-text-centered">
			<div>
				<p class="heading">Cartazes</p>
				<p class="title">{{ $posters->count() }}</p>
			</div>
		</div>
		<div class="level-item has-text-centered">
			<div>
				<p class="heading">Tags</p>
				<p class="title">{{ $tags->count() }}</p>
			</div>
		</div>
	</nav>
	<div class="has-margin-50">
		<div class="columns">
			<div class="column">
				<div class="card">
					<header class="card-header">
						<p class="card-header-title">
							Cartazes sem fotos
						</p>
					</header>
					<div>
						<table class="table is-striped is-fullwidth">
							<tbody>
								@foreach(App\Poster::doesntHave('photos')->get() as $poster)
									<tr>
										<td>
											<a href="cartazes/{{ $poster->id }}/editar">
												{{ $poster->text }}
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="column">
				<div class="card">
					<header class="card-header">
						<p class="card-header-title">
							Tags sem cartazes
						</p>
					</header>
					<div>
						<table class="table is-striped is-fullwidth">
							<tbody>
								@foreach(App\Tag::doesntHave('posters')->get() as $tag)
									<tr>
										<td>
											<a href="tags/{{ $tag->id }}/editar">
												{{ $tag->text }}
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
