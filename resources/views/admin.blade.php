@extends('layouts.app')

@section('content')

	{{ Breadcrumbs::render('admin') }}
	<div class="content has-text-centered">
		<h3>Painel</h3>
	</div>
	<div class="columns">
		<div class="column is-one-third has-text-centered">
			<div>
				<p class="heading">Fotos</p>
				<p class="title">{{ $photos->count() }}</p>
			</div>
		</div>
		<div class="column is-one-third has-text-centered">
			<div>
				<p class="heading">Cartazes</p>
				<p class="title">{{ $posters->count() }}</p>
			</div>
		</div>
		<div class="column is-one-third has-text-centered">
			<div>
				<p class="heading">Tags</p>
				<p class="title">{{ $tags->count() }}</p>
			</div>
		</div>
	</div>
	@if (Request::query('mostrarInconsistencias') === null)
		<div class="has-text-centered">
			<a class="button is-primary" href="?mostrarInconsistencias=1">
				Mostrar Inconsistências
			</a>
		</div>

	@else
		<div class="has-margin-50">
			<div class="columns">
				<div class="column is-one-third">
					<div class="card">
						<header class="card-header">
							<p class="card-header-title">
								Fotos com cartazes repetidos
							</p>
						</header>
						<div>
							<table class="table is-striped is-fullwidth">
								<tbody>
									@foreach($photos as $photo)
										@if(App\Photo::find($photo->id)->posters->groupBy('text')->count() !== App\Photo::find($photo->id)->posters->count())
										<tr>
											<td>
												<a href="fotos/{{ $photo->id }}/editar" style="word-wrap:break-word;">
													{{ $photo->name }}
												</a>
											</td>
										</tr>
										@endif
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="column is-one-third">
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
				<div class="column is-one-third">
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
	@endif

@endsection
