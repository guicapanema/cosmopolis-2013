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
