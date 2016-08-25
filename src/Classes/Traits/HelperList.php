<?php

namespace Cobonto\Classes\Traits;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;

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
    // callback validate for dataTable
    protected $validCallbacks = [
        'createdRow',
        'drawCallback',
        'footerCallback',
        'formatNumber',
        'headerCallback',
        'infoCallback',
        'initComplete',
        'preDrawCallback',
        'rowCallback',
        'stateLoadCallback',
        'stateLoaded',
        'stateLoadParams',
        'stateSaveCallback',
        'stateSaveParams',
    ];
    private $tpl_list = 'admin.helpers.list.main';
    /** @var array skip actions */
    protected $skip_actions = [];
    /** @var Builder */
    protected $listBuilder = false;
    /** @var string url */
    protected $ajax_list_url;
    /** @var \DB sql */
    protected $sql;

    protected function generateList()
    {
        $this->listBuilder = app('datatables')->getHtmlBuilder();
        // we calculate fields and create some data for dataTable
        if (count($this->fields_list)) {
            foreach ($this->fields_list as $field => $options) {
                $options['data'] = $options['name'] = $field;
                $this->listBuilder->addColumn($options);
            }
            $this->assign->addCSS(['plugins/datatables/dataTables.bootstrap.css',
                                 'css/list.css']);
            $this->assign->addJS([
                'plugins/datatables/jquery.dataTables.min.js',
                'plugins/datatables/dataTables.bootstrap.min.js',
                'js/list.js',
            ]);
            $this->assign->addPlugin('confirm');
        }
        if (count($this->actions)) {
            // set session and retrieve later
            session([
                'actions_list' => $this->actions,
                'skip_actions_list' => $this->skip_actions,
            ]);
            $options['data'] = $options['name'] = 'actions';
            $options['title'] = 'Actions';
            $this->listBuilder->addColumn($options);
        }
        // add extra methods to listHtmlBuilder
        $this->setParametersDataTable();
        // add assign
        $this->assign->params([
            'HtmlList'=> $this->listBuilder,
            'create'=>$this->create,
            'route_name' => $this->route_name,
        ]);
    }

    protected function listQuery()
    {
        $select = [];
        foreach ($this->fields_list as $field => $options) {
            // select
            $select[] = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
        }
        if (!in_array('id', $select) && $this->autoIncrement)
            $select[] = 'id';
        $this->sql = \DB::table($this->table . ' AS a')->select($select);

        return $this->sql;
    }

    protected function loadData()
    {

        $dataTables = DataTables::of($this->listQuery());
        foreach ($this->fields_list as $field => $options) {
            if (isset($options['function'])) {
                if (method_exists($this, $options['function']))
                    $this->{$options['function']}($dataTables);
            }
        }
        // add row  actions
        if ($this->actions && count($this->actions)) {

            $this->displayRowActions($dataTables);
        }
        return $dataTables->make(true);
    }

    /**
     * display status
     * @param $dataTables
     * @return void
     */
    protected function displayStatus(&$dataTables)
    {

        $dataTables->editColumn('active', function ($data) {
            return '<a href=""><i class="fa ' . ($data->active ? 'fa-check' : 'fa-remove') . '"></a>';
        });
    }

    /**
     * display add row
     * @param $dataTables
     * @param $skip_actions
     * @return void
     */
    protected function displayRowActions(&$dataTables)
    {
        $dataTables->addColumn('actions', function ($data) {
            return View('admin.helpers.list.actions',
                [
                    'actions' => session('actions_list'),
                    'skip_actions' => session('skip_actions_list'),
                    'data' => $data,
                    'route_name' => $this->route_name,
                ]);
        });
    }

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

    // add some parameters to dataTable
    protected function setParametersDataTable()
    {
        // check for methods
        foreach ($this->validCallbacks as $functions)
            if (method_exists($this, $functions))
                $this->listBuilder->parameters([$functions => $this->{$functions}()]);

    }

    public function initComplete()
    {
        // add some method whtn
        $file = base_path('resources/views/admin/helpers/list/datatable/initcontent.js');
        $content =  app('files')->get($file);
        $replaces = [
            'csrf_token'=>csrf_token(),
        ];
      return  $content = strtr($content,$replaces);
    }
}