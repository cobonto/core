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

    }
}