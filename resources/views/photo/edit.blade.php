@extends('layouts.app')

@section('content')
	<div id="app" class="container">
		{{ Breadcrumbs::render('photo_edit', $photo) }}

		<photo-edit photo_id="{{$photo->id}}"></photo-edit>
	</div>
@endsection
