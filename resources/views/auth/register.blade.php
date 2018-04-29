
@extends('layouts.app')

@section('content')

<div class="container">
	<div class="section">
		<div class="content has-text-centered">
			<h3>Registrar</h3>
		</div>

		<div class="columns">
			<div class="column is-4 is-offset-4">
				<form method="POST" action="{{ route('register') }}">
					@csrf

					<div class="field">
						<label class="label">Nome</label>
						<div class="control">
							<input type="text" class="input{{ $errors->has('name') ? ' is-danger' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
						</div>
						@if ($errors->has('name'))
							<p class="help is-danger">{{ $errors->first('name') }}</p>
						@endif
					</div>

					<div class="field">
						<label class="label">Email</label>
						<div class="control">
							<input type="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
						</div>
						@if ($errors->has('email'))
							<p class="help is-danger">{{ $errors->first('email') }}</p>
						@endif
					</div>

					<div class="field">
						<label class="label">Senha</label>
						<div class="control">
							<input type="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" required>
						</div>
						@if ($errors->has('password'))
							<p class="help is-danger">{{ $errors->first('password') }}</p>
						@endif
					</div>

					<div class="field">
						<label class="label">Confirmar senha</label>
						<div class="control">
							<input type="password" class="input" name="password_confirmation" required>
						</div>
					</div>

					<div class="field">
						<p class="control">
							<button type="submit" class="button is-success">
								Registrar
							</button>
						</p>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
