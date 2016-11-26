<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/25/2016
 * Time: 9:13 PM
 */

namespace Cobonto\Controllers;

class FrontController extends Controller
{
    public function __construct()
    {
        $this->assign = app('assign');
        $this->theme = config('app.theme');
        $this->assign->setEnvironment($this->theme);
        // load module
        if ($this instanceof ModuleFrontController)
            $this->loadModule();
        //run some method before routing
        //$this->beforeProcess(\Route::getCurrentRoute()->getActionName());
        $this->setProperties();
    }
    // we need some properties if not set it this method set theme
    protected function setProperties()
    {

    }
    /**
     * add media specific for controller
     * @return void
     */
    protected function setMedia()
    {

    }
    protected function view()
    {
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayHeader'),
            'route_name' => $this->route_name,
            'controller' => $this,
        ]);
        // assign general hooks
        // to override all plugins and buttons
        $this->assign->addCSS('css/AdminLTE.css');
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
        $this->loadMsgs();
        ;
        // add javascript vars to front
        $this->assign->addJSVars([
            '_token' => csrf_token(),
        ]);
        \JavaScript::put($this->assign->getJSVars());
        // analyze tpl name and render view
        return view($tpl, $this->assign->getViewData());
    }
}