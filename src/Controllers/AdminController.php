<?php

namespace Cobonto\Controllers;

use App\Http\Requests\Request;
use App\User;
use Cobonto\Classes\Admin;
use Cobonto\Classes\Assign;
use Cobonto\Classes\Roles\Role;
use Cobonto\Classes\Traits\HelperList;
use Cobonto\Classes\Traits\SimpleHelperList;
use Illuminate\Support\MessageBag;
use LaravelArdent\Ardent\Ardent;
use Module\Classes\Hook;
use Cobonto\Classes\Traits\HelperForm;
use Cobonto\Classes\Traits\DataTableHelperList;

abstract class AdminController extends Controller
{
    use HelperList;
    use DataTableHelperList;
    use HelperForm;
    use SimpleHelperList;
    /**
     * @string title of page
     */
    protected $title;
    /**
     * @string table
     */
    protected $table;
    /*
     * @string className
     */
    protected $className;
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var \App::getInstance()
     */
    protected $app;
    /**
     * @param  string $prefix_model preifx_namespace of model
     */
    protected $prefix_model;

    /**
     * @param string $model_name ;
     */
    protected $model_name;

    /**
     * @param Ardent $model ;
     */
    protected $model;
    /**
     * @param array $warning
     */
    protected $warning = [];

    /**
     * @param array $warning
     */
    protected $info = [];
    /**
     * @var string $translationPrefixFile
     */
    protected $translationPrefixFile = 'admin';
    /**
     * @var bool datatable list
     */
    protected $dataTableList = false;
    /**
     * @var array $permissions
     */
    protected $permissions;
    /**
     * @var Admin instance;
     */
    protected $admin;
    /**
     * AdminController constructor.
     * @param \Illuminate\Http\Request $request
     */
    public function __construct()
    {
        $this->request = app('request');
        $this->theme = 'admin';
        $this->app = \App::getInstance();
        $this->setPermissions();
        /**
         * @var Assign
         */
        $this->assign = app('assign');
        // set login environment
        $this->assign->setEnvironment('admin');
        // load module
        if ($this instanceof ModuleAdminController)
            $this->loadModule();
        //run some method before routing
        //$this->beforeProcess(\Route::getCurrentRoute()->getActionName());
        $this->setProperties();
        $this->route_name = config('app.admin_url') . '.' . $this->route_name . '.';
        $this->setMedia();
    }

    // we need some properties if not set it this method set theme
    protected function setProperties()
    {
        if(!$this->prefix_model)
        $this->prefix_model = $this->app->getNamespace();
        $controller_name = substr(class_basename($this), 0, -10);
        /**
         * @deprecated and remove next version
         */
        if (!$this->route_name)
        {
            $this->route_name = strtolower($controller_name);
        }
        $this->route = $this->route_name;
        if (!$this->title)
            $this->title = $controller_name;
        if (!$this->model_name)
            $this->model_name = $controller_name;
        if (!$this->table)
        {
            if ($this->model_name)
                $this->table = strtolower(snake_case($this->model_name)) . 's';
            else
                $this->table = snake_case($controller_name);
        }
    }

    /**
     * add media specific for controller
     * @return void
     */
    protected function setMedia()
    {
        parent::setMedia();
        // css
        $this->assign->addCSS([
            'css/ionicons/css/ionicons.min.css',
            'css/skins/_all-skins.min.css',
        ]);
        //js
        $this->assign->addJS('js/app.js');
        // plugins
        $this->assign->addPlugin('morris');
        $this->assign->addPlugin('pace');
    }

    /**
     * do something and prepare to render view
     * @return \View html
     */
    protected function view()
    {
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayAdminHeader'),
            'route_name' => $this->route_name,
            'controller' => $this,
            'admin'=>$this->admin,
        ]);
        // assign general hooks
        // to override all plugins and buttons
        $this->assign->addCSS('css/AdminLTE.css');
        $this->assign->addCSS('css/admin.css');
        // add rtl file if app is rtl
        if (config('app.rtl'))
        {
            $this->assign->addCSS('css/rtl.css');
        }

        $this->assign->params([
            'css' => $this->assign->getCSS(),
            'javascript_files' => $this->assign->getJS(),
            'title' => $this->title,
        ]);
        $tpl = $this->renderTplName();
        $this->loadMessages();
        ;
        // add javascript vars to front
        $this->assign->addJSVars([
            '_token' => csrf_token(),
        ]);
        \JavaScript::put($this->assign->getJSVars());
        // analyze tpl name and render view
        return view($tpl, $this->assign->getViewData());
    }

    /**
     * render tpl name and return string of full tpl name
     * @return string
     */
    protected function renderTplName()
    {
        $data = explode('.', $this->tpl);
        if (count($data) == 1)
            return $this->theme.'.' . $this->tpl . '.main';
        elseif (count($data) == 2)
            return $this->theme.'.' . $this->tpl;
        else
            return $this->tpl;
    }

    /**
     * load object
     * @param int|bool $id ;
     * @param bool|false $force
     * @return bool|void
     */
    protected function loadObject($id = false, $force = false)
    {
        if (is_object($this->model))
            return true;
        if (!$this->model_name)
            return false;
        else
        {
            $model = $this->prefix_model . $this->model_name;
            if (class_exists($model))
            {
                if (!$id && $force)
                    return false;
                elseif ($id)
                {
                    if ($force)
                        $this->model = $model::findOrFail($id);
                    else
                        $this->model = $model::find($id);
                }
                elseif (!$id && !$force)
                    $this->model = new $model;

            }
            return true;
        }

    }

    // index method
    protected function index()
    {
        // fill fields list
        $this->fieldList();
        // check permission for actions
        $this->checkPermissions();
        if (count($this->fields_list))
        {
            // determine datatable or list table loaded
            if ($this->dataTableList)
            {
                if ($this->request->ajax())
                    return $this->dataTableGenerateList();
                else
                    $this->dataTableGenerateList();
            }
            else
                $this->generateList();
        }
        return $this->view();
    }

    protected function create()
    {
        $this->loadObject();
        $this->fieldForm();
        // add some variable for view
        if ($this->tpl == false)
            $this->tpl = $this->tpl_form;
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => 0,
            'form_url' => $this->getRoute('store'),
            'object' => $this->model ?: null,
            'route_list' => $this->getRoute('index'),
        ]);
        return $this->view();
    }

    protected function fieldForm()
    {

    }

    // edit method
    protected function edit($id)
    {
        $this->loadObject($id, true);
        $this->fieldForm();
        // add some variable for view
        if ($this->tpl == false)
            $this->tpl = $this->tpl_form;
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => $id,
            'form_url' => route($this->route_name . 'store', ['id' => $id]),
            'object' => $this->model ?: null,
            'route_list' => $this->getRoute('index'),
        ]);
        return $this->view();
    }

    // store maybe update or delete
    protected function store()
    {
        $id = $this->request->input('id');
        if ($id && $this->loadObject($id, true))
        {
            // check permissions
            if (!$this->hasPermission('edit'))
                return redirect(adminRoute('403'));
            return $this->update($id);
        }
        else
        {
            // check permissions
            if (!$this->hasPermission('create'))
                return redirect(adminRoute('403'));
            return $this->add();
        }

    }

    // create
    protected function add()
    {
        $this->calcPost();
        $this->loadObject();
        if (is_object($this->model))
        {
            $this->beforeAdd();
            if (!count($this->errors))
            {
                if (!$this->model->save())
                {
                    $this->errors = $this->model->errors()->all();
                    $this->request->flash();
                    return redirect($this->getRoute('create'))->withErrors($this->errors);
                }
                else
                {
                    // call beforeCrate()
                    $this->afterAdd($this->model->id);
                    if (count($this->errors))
                    {
                        $this->request->flash();
                        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                    }

                    else
                        return $this->redirect($this->lang('add_success'));
                }
            }
        }
        else
            $this->errors[] = $this->lang('object_not_loaded');
        return redirect($this->getRoute('create'))->withErrors($this->errors);
    }

    //update
    protected function update($id)
    {
        $this->calcPost();
        $this->loadObject($id, true);
        if (is_object($this->model))
        {
            $this->beforeUpdate($id);
            if (!count($this->errors))
            {
                if (!$this->model->save())
                {
                    $this->errors = $this->model->errors()->all();
                    $this->request->flash();
                    return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                }
                else
                {
                    // call beforeCrate()
                    $this->afterUpdate($id);
                    if (count($this->errors))
                    {
                        $this->request->flash();
                        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                    }
                    else
                        return $this->redirect($this->lang('add_success'));
                }
            }
        }
        else
            $this->errors[] = $this->lang('object_not_loaded');
        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
    }

    /**
     * destroy object
     * @param int $id
     */
    protected function destroy($id)
    {
        if (!$this->loadObject($id, true))
            $this->errors[] = $this->lang('object_not_loaded');
        else
        {
            $this->beforeDelete($this->model, $id);
            if (!count($this->errors))
            {
                if ($this->model->delete())
                {
                    $this->afterDelete($this->model, $id);

                }
                else
                {
                    $this->errors[] = $this->lang('object_not_deleted');
                }
            }

        }
        if (count($this->errors))
            return redirect($this->getRoute('index'))->withErrors($this->errors);
        else
            return $this->redirect($this->lang('delete_success'));
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

    /**
     * getHighestPosition
     * @return int
     */
    protected function getHighestPosition()
    {
         return $this->model->getHighestPosition()+1;
    }
    protected function beforeDelete($object, $id)
    {

    }

    protected function afterDelete($object, $old_delete)
    {

    }

    protected function beforeAdd()
    {

    }

    protected function afterAdd($id)
    {

    }

    protected function beforeUpdate($id)
    {

    }

    protected function afterUpdate($id)
    {

    }

    protected function redirect($msg)
    {
        if ($this->request->input('saveAndStay'))
            return redirect($this->getRoute('edit', ['id' => $this->model->id]))->with('success', $msg);
        else
            return redirect($this->getRoute('index'))->with('success', $msg);
    }

    /**
     * load messages for errors warning info
     */
    protected function loadMessages()
    {
        if (count($this->errors))
            \Session::flash('errors', new MessageBag($this->errors));
        if (count($this->warning))
            \Session::flash('warning', $this->warning);
        if (count($this->info))
            \Session::flash('info', $this->info);
    }

    /**
     * get translated file
     * @param $string
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function lang($string)
    {
        return trans($this->translationPrefixFile . '.' . $string);
    }

    /**
     * get translated file
     * @param $string
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function l($string)
    {
        return $this->lang($string);
    }

    /**
     * create sql instance
     * @return \DB
     */
    protected function listQuery()
    {
        if ($this->sql == false)
        {
            $select = [];
            foreach ($this->fields_list as $field => $options)
            {
                // select
                $select[] = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
            }
            /**
             * @var \DB
             */
            $this->sql = \DB::table($this->table . ' AS a')->select($select);
        }
        return $this->sql;
    }

    /**
     * get route from controller
     * @param string $route_name
     * @param bool $route
     * @param array $params
     * @return string
     * @deprecated
     */
    public function getRoute($route_name,$params=[],$route=true)
    {
        if ($route)
            return route($this->route_name . $route_name,$params);
        else
            return $this->route_name.$route_name;
    }
    /**
     * get route from controller
     * @param string $route_name
     * @param bool $route
     * @param array $params
     * @return string
     */
    public function route($route_name,$params=[],$route=true)
    {
        if ($route)
            return route($this->route_name . $route_name,$params);
        else
            return $this->route_name.$route_name;
    }
    protected function checkPermissions()
    {
        if ($this->admin->role_id == 1)
            return true;
        foreach ($this->actions as $action => $params)
        {
            if (!isset($this->permissions[$this->route . '.' . $action]))
                $this->removeAction($action);
        }

        if (!isset($this->permissions[$this->route . '.create']))
        {

            $this->create = false;
        }
    }

    protected function hasPermission($permission)
    {
        if ($this->admin->role_id == 1)
            return true;
        return isset($this->permissions[$this->route . '.' . $permission]);
    }

    protected function setPermissions()
    {
        // for debug and artisan
        if (\Auth::guard('admin')->check())
        {
            /** @var Admin admin */
            $this->admin = \Auth::guard('admin')->user();
            $this->admin->setLocale();
            $this->permissions = Role::getRolePermissions($this->admin->role_id);
        }
    }
}