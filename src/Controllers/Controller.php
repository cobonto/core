<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/26/2016
 * Time: 1:22 AM
 */

namespace Cobonto\Controllers;
use Cobonto\Classes\Assign;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    /**
     * @param string $route name of route
     */
    protected $route;
    /**
     * @param string $route_name full route name include prefix route
     */
    protected $route_name;
    /**
     * @param array $errors ;
     */
    protected $errors = [];
    /**
     * @var Assign
     */
    protected $assign;
    /**
     * @string tpl view file
     */
    protected  $tpl = false;
    /**
     * @var string theme directory
     */
    protected $theme;
    /**
     * @var string theme uri
     */
    protected  $theme_uri;
    /** set additaional properties to controller */
    abstract protected function setProperties();
    /** render view */
    abstract protected function view();
    /**  setMedia files  */
    protected function setMedia()
    {
        $this->assign->addCSS(
            ['admin/css/bootstrap.min.css',
                'admin/css/font-awesome/font-awesome.min.css']
            ,true);
        if(config('app.rtl'))
            $this->assign->addCSS(
                ['admin/css/bootstrap-rtl.css',
                ]
                ,true);
        $this->assign->addCSS(
            [    'admin/css/font-awesome/font-awesome.min.css']
            ,true);
        $this->assign->addPlugin('jquery');
        $this->assign->addPlugin('jquery-ui');
        $this->assign->addJS('admin/js/bootstrap.min.js',true);
    }
}