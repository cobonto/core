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

//Route::get('/', 'IndexController@index');
// admin
// admin login
Route::get('admin/login', 'Admin\\AuthController@showLoginForm');
Route::post('admin/login', 'Admin\\AuthController@login');
Route::get('admin/logout', 'Admin\\AuthController@logout');
Route::group(
    [
        'namespace' => 'Admin',
        'middleware' => ['admin'],
        'prefix' => 'admin'],function(){
    // module additional resources
    Route::get('modules/install/{author}/{name}', 'ModulesController@install')->name('admin.modules.install');
    Route::get('modules/uninstall/{author}/{name}', 'ModulesController@uninstall')->name('admin.modules.uninstall');;
    Route::get('modules/enable/{author}/{name}', 'ModulesController@enable')->name('admin.modules.enable');;
    Route::get('modules/disable/{author}/{name}', 'ModulesController@disable')->name('admin.modules.disable');;
    Route::get('modules/configure/{author}/{name}', 'ModulesController@configuration')->name('admin.modules.configure');;
    Route::post('modules/save/{author}/{name}', 'ModulesController@save')->name('admin.modules.save');;
    Route::post('modules/uploadModule', 'ModulesController@uploadModule')->name('admin.modules.uploadModule');;
    Route::get('modules/rebuild', 'ModulesController@rebuildList')->name('admin.modules.rebuildList');;
    Route::resources([
        'dashboard'=>'DashboardController',
        'users'=>'UserController',
        'modules'=>'ModulesController',
    ]);
});
