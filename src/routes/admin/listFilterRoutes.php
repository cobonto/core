<?php

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
        \Cache::forget($controller->getRoute('filter',false));
        if($filters && count($filters))
            \Cache::add($controller->getRoute('filter',false),$filters,1440);
    }
    elseif($perPage = $request->input('perPage',false))
    {
        \Cache::put($controller->getRoute('perPage',false),$perPage,1440);
    }
    elseif($request->input('resetFilter',false))
    {
        \Cache::forget($controller->getRoute('filter',false));
    }
    return redirect(route($controller->getRoute('index',false)));
})->name('list.filters');