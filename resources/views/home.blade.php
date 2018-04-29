@extends('layouts.app')

@section('content')
	<div class="section">
		<div class="container">
		    <div class="columns">
		        <div class="column is-8 is-offset-2">
		            <div class="box content">
		                <h3>Dashboard</h3>

		                <div class="card-body">
		                    @if (session('status'))
		                        <div class="alert alert-success">
		                            {{ session('status') }}
		                        </div>
		                    @endif

							<a type="button" class="button is-primary" href="{{ route('photo_create') }}">
								Enviar imagens
							</a>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="section">
		<div class="container">
			<div class="grid">
				{{-- @foreach($photos as $photo) --}}
				<div class="grid-item"></div>
				{{-- @endforeach --}}
			</div>
		</div>
	</div>
@endsection
