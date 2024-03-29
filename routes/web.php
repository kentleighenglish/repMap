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

Route::get('/config', 'PageController@config');
Route::get('/', 'PageController@index');

Route::get('/admin', 'PageController@admin');

Route::get('/{slug}', 'PageController@index');
