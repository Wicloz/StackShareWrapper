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

Auth::routes();

Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');

Route::get('/folder/{folder}', 'BrowseController@folder');
Route::get('/file/{file}', 'BrowseController@file');
Route::get('/{path?}', 'BrowseController@request')->where('path', '(.*)');
