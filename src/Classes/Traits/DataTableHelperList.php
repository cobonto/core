<?php

namespace Cobonto\Classes\Traits;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;

trait DataTableHelperList
{
    // callback validate for dataTable
    protected $validCallbacks = [
        'createdRow',
        'rawCallback',
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
    /** @var Builder */
    protected $listBuilder = false;
    protected function dataTableGenerateList()
    {
        if($this->tpl==false)
            $this->tpl = 'admin.helpers.list.datatable.main';
      // if ajax list
        if ($this->request->ajax())
            return $this->dataTableLoadData();
        // create builder
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
        $this->dataTableSetParameters();
        // add assign
        $this->assign->params([
            'HtmlList'=> $this->listBuilder,
            'create'=>$this->create,
            'route_name' => $this->route_name,
        ]);
    }
    protected function dataTableLoadData()
    {
        $dataTables = DataTables::of($this->listQuery());
        foreach ($this->fields_list as $field => $options) {
            if (isset($options['function'])) {
                if (method_exists($this,'dataTable'.ucfirst($options['function'])))
                    $this->{'dataTable'.$options['function']}($dataTables);
            }
        }
        // add row  actions
        if ($this->actions && count($this->actions)) {

            $this->dataTableDisplayRowActions($dataTables);
        }
        return $dataTables->make(true);
    }

    /**
     * display status
     * @param $dataTables
     * @return void
     */
    protected function dataTableDisplayStatus(&$dataTables)
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
    protected function dataTableDisplayRowActions(&$dataTables)
    {
        $dataTables->addColumn('actions', function ($data) {
            return View('admin.helpers.list.datatable.actions',
                [
                    'actions' => session('actions_list'),
                    'skip_actions' => session('skip_actions_list'),
                    'data' => $data,
                    'route_name' => $this->route_name,
                ]);
        });
    }

    // add some parameters to dataTable
    protected function dataTableSetParameters()
    {
        // check for methods
        foreach ($this->validCallbacks as $functions)
            if (method_exists($this, 'dataTable'.ucfirst($functions)))
                $this->listBuilder->parameters([$functions => $this->{'dataTable'.ucfirst($functions)}()]);

    }

    public function dataTableInitComplete()
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