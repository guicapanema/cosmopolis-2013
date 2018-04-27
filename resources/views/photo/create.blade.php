@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enviar imagens</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('photo_store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">Data</label>

                            <div class="col-md-6">
                                <input id="date" type="text" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" value="{{ old('date') }}" required autofocus>

                                @if ($errors->has('date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">Cidade</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required autofocus>

                                @if ($errors->has('city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="form-group row">
                            <label for="photographer" class="col-md-4 col-form-label text-md-right">Fotógrafo</label>

                            <div class="col-md-6">
                                <input id="photographer" type="text" class="form-control{{ $errors->has('photographer') ? ' is-invalid' : '' }}" name="photographer" value="{{ old('photographer') }}" required autofocus>

                                @if ($errors->has('photographer'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('photographer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="form-group row">
                            <label for="license" class="col-md-4 col-form-label text-md-right">Licença de uso</label>

                            <div class="col-md-6">
                                <input id="license" type="text" class="form-control{{ $errors->has('license') ? ' is-invalid' : '' }}" name="license" value="{{ old('license') }}" required autofocus>

                                @if ($errors->has('license'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('license') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

						<div class="form-group row">
                            <label for="photos" class="col-md-4 col-form-label text-md-right">Imagens</label>

                            <div class="col-md-6">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="photos" name="photos[]" accept=".jpg, .jpeg, .png" multiple>
									<label class="custom-file-label" for="photos">Choose file</label>
								</div>

                                @if ($errors->has('photos'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('photos') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Enviar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
