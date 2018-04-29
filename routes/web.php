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

// Photo routes

Route::get('/fotos', 'PhotoController@index')->name('photo_index');

Route::get('/foto/criar', 'PhotoController@create')->name('photo_create');

Route::post('/foto/criar', 'PhotoController@store')->name('photo_store');
