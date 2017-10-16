<?php

// route for filter list admin
Route::post('list/filters',function(){
    $filters = [];
    $class_name = \Request::input('class_name');
    $request = app('request');
    /** @var \Cobonto\Controllers\AdminController $controller */
    $controller = new $class_name($request);
    if(\Request::has('submitFilter'))
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
                    $column = isset($options['filter_key']) ? $options['filter_key']: $field;
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
                                    'value'=>$from,
                                    'time'=>'00:00:00',
                                ];
                            }

                            if ($to)
                            {
                                $filters[$field.'_to'] = [
                                    'name'=>$column,
                                    'condition'=>'<=',
                                    'type'=>'date',
                                    'value'=>$to,
                                    'time'=>'23:59:59',
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
        // create filter name for each employee
        $filter_name = $controller->route('filter',[],false).'_'.\Auth::guard('admin')->user()->id;
        \Cache::forget($filter_name);
        if($filters && count($filters))
            \Cache::put($filter_name,$filters,1440);
    }
    elseif($perPage = \Request::input('perPage'))
    {
        $perPage_name = $controller->route('perPage',[],false).'_'.\Auth::guard('admin')->user()->id;

        if($perPage>=10)
            \Cache::put($perPage_name,$perPage,1440);
    }
    elseif(\Request::has('resetFilter'))
    {
        \Cache::forget($controller->route('filter',[],false).'_'.\Auth::guard('admin')->user()->id);
    }
    else{
        dd('wrong!!!!');
    }
    return redirect(route($controller->route('index',[],false)));
})->name(config('app.admin_url').'.list.filters');