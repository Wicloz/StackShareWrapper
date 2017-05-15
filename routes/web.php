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

Route::get('/folder/{hash}', 'BrowseController@folderHash');
Route::get('/file/{hash}', 'BrowseController@fileHash');
Route::get('/{path?}', 'BrowseController@request')->where('path', '(.*)');
