<?php

namespace Cobonto\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\User;
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
     * @string tpl view file
     */
    protected $tpl = false;
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
     * @param string $route name of route
     */
    protected $route;
    /**
     * @param string $route_name full route name include prefix route
     */
    protected $route_name;

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
     * @param array $errors ;
     */
    protected $errors = [];

    /**
     * @param array $warning
     */
    protected $warning = [];

    /**
     * @param array $warning
     */
    protected $info = [];

    /**
     * @param Cobonto\Classes\Assign
     */
    protected $assign;

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
     * AdminController constructor.
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->request = $request;
        $this->app = \App::getInstance();
        /** @var Assign assign */
        $this->assign = app('assign');
        // load module
        if ($this instanceof ModuleAdminController)
            $this->loadModule();
        //run some method before routing
        //$this->beforeProcess(\Route::getCurrentRoute()->getActionName());
        $this->setProperties();
        $this->permissions = Role::getRolePermissions(\Auth::user()->role_id);
        $this->route_name = 'admin.' . $this->route_name . '.';
        $this->setMedia();
    }

    // we need some properties if not set it this method set theme
    protected function setProperties()
    {
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
        // css
        $this->assign->addCSS([
            'css/bootstrap.min.css',
            'css/font-awesome/css/font-awesome.min.css',
            'css/ionicons/css/ionicons.min.css',
            'css/skins/_all-skins.min.css',
        ]);
        // js
        $this->assign->addJS([
            'plugins/jQuery/jQuery-2.2.0.min.js',
            'plugins/jQueryUI/jquery-ui.min.js',
            'js/bootstrap.min.js',
            'js/app.js',

        ]);
        #  $this->assign->addUI('ui.core');
        #  $this->assign->addUI('ui.widget');
        # $this->assign->addUI('ui.button');
        // javascript
        $this->assign->addPlugin('morris');
        $this->assign->addPlugin('pace');
    }

    /**
     * before routing is executed this method will be running
     * @param string $routeName
     * @return void
     */
    public function beforeProcess($routeName)
    {

    }

    /**
     * after routing is run this method is executed
     * @param string $routeName
     * @return void
     */
    public function afterProcess($routeName)
    {

    }

    /**
     * do something and prepare to render view
     * @return \View html
     */
    protected function view()
    {
        // after process
        $this->afterProcess(\Route::getCurrentRoute()->getActionName());
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayAdminHeader'),
            'HOOK_FOOTER' => Hook::execute('displayAdminFooter'),
            'HOOK_NAV' => Hook::execute('displayAdminNav'),
            'HOOK_SIDEBAR_TOP' => Hook::execute('displayAdminSideBarTop'),
            'HOOK_SIDEBAR' => Hook::execute('displayAdminSideBar'),
        ]);
        // assign general hooks
        // to override all plugins and buttons
        $this->assign->addCSS(
            [
                'css/AdminLTE.css',
            ]);
        $this->assign->params([
            'css' => $this->assign->getCSS(),
            'javascript_files' => $this->assign->getJS(),
            'title' => $this->title,
        ]);
        $tpl = $this->renderTplName();
        $this->loadMsgs();
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
            return 'admin.' . $this->tpl . '.main';
        elseif (count($data) == 2)
            return 'admin.' . $this->tpl;
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
        $this->fieldForm();
        $this->loadObject();
        // add some variable for view
        if ($this->tpl == false)
            $this->tpl = $this->tpl_form;
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => 0,
            'form_url' => route($this->route_name . 'store'),
            'object' => $this->model ?: null,
            'route_list' => route($this->route_name . 'index'),
        ]);
        return $this->view();
    }

    protected function fieldForm()
    {

    }
    // edit method
    protected function edit($id)
    {
        $this->fieldForm();
        $this->loadObject($id, true);
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
            'route_list' => route($this->route_name . 'index'),
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
            if(!$this->hasPermission('edit'))
                return redirect(route(config('api.admin_url').'.403'));
            return $this->update($id);
        }
        else
        {
            // check permissions
            if(!$this->hasPermission('create'))
                return redirect(route(config('api.admin_url').'.403'));
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
                    return redirect(route($this->route_name . 'create'))->withErrors($this->errors);
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
        return redirect(route($this->route_name . 'create'))->withErrors($this->errors);
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
            return redirect(route($this->route_name . 'index'))->withErrors($this->errors);
        else
            return $this->redirect($this->lang('delete_success'));
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
            return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->with('success', $msg);
        else
            return redirect(route($this->route_name . 'index'))->with('success', $msg);
    }

    /**
     * load messages for errors warning info
     */
    protected function loadMsgs()
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
       if($this->sql==false)
       {
           $select = [];
           foreach ($this->fields_list as $field => $options)
           {
               // select
               $select[] = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
           }
           $this->sql = \DB::table($this->table . ' AS a')->select($select);
       }
        return $this->sql;
    }
    public function getRoute()
    {
        return $this->route_name;
    }
    protected function checkPermissions()
    {
        if(\Auth::user()->role_id==1)
            return true;
        foreach($this->actions as $action=>$params)
        {
            if(!isset($this->permissions[$this->route.'.'.$action]))
                $this->removeAction($action);
        }

        if(!isset($this->permissions[$this->route.'.create']))
        {

            $this->create = false;
        }
    }
    protected function hasPermission($permission)
    {
        if(\Auth::user()->role_id==1)
            return true;
        return isset($this->permissions[$this->route.'.'.$permission]);
    }
}