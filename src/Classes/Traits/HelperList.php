<?php

namespace  RmsCms\Classes\Traits;

use Yajra\Datatables\Html\Builder;
trait HelperList
{
    /** @var  array $fields */
    protected $fields_list = [];

    /** @var array $actions */
    protected $actions;
    /** @var Builder */
    protected $listBuilder=false;
    /** @var string url */
    protected $ajax_list_url;
    protected function generateList()
    {
        $this->listBuilder = app('datatables')->getHtmlBuilder();
        // we calculate fields and create some data for dataTable
        if(count($this->fields_list))
        {
            foreach($this->fields_list as $field=>$options)
            {
                $options['data'] =  $options['name']=$field;
                $this->listBuilder->addColumn($options);
            }
        }
        // add assign
        app('assign')->view('HtmlList',$this->listBuilder);
    }
    protected function listQuery()
    {

    }
}