<?php
/*
|--------------------------------------------------------------------------
| Routes admin login
|--------------------------------------------------------------------------
*/
Route::group(
    ['middleware' => ['web'],
        'namespace' => 'Cobonto\\Controllers\\Admin',
    ],
    function ()
    {
        $admin_url = config('app.admin_url');
        Route::get($admin_url.'/login', 'AuthController@showLoginForm')->name('admin.login');
        Route::post($admin_url.'/login', 'AuthController@login')->name('admin.login.auth');
        Route::get($admin_url.'/logout', 'AuthController@logout')->name('admin.logout');
    });
/*
|--------------------------------------------------------------------------
| Routes admin controllers
|--------------------------------------------------------------------------
*/
Route::group(
    [
        'namespace' => 'Cobonto\Controllers\Admin',
        'middleware' => ['web', 'admin'],
        'prefix' => config('app.admin_url')], function ()
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
    Route::get('modules/positions', 'ModulePositionsController@index')->name('admin.positions.index');
    Route::post('modules/positions/update', 'ModulePositionsController@updatePositions')->name('admin.positions.update');
    // permissions route
    Route::get('permissions', 'PermissionsController@index')->name('admin.permissions.index');
    Route::post('permissions/update', 'PermissionsController@updatePermissions')->name('admin.permissions.update');
    Route::post('permissions/role', 'PermissionsController@Role')->name('admin.permissions.role');

    Route::resources([
        'dashboard' => 'DashboardController',
        'users' => 'UserController',
        'modules' => 'ModulesController',
        'roles'=>'RolesController',
        'groups'=>'GroupsController',
        'employees'=>'EmployeesController'
    ]);
/*
|--------------------------------------------------------------------------
| require  routes
|--------------------------------------------------------------------------
| for others routes that it's more complicated we separate them in files and require here
*/
    $files = File::allFiles( __DIR__.'/admin');
    foreach ($files as $file)
    {
        require_once($file->getPathName());
    }
});
