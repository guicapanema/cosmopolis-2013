@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="section">
			<div class="content has-text-centered">
				<h3>Enviar Imagens</h3>
			</div>

			<div class="columns">
				<div class="column is-4 is-offset-4">
					<form method="POST" action="{{ route('photo_store') }}" enctype="multipart/form-data">
						@csrf

						<div class="field">
							<label class="label">Data</label>
							<div class="control">
								<input type="text" class="input{{ $errors->has('date') ? ' is-danger' : '' }}" name="date" value="{{ old('date') }}" placeholder="DD/MM/YYYY" required autofocus>
							</div>
							@if ($errors->has('date'))
								<p class="help is-danger">{{ $errors->first('date') }}</p>
							@endif
						</div>

						<div class="field">
							<label class="label">Cidade</label>
							<div class="control">
								<input type="text" class="input{{ $errors->has('city') ? ' is-danger' : '' }}" name="city" value="{{ old('city') }}" required autofocus>
							</div>
							@if ($errors->has('city'))
								<p class="help is-danger">{{ $errors->first('city') }}</p>
							@endif
						</div>

						<div class="field">
							<label class="label">Fotógrafo</label>
							<div class="control">
								<input type="text" class="input{{ $errors->has('photographer') ? ' is-danger' : '' }}" name="photographer" value="{{ old('photographer') }}" required autofocus>
							</div>
							@if ($errors->has('photographer'))
								<p class="help is-danger">{{ $errors->first('photographer') }}</p>
							@endif
						</div>

						<div class="field">
							<label class="label">Licença de Uso</label>
							<div class="control">
								<input type="text" class="input{{ $errors->has('license') ? ' is-danger' : '' }}" name="license" value="{{ old('license') }}" required autofocus>
							</div>
							@if ($errors->has('license'))
								<p class="help is-danger">{{ $errors->first('license') }}</p>
							@endif
						</div>

						<div class="field">
							<div class="file has-name">
								<label class="file-label">
									<input id="photos" class="file-input" type="file" name="photos[]" accept=".jpg, .jpeg, .png" multiple>
									<span class="file-cta">
										<span class="file-icon">
											<i class="fas fa-upload"></i>
										</span>
										<span class="file-label">
											Escolher arquivos
										</span>
									</span>
									<span id="file-name" class="file-name" style="display:none">
									</span>
								</label>
							</div>
						</div>

						<div class="field">
							<p class="control">
								<button type="submit" class="button is-success">
									Enviar
								</button>
							</p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		var photos = document.getElementById("photos");
		photos.onchange = function(){
		    if(photos.files.length > 0)
		    {
				var fileName = document.getElementById('file-name');
				fileName.innerHTML = photos.files.length + ' arquivos';
				fileName.removeAttribute("style")
		    }
		};
	</script>

@endsection
