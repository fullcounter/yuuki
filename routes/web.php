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

Route::get('/', 'MainController@index');
Route::get('page', function () {
    return App\User::paginate();
});
Route::get('find', 'SearchController@find');
Route::post('/upload', 'MainController@postupload');
Route::get('/upload', 'MainController@upload');