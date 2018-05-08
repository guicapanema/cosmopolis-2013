<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/**** PHOTO ROUTES ****/

Route::get('/fotos/indice', 'PhotoController@index')->name('photo_index');

Route::get('/fotos/criar', 'PhotoController@create')->name('photo_create');

Route::get('/fotos/{photo}/editar', 'PhotoController@edit')->name('photo_edit');

// API routes

Route::post('/fotos', 'PhotoController@store')->name('photo_store');

Route::get('/fotos', 'PhotoController@list')->name('photo_list');

Route::get('/fotos/{photo}', 'PhotoController@retrieve')->name('photo_retrieve');

Route::put('/fotos/{photo}', 'PhotoController@update')->name('photo_update');

Route::delete('/fotos/{photo}', 'PhotoController@destroy')->name('photo_destroy');


/**** POSTER ROUTES ****/

Route::get('/cartazes/indice', 'PosterController@index')->name('poster_index');

Route::get('/cartazes/{poster}/editar', 'PosterController@edit')->name('poster_edit');


// API routes

Route::post('/cartazes', 'PosterController@store')->name('poster_store');

Route::get('/cartazes', 'PosterController@list')->name('poster_list');

Route::put('/cartazes/{poster}', 'PosterController@update')->name('poster_update');

Route::delete('/cartazes/{poster}', 'PosterController@destroy')->name('poster_destroy');

Route::get('/fotos/{photo}/cartazes', 'PosterController@filterByPhoto')->name('poster_filter_by_photo');

Route::post('/fotos/{photo}/cartazes', 'PosterController@attachToPhoto')->name('poster_attach_to_photo');

Route::put('/fotos/{photo}/cartazes/{poster}', 'PosterController@updatePhotoRelationship')->name('poster_update_photo_relationship');

Route::delete('/fotos/{photo}/cartazes/{poster}', 'PosterController@detachFromPhoto')->name('poster_detach_from_photo');

/**** TAG ROUTES ****/

// API routes

Route::get('/tags', 'TagController@list')->name('tag_list');

Route::post('/tags', 'TagController@store')->name('tag_store');
