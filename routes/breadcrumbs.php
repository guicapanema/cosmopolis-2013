<?php

// Painel
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Painel', route('home'));
});

// Painel > Enviar imagens
Breadcrumbs::register('photo_create', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Enviar imagens', route('photo_create'));
});

// Painel > Banco de imagens
Breadcrumbs::register('photo_index', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Banco de imagens', route('photo_index'));
});

// Painel > Banco de imagens > [Imagem]
Breadcrumbs::register('photo_edit', function ($breadcrumbs, $photo) {
	$breadcrumbs->parent('photo_index');
	$breadcrumbs->push($photo->name, route('photo_edit', $photo));
});

// Painel > Banco de cartazes
Breadcrumbs::register('poster_index', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Banco de cartazes', route('poster_index'));
});

// Painel > Banco de cartazes > Editar cartaz
Breadcrumbs::register('poster_edit', function ($breadcrumbs, $poster) {
	$breadcrumbs->parent('poster_index');
    $breadcrumbs->push('Editar cartaz', route('poster_edit', $poster->id));
});
