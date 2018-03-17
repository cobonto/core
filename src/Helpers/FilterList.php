<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 1/2/2018
 * Time: 8:27 PM
 */

namespace App\Http\Controllers\Admin\Helpers;


use App\GenerateBill;
use App\Pan;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\jDateTime;

trait FilterList
{
    protected function getFilters(Request $request)
    {
        $fields_list = $this->getFieldList();
        $filters = [];
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
        return $filters;
    }

    protected function loadFilterQuery(Request $request)
    {
        $this->listQuery();
        $filters = $this->getFilters($request);
        if (count($this->getFieldList()) && $filters &&  is_array($filters))
        {
            foreach ($filters as $field=>$filter)
            {
                if($filter['type']=='date')
                {
                    $time = explode(':',$filter['time']);
                    $date = explode('/',$filter['value']);
                    // if system is rtl need to change
                    if(config('app.rtl')){
                        $date = jDateTime::toGregorian($date[2],$date[1],$date[0]);
                        $filter['value'] = Carbon::create($date[0],$date[1],$date[2],$time[0],$time[1],$time[2]);
                    }
                    else{
                        $filter['value'] = Carbon::create($date[2],$date[0],$date[1],$time[0],$time[1],$time[2]);
                    }
                }
                $this->sql->where($filter['name'],$filter['condition'],$filter['value']);
            }
        }
    }
}