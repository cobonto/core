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

abstract class AdminController extends Controller
{
    use HelperList;
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
        if ($this instanceof ModuleAdminController && method_exists($this,'loadModule'))
            $this->loadModule();
        //run some method before routing
        //$this->beforeProcess(\Route::getCurrentRoute()->getActionName());
        $this->setProperties();
        $this->setMedia();
    }

    // we need some properties
    // if not set it in controller
    // this method set them
    protected function setProperties()
    {
        if(!$this->prefix_model)
            $this->prefix_model = $this->app->getNamespace();
        $controller_name = substr(class_basename($this), 0, -10);
        /**
         * @deprecated and remove next version
         */
        if (!$this->route)
        {
            $this->route = strtolower($controller_name);
        }
        if (!$this->title)
            $this->title = $controller_name;
        if (!$this->model_name)
            // most controllers have s in end of them so we remove it but for Some controller
            // like Address or... you should set the model
            $this->model_name = rtrim($controller_name,'s');
        if (!$this->table)
        {
            if ($this->model_name)
                $this->table = strtolower(snake_case($this->model_name));
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
        $this->assign->addJS('js/global.js');
    }

    /**
     * prepare to render view
     * @return \View html
     */
    protected function view()
    {
        // add importan variable to view
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayAdminHeader'),
            'route_name' => config('app.admin_url').'.'.$this->route.'.',
            'controller' => $this,
            'admin'=>$this->admin,
        ]);
        // assign general hooks
        // to override all plugins and buttons
        #$this->assign->addCSS('css/AdminLTE.css');
        $this->assign->addCSS('css/admin.css');
        // add rtl file if app is rtl
        if (config('app.rtl'))
        {
            $this->assign->addCSS('css/rtl.css');
        }
        $this->assign->params([
            'css' => array_unique($this->assign->getCSS()),
            'javascript_files' => array_unique($this->assign->getJS()),
            'title' => $this->title,
        ]);
        $tpl = $this->renderTplName();
        $this->loadMessages();
        ;
        // add important javascript vars in view
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
        return $this->tpl;
    }

    /**
     * load Instance of model for this controller
     * this method is helper and usefull in admin controllers without check what model need to be loaded for this controller
     * because you already determine model_name it in top of class
     * @param int|bool $id ;
     * @param bool|false $force if this property is true and model can't be loaded you hwave throw exception
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

    // index method in most admin controller this method show list of data
    protected function index()
    {
        // fill fields list
        $this->fieldList();
        // check permission for actions
        $this->checkPermissions();
        if (count($this->fields_list))
        {
            $this->generateList();
        }
        return $this->view();
    }

    protected function create()
    {
        $this->loadObject();
        $this->fieldForm();
        $this->loadTplForm();
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => 0,
            'form_url' => $this->route('store'),
            'object' => $this->model ?: null,
            'route_list' => $this->route('index'),
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
        $this->loadTplForm();
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => $id,
            'form_url' => route($this->route . 'store', ['id' => $id]),
            'object' => $this->model ?: null,
            'route_list' => $this->route('index'),
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
        $this->processPostValues();
        $this->loadObject();
        if (is_object($this->model))
        {
            // you can change data or do something else before data save to database
            $this->beforeAdd();
            if (!count($this->errors))
            {
                // according Ardent if this property become true model load all data from  post
                // and sometimes you wish to add data by yourself in model no automatic
                // so you set this property false but here you have to set it true again
                // we add this lines its mess but your code become cleaner

                if($this->model->forceEntityHydrationFromInput==false)
                    $this->model->forceEntityHydrationFromInput=true;
                if (!$this->model->save())
                {
                    $this->errors = $this->model->errors()->all();
                    $this->request->flash();
                    return redirect($this->route('create'))->withErrors($this->errors);
                }
                else
                {
                    // call after data crated you do something in controller like save image by given id ()
                    $this->afterAdd($this->model->id);
                    if (count($this->errors))
                    {
                        $this->request->flash();
                        return redirect($this->route('edit', ['id' => $this->model->id]))->withErrors($this->errors);
                    }

                    else
                        return $this->redirect($this->lang('add_success'));
                }
            }
        }
        else
            $this->errors[] = $this->lang('object_not_loaded');
        return redirect($this->route('create'))->withErrors($this->errors);
    }

    //update
    protected function update($id)
    {
        $this->processPostValues();
        $this->loadObject($id, true);
        if (is_object($this->model))
        {
            $this->beforeUpdate($id);
            // according Ardent if this property become true model load all data from  post
            // and sometimes you wish to add data by yourself in model no automatic
            // so you set this property false but here you have to set it true again
            // we add this lines its mess but your code become cleaner

            if($this->model->forceEntityHydrationFromInput==false)
                $this->model->forceEntityHydrationFromInput=true;

            if (!count($this->errors))
            {
                if (!$this->model->save())
                {
                    $this->request->flash();
                    return redirect($this->route('edit', ['id' => $this->model->id]))->withErrors($this->model->errors()->all(););
                }
                else
                {
                    // call after object updated()
                    $this->afterUpdate($id);
                    if (count($this->errors))
                    {
                        $this->request->flash();
                        return redirect($this->route('edit', ['id' => $this->model->id]))->withErrors($this->errors);
                    }
                    else
                        return $this->redirect($this->lang('add_success'));
                }
            }
        }
        else
            $this->errors[] = $this->lang('object_not_loaded');
        return redirect($this->route('edit', ['id' => $this->model->id]))->withErrors($this->errors);
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
            return redirect($this->route('index'))->withErrors($this->errors);
        else
            return $this->redirect($this->lang('delete_success'));
    }
    /**
     * getHighestPosition
     * @return int
     */
    protected function getHighestPosition()
    {
        return $this->model->getHighestPosition()+1;
    }

    /*********************************************************************************
     |
     |
     | Helper methods this methods works in controller and help you manage your controller's model after and before actions
     | like beforeAdd beforUpdate beforeDelete and etc
     | this methods is like model events but its better to use them where nearby model are acting  :)
     |
     |
     |
     */
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

    /**
     * In each form we have 2 button
     * one save and go to list page
     * second save and stay in that page
     * so we created it this method for that reason
     * Hope enjoy it
     * @param string $msg
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirect($msg)
    {
        if ($this->request->input('saveAndStay'))
            return redirect($this->route('edit', ['id' => $this->model->id]))->with('success', $msg);
        else
            return redirect($this->route('index'))->with('success', $msg);
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
     * get translated string
     * @param $string
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function lang($string)
    {
        return trans($this->translationPrefixFile . '.' . $string);
    }

    /**
     * get translated strin
     * alias of lang and use it in modules
     * @param $string
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    public function l($string)
    {
        return $this->lang($string);
    }

    /**
     * This method process fieldsList and create queryData
     * but not fired
     * so you can call at nad after that add custom queries
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
                if(!isset($options['sql_method']))
                    $select[] = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
                // sql methods or custom  like concat and....
                else
                    $select[]= \DB::raw(isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field);
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
     * @param bool $return_link
     * @param array $params
     * @return string
     */
    public function route($route_name,$params=[],$return_link=true)
    {
        if ($return_link)
            return route(config('app.admin_url').'.'.$this->route . $route_name,$params);
        else
            return config('app.admin_url').'.'.$this->route.$route_name;
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
        $this->middleware(function ($request, $next) {
            /** @var Admin admin */
            $this->admin = \Auth::guard('admin')->user();
            $this->admin->setLocale();
            $this->permissions = Role::getRolePermissions($this->admin->role_id);
            return $next($request);
        });
    }
}