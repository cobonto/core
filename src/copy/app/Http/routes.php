<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::auth();

Route::get('/home', 'HomeController@index');
// admin
// admin login
Route::get('admin/login', 'Admin\\AuthController@showLoginForm');
Route::post('admin/login', 'Admin\\AuthController@login');
Route::get('admin/logout', 'Admin\\AuthController@logout');
Route::group(
    [
        'namespace' => 'Admin',
        'middleware' => ['admin','web'],
        'prefix' => 'admin'],function(){
    Route::get('dashboard', 'DashboardController@index');
    Route::get('users', 'UserController@getIndex');
    Route::get('users/databalse',['as'=>'datatable','uses'=>'UserController@myData']);
});
