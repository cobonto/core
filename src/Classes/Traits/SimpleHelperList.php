<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 10/15/2016
 * Time: 11:30 AM
 */

namespace Cobonto\Classes\Traits;

use Carbon\Carbon;
use Cobonto\Controllers\ModuleAdminController;
use Jenssegers\Date\Date;
use Morilog\Jalali\jDateTime;

trait SimpleHelperList
{
    protected $position_identifier = false;
    protected $use_simple_pagination = false;
    protected $per_page=false;
    protected function generateList()
    {
        if ($this->tpl == false)
            $this->tpl = $this->theme.'.helpers.list.simple.main';
        $rows = [];
        if (count($this->fields_list))
        {
            // first get rows from database
            $this->listQuery();
            // add filters from cache
            $this->filters();
            // add pagination
            $rows = $this->pagination();
        }
        //add javascript vars
        $this->assign->addJSVars(
            ['alert_msg'=>$this->lang('sure_to_delete'),
                'list_positions_update'=>adminRoute('list.positions.update'),
                'controller'=>get_class($this),
                'module'=>($this instanceof ModuleAdminController)?$this->getModule()->name:false,
            ]);
        // add css and img files
        $this->listAssets();
        // call helper method before render view
        $this->beforeGenerateSimpleList($rows);
        // assign variables to view
        $this->assign->params([
            'search' => false,
            'listTitle'=>$this->listTitle(),
            'create' => $this->create,
            'fields' => $this->fields_list,
            'rows' => $rows,
            'position_identifier'=>$this->position_identifier,
            'actions' => $this->actions,
            'skip_actions' => $this->skip_actions,
            'filters' => $this->filter,
            'sqlRows'=>$this->sql
        ]);
    }

    protected function listAssets()
    {
        $this->assign->addCSS(['css/list.css']);
        $this->assign->addJS(['js/list.js']);
        #    $this->assign->addJS(['js/simple.list.js']);
        $this->assign->addPlugin('confirm');
        $this->assign->addPlugin('growl');
        $this->assign->addPlugin('selecttwo');
        $this->assign->addPlugin('datepicker');
        if(config('app.rtl')){
            $this->assign->addJS('plugins/datepicker/datepicker.fa.min.js',true);
        }

    }

    protected function beforeGenerateSimpleList(&$data)
    {

    }
    protected function filters()
    {
        $filters = \Cache::get($this->route('filter',[],false).'_'.\Auth::guard('admin')->user()->id);
        if (count($this->fields_list) && $filters &&  is_array($filters))
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
        $this->assign->params(['filter_values'=>$filters]);
    }
    protected function pagination()
    {
        $perPage = \Cache::get($this->route('perPage',[],false).'_'.\Auth::guard('admin')->user()->id,$this->per_page);
        if(!$perPage)
            $perPage=10;
        $this->assign->params(['per_page'=>$perPage]);
        if($this->use_simple_pagination)
            return $this->sql->simplePaginate($perPage);
        return  $this->sql->paginate($perPage);
    }
    public function displayStatus($row)
    {
        return '<i class="fa '.($row->active?'fa-check btn btn-success':'fa-remove btn btn-danger').'"></i>';
    }
    protected function listTitle($titles=[],$id=1)
    {
        if(!count($titles))
            $titles[] =['id'=>0,'name'=>$this->title,'link'=>false];
        return view($this->theme.'.helpers.list.simple.list_title',['titles'=>$titles,'id'=>$id]);
    }

    public function updatePositions($positions=[])
    {
        if(count($positions))
        {
            $ids =[];
            $positionsIds=[];
            foreach($positions as $position)
            {
                list($id,$id_position) = explode('|',$position);
                $ids[] = $id;
                $positionsIds[] = $id_position;
            }
            sort($positionsIds,SORT_NUMERIC);
            foreach($ids as $key=>$id)
            {
                \DB::table($this->table)->where($this->position_identifier,$id)->update(['position'=>$positionsIds[$key]]);
            }
            return ['status'=>'success','msg'=>$this->lang('update_success')];
        }
    }

}