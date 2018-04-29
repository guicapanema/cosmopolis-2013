@extends('layouts.app')

@section('content')

<div class="container">
	<div class="section">
		<div class="content has-text-centered">
			<h3>Login</h3>
		</div>

		<div class="columns">
			<div class="column is-4 is-offset-4">
				<form method="POST" action="{{ route('login') }}">
					@csrf

					<div class="field">
						<p class="control has-icons-left">
							<input type="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
							<span class="icon is-small is-left">
								<i class="fas fa-envelope"></i>
							</span>
						</p>
						@if ($errors->has('email'))
							<p class="help is-danger">{{ $errors->first('email') }}</p>
						@endif
					</div>

					<div class="field">
						<p class="control has-icons-left">
							<input type="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" placeholder="Senha" required>
							<span class="icon is-small is-left">
								<i class="fas fa-lock"></i>
							</span>
						</p>
						@if ($errors->has('password'))
							<p class="help is-danger">{{ $errors->first('password') }}</p>
						@endif
					</div>

					<div class="field">
						<div class="control">
							<label class="checkbox">
								<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
								Lembrar de mim
							</label>
						</div>
					</div>

					<div class="field">
						<p class="control">
							<button type="submit" class="button is-success">
								Login
							</button>
						</p>
					</div>
					<div class="has-text-right">
						<a class="btn btn-link" href="{{ route('password.request') }}">
							Esqueceu a senha?
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
