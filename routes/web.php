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

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware('auth')->get('/{id}', 'HomeController@index')->name('index');

Route::middleware('auth')->post('/load','HomeController@load')->name('load');

Route::middleware('auth')->post('/makeDir','HomeController@makeDir')->name('makeDir');;

Route::middleware('auth')->post('/delDir','HomeController@delDir')->name('delDir');;

Route::middleware('auth')->post('/delFile','HomeController@delFile')->name('delFile');;

Route::middleware('auth')->post('/editFile','HomeController@editFile')->name('editFile');;

Route::middleware('auth')->post('link/', 'HomeController@linkFile')->name('link');
