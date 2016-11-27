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
    protected function view()
    {
        $this->setMedia();
        $this->assign->params([
            'HOOK_HEADER' => Hook::execute('displayHeader'),
        ]);
        // add rtl file if app is rtl
        if (config('app.rtl'))
        {
            $this->assign->addCSS('rtl.css');
        }
    }
}