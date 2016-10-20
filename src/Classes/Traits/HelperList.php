<?php

namespace Cobonto\Classes\Traits;


trait HelperList
{
    /** @var  array $fields */
    protected $fields_list = [];
    /** @var bool $autoIncrement check if list has autoincrement */
    protected $autoIncrement = true;
    /** @var $create */
    protected $create = true;
    /** @var array $actions */
    protected $actions = [
        'edit' => ['name' => 'Edit'],
        'destroy' => ['name' => 'Delete'],
    ];
    protected $skip_actions = [];
    /** @var string url */
    protected $ajax_list_url;
    /** @var \DB sql */
    protected $sql;

    /**
     * get actions
     * @param $action
     * @param $params
     */
    protected function addActions($action, $params)
    {
        $this->actions[$action] = $params;
    }
    /**
     * remove action from list
     * @param $action
     */
    protected function removeAction($action)
    {
        if (isset($this->actions[$action]))
            unset($this->actions[$action]);
    }
    protected function fieldList()
    {

    }
    public function getFieldList()
    {
        $this->fieldList();
        return $this->fields_list;
    }
}