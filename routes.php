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
        'namespace' => 'Cobonto\Controllers\Admin',
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

    // route for filter list admin
    Route::post('list/filters',function(){
        $filters = [];
        $class_name = Request::input('class_name');
        $request = app('request');
        /** @var \Cobonto\Controllers\AdminController $controller */
        $controller = new $class_name($request);
        if(Request::input('submitFilter'))
        {
            $fields_list = $controller->getFieldList();
            if (count($fields_list))
            {
                foreach ($fields_list as $field => &$options)
                {
                    $filter_type=true;
                    if (!isset($options['filter_type']))
                        $filter_type = false;

                    if (isset($options['filter']) && $options['filter'] == false)
                        continue;
                    else
                    {

                        $value = $request->input('filter_' . $field);
                        $column = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
                        if (!$value && !$filter_type)
                            continue;
                        if ($filter_type && $options['filter_type'] == 'date')
                        {

                            $from = $request->input('filter_' . $field . '_from');
                            $to = $request->input('filter_' . $field . '_to');
                            if (!$from && !$to)
                                continue;
                            else
                            {
                                if ($from)
                                {
                                    $filters[$field.'_from'] = [
                                        'name'=>$column,
                                        'condition'=>'>=',
                                        'type'=>'date',
                                        'value'=>$from
                                    ];
                                }

                                if ($to)
                                {
                                    $filters[$field.'_to'] = [
                                        'name'=>$column,
                                        'condition'=>'<=',
                                        'type'=>'date',
                                        'value'=>$to
                                    ];
                                }
                            }
                        }
                        elseif ($filter_type && ($options['filter_type'] == 'select' || $options['filter_type'] == 'bool'))
                        {
                            if($value !=="")
                                $filters[$field] = [
                                    'name'=>$column,
                                    'condition'=>'=',
                                    'type'=>'select',
                                    'value'=>$value
                                ];
                        }
                        else
                        {
                            $filters[$field] = [
                                'name'=>$column,
                                'condition'=>'LIKE',
                                'type'=>'text',
                                'value'=>'%'.$value.'%',
                            ];
                        }
                    }
                }
            }
            \Cache::forget($controller->getRoute().'filter');
            if($filters && count($filters))
                \Cache::add($controller->getRoute().'filter',$filters,1440);
        }
        elseif($perPage = $request->input('perPage'))
        {
             \Cache::put($controller->getRoute().'perPage',$perPage,1440);
        }
        elseif($request->input('cancelFilter'))
        {
            \Cache::forget($controller->getRoute().'filter');
        }
        return redirect(route($controller->getRoute().'index'));
    })->name('admin.list.filters');
});
