<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/2/2016
 * Time: 7:40 PM
 */

namespace RmsCms\Controllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use LaravelArdent\Ardent\Ardent;
use Module\Classes\Hook;
use RmsCms\Classes\Traits\HelperForm;
use RmsCms\Classes\Traits\HelperList;

abstract class AdminController extends Controller
{
    use HelperList;
    use HelperForm;
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
     * @var \App $request
     */
    protected $app;
    /**
     * @param string $route_name
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

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->app = \App::getInstance();
        $this->setProperties();
        $this->route_name = 'admin.' . $this->route_name . '.';
        //run some method before routing
        //$this->beforeProcess(\Route::getCurrentRoute()->getActionName());
        $this->setMedia();
    }

    // we need some properties if not set it this method set theme
    protected function setProperties()
    {
        $this->prefix_model = $this->app->getNamespace();
        $controller_name = substr(class_basename($this), 0, -10);
        if (!$this->table)
            $this->table = snake_case($controller_name);
        if (!$this->route_name)
            $this->route_name = strtolower($controller_name);
        if (!$this->title)
            $this->title = $controller_name;
        if (!$this->model_name)
            $this->model_name = $controller_name;


    }

    /**
     * add media specific for controller
     * @return void
     */
    protected function setMedia()
    {
        // css
        app('assign')->addCSS([
            'css/bootstrap.min.css',
            'css/font-awesome/css/font-awesome.min.css',
            'css/ionicons/css/ionicons.min.css',
            'css/skins/_all-skins.min.css',
        ]);
        // js
        app('assign')->addJS([
            'plugins/jQuery/jQuery-2.2.0.min.js',
            'plugins/jQueryUI/jquery-ui.min.js',
            'js/bootstrap.min.js',
            'js/app.js',

        ]);
        // javascript
        app('assign')->addPlugin('morris');
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
        app('assign')->view([
            'HOOK_HEADER' => Hook::execute('displayAdminHeader'),
            'HOOK_FOOTER' => Hook::execute('displayAdminFooter'),
            'HOOK_NAV' => Hook::execute('displayAdminNav')
        ]);
        // assign general hooks
        // to override all plugins and buttons
        app('assign')->addCSS(
            [
                'css/AdminLTE.css',
            ]);
        app('assign')->view([
            'css' => app('assign')->getCSS(),
            'javascript_files' => app('assign')->getJS(),
            'javascript_vars' => app('assign')->getJSVars(),
            'title' => $this->title,
        ]);
        $this->loadMsgs();
        // analyze tpl name and render view
        return view($this->renderTplName(), app('assign')->getViewData());
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
        else {
            $model = $this->prefix_model . $this->model_name;
            if (class_exists($model)) {
                if (!$id && $force)
                    return false;
                elseif ($id) {
                    if ($force)
                        $this->model = $model::findOrFail($id);
                    else
                        $this->model = $model::find($id);
                } elseif (!$id && !$force)
                    $this->model = new $model;

            }
            return true;
        }

    }

    // index method
    protected function index()
    {
        if (count($this->fields_list)) {
            // render view file
            if ($this->tpl == false)
                $this->tpl = $this->tpl_list;
            // after set fields_list
            if ($this->request->ajax())
                return $this->loadData();
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
        $this->generateForm();
        app('assign')->view([
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
        $this->generateForm();
        app('assign')->view([
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
            return $this->update($id);
        else
            return $this->add();
    }

    // create
    protected function add()
    {
        $this->calcPost();
        $this->loadObject();
        if (is_object($this->model)) {
            $this->beforeAdd();
            if (!count($this->errors)) {
                if (!$this->model->save()) {
                    $this->errors = $this->model->errors()->all();
                    return redirect(route($this->route_name . 'create'))->withErrors($this->errors);
                } else {
                    // call beforeCrate()
                    $this->afterAdd($this->model->id);
                    if (count($this->errors))
                        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                    else
                        return $this->redirect('Successfully Added');
                }
            }
        }
        else
            $this->errors[] = 'Problem in load object';
        return redirect(route($this->route_name . 'create'))->withErrors($this->errors);
    }

    //update
    protected function update($id)
    {
        $this->calcPost();
        $this->loadObject($id, true);
        if (is_object($this->model)) {
            $this->beforeUpdate($id);
            if (!count($this->errors)) {
                if (!$this->model->save()) {
                    $this->errors = $this->model->errors()->all();
                    return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                } else {
                    // call beforeCrate()
                    $this->afterUpdate($id);
                    if (count($this->errors))
                        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
                   else
                       return $this->redirect('Successfully updated');
                    }
                }
            }
        else
            $this->errors[] = 'Problem in load object';
        return redirect(route($this->route_name . 'edit', ['id' => $this->model->id]))->withErrors($this->errors);
    }
    /**
     * destroy object
     * @param int $id
     */
    protected function destroy($id)
    {
        if(!$this->loadObject($id,true))
            $this->errors[] = 'Problem in load object';
        else
        {
            $this->beforeDelete($this->model,$id);
            if(!count($this->errors))
            {
                if($this->model->delete())
                {
                    $this->afterDelete($this->model,$id);

                }
                else
                {
                    $this->errors[] = 'Problem in delete object';
                }
            }

        }
        if(count($this->errors))
            return redirect(route($this->route_name . 'index'))->withErrors($this->errors);
        else
            return $this->redirect('SuccessFully Deleted');
    }

    protected function beforeDelete($object,$id)
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
        if (isset($_POST['saveAndStay']))
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
            \Session::flash('errors', $this->errors);
        if (count($this->warning))
            \Session::flash('warning', $this->warning);
        if (count($this->warning))
            \Session::flash('info', $this->info);
    }

}