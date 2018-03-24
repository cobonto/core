<?php
/*
|--------------------------------------------------------------------------
| Routes admin
|--------------------------------------------------------------------------
*/
Route::group(
    ['middleware' => ['web'],
        'namespace' => 'Cobonto\\Controllers\\Admin',
    ],
    function ()
    {
        $admin_url = config('app.admin_url');
        Route::get($admin_url.'/login', 'AuthController@showLoginForm')->name($admin_url.'.login');
        Route::post($admin_url.'/login', 'AuthController@login')->name($admin_url.'.login.auth');
        Route::get($admin_url.'/logout', 'AuthController@logout')->name($admin_url.'.logout');
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
        'prefix' => config('app.admin_url'),
        'as' => config('app.admin_url').'.',
    ], function ()
{
    // module routes
    $admin_url = config('app.admin_url');
    Route::get('modules/install/{author}/{name}', 'ModulesController@install')->name('modules.install');
    Route::get('modules/uninstall/{author}/{name}', 'ModulesController@uninstall')->name('modules.uninstall');;
    Route::get('modules/enable/{author}/{name}', 'ModulesController@enable')->name('modules.enable');;
    Route::get('modules/disable/{author}/{name}', 'ModulesController@disable')->name('modules.disable');;
    Route::get('modules/configure/{author}/{name}', 'ModulesController@configuration')->name('modules.configure');;
    Route::post('modules/save/{author}/{name}', 'ModulesController@save')->name('modules.save');;
    Route::post('modules/uploadModule', 'ModulesController@uploadModule')->name('modules.uploadModule');;
    Route::get('modules/rebuild', 'ModulesController@rebuildList')->name('modules.rebuildList');

    // module positions routes
    Route::get('modules/positions', 'ModulePositionsController@index')->name('positions.index');
    Route::post('modules/positions/update', 'ModulePositionsController@updatePositions')->name('positions.update');
    Route::post('modules/positions/unregister', 'ModulePositionsController@unRegister')->name('positions.unregister');
    Route::get('modules/positions/set', 'ModulePositionsController@set')->name('positions.set');
    Route::post('modules/positions/register', 'ModulePositionsController@register')->name('positions.register');
    Route::post('modules/positions/loadhooks', 'ModulePositionsController@loadHooks')->name('positions.loadhooks');
    // permissions route
    Route::get('permissions', 'PermissionsController@index')->name('permissions.index');
    Route::post('permissions/update', 'PermissionsController@updatePermissions')->name('permissions.update');
    Route::post('permissions/role', 'PermissionsController@Role')->name('permissions.role');
    // settings route
    Route::get('settings/{settings}', 'SettingsController@settings')->name('settings.settings');
    Route::post('settings/update', 'SettingsController@updateSettings')->name('settings.update');
    Route::post('settings/load', 'SettingsController@load')->name('settings.load');
    Route::post('settings/ajax/actions', 'SettingsController@ajaxActions')->name('settings.ajax.actions');
    // translate controller
    Route::get('translates','TranslatesController@index')->name('translates.index');
    Route::post('translates/load','TranslatesController@loadFile')->name('translates.load');
    Route::post('translates/save','TranslatesController@saveFile')->name('translates.save');
    Route::post('translates/environment','TranslatesController@loadEnvironment')->name('translates.environment');
    Route::resources([
        'dashboard' => 'DashboardController',
        'users' => 'UsersController',
        'modules' => 'ModulesController',
        'roles'=>'RolesController',
        'groups'=>'GroupsController',
        'admins'=>'AdminsController',
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

/*
|--------------------------------------------------------------------------
| Routes front
|--------------------------------------------------------------------------
*/
Route::get('/url-changed',function(){

})->name('url.changed');
