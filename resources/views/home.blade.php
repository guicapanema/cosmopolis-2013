@extends('layouts.app')

@section('content')
	<div class="container">
		{{ Breadcrumbs::render('home') }}
		<div class="section">
		    <div class="columns">
		        <div class="column is-8 is-offset-2">
		            <div class="box content">
		                <h3>Painel</h3>

		                <div class="card-body">
		                    @if (session('status'))
		                        <div class="alert alert-success">
		                            {{ session('status') }}
		                        </div>
		                    @endif

							<a type="button" class="button is-primary" href="{{ route('photo_create') }}">
								Enviar imagens
							</a>

							<a type="button" class="button is-primary" href="{{ route('photo_index') }}">
								Listar imagens
							</a>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
@endsection
