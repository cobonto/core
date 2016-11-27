<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/25/2016
 * Time: 9:13 PM
 */

namespace Cobonto\Controllers;

use Module\Classes\Hook;

class FrontController extends Controller
{
    public function __construct()
    {
        $this->assign = app('assign');
        $this->theme = config('app.theme');
        $this->theme_uri = str_replace('.','/',$this->theme);

        $this->assign->setEnvironment($this->theme_uri);
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
    protected function view()
    {

        $this->setMedia();
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayHeader'),
            'theme_uri'=>$this->theme_uri
        ]);
        // add rtl file if app is rtl
        if (config('app.rtl'))
        {
            $this->assign->addCSS('rtl.css');
        }
        $this->assign->params([
            'css' => $this->assign->getCSS(),
            'javascript_files' => $this->assign->getJS(),
        ]);
        // add javascript vars to front
        $this->assign->addJSVars([
            '_token' => csrf_token(),
        ]);
        \JavaScript::put($this->assign->getJSVars());
    }
}