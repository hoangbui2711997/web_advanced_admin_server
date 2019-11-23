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
Route::get('/', function () {
    return redirect()->route('admin.home');
});
Route::get('/admin/login', 'HomeController@login')->name('login');
Route::post('/admin/login', 'Auth\LoginController@login');
Route::post('/admin/register', 'Auth\RegisterController@signup');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('admin.home');
    Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)');
    Route::post('/logout', 'Auth\LoginController@logout');
});


Route::get('404', 'HomeController@showNotFoundPage');
Route::get('auth', 'HomeController@authUrl')->middleware('auth');

