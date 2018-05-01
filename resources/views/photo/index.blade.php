@extends('layouts.app')

@section('content')
	<div class="container">
		{{ Breadcrumbs::render('photo_index') }}

		<div class="section">
			<div class="content has-text-centered">
				<h3>Banco de imagens</h3>
			</div>

			<div class="columns">
				<div class="column is-8 is-offset-2">
					<table class="table">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Data</th>
								<th>Cidade</th>
								<th>Fotógrafo</th>
								<th>Licença</th>
								<th>Nº cartazes</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($photos as $photo)
								<tr>
									<td><a href="{{ route('photo_edit', [ 'photo' => $photo ]) }}">{{ $photo->name }}</a></td>
									<td>{{ $photo->date }}</td>
									<td>{{ $photo->city }}</td>
									<td>{{ $photo->photographer }}</td>
									<td>{{ $photo->license }}</td>
									<td>{{ $photo->posters ? count($photo->posters) : '0' }}</td>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

@endsection
