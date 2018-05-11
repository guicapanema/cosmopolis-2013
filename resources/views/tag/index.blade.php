@extends('layouts.app')

@section('content')
	{{ Breadcrumbs::render('tag_index') }}
	<div class="content has-text-centered">
		<h3>Banco de tags</h3>
	</div>
	<div id="app" class="has-margin-50">
		<tag-table></tag-table>
	</div>
@endsection
