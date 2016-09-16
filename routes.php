<?php

Route::group(
    ['middleware' => ['web'],
        'namespace' => 'Cobonto\\Controllers\\Admin',
    ],
    function ()
    {
        Route::get('admin/login', 'AuthController@showLoginForm')->name('admin.login');
        Route::post('admin/login', 'AuthController@login')->name('admin.login.auth');
        Route::get('admin/logout', 'AuthController@logout')->name('admin.logout');
    });
Route::group(
    [
        'namespace' => 'Cobonto\\Controllers\\Admin',
        'middleware' => ['web', 'admin'],
        'prefix' => 'admin'], function ()
{
    // module routes

    Route::get('modules/install/{author}/{name}', 'ModulesController@install')->name('admin.modules.install');
    Route::get('modules/uninstall/{author}/{name}', 'ModulesController@uninstall')->name('admin.modules.uninstall');;
    Route::get('modules/enable/{author}/{name}', 'ModulesController@enable')->name('admin.modules.enable');;
    Route::get('modules/disable/{author}/{name}', 'ModulesController@disable')->name('admin.modules.disable');;
    Route::get('modules/configure/{author}/{name}', 'ModulesController@configuration')->name('admin.modules.configure');;
    Route::post('modules/save/{author}/{name}', 'ModulesController@save')->name('admin.modules.save');;
    Route::post('modules/uploadModule', 'ModulesController@uploadModule')->name('admin.modules.uploadModule');;
    Route::get('modules/rebuild', 'ModulesController@rebuildList')->name('admin.modules.rebuildList');

    // module positions routes
    Route::get('modules/positions', 'ModulePositionsController@index')->name('admin.positions');
    Route::post('modules/positions/update', 'ModulePositionsController@updatePositions')->name('admin.positions.update');
    Route::resources([
        'dashboard' => 'DashboardController',
        'users' => 'UserController',
        'modules' => 'ModulesController',
    ]);
});
