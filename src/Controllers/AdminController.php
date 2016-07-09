<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/2/2016
 * Time: 7:40 PM
 */

namespace RmsCms\Controllers;


use App\Http\Controllers\Controller;
use Barryvdh\Reflection\DocBlock\Context;
use Module\Classes\Hook;
use RmsCms\Classes\Traits\HelperList;

abstract class AdminController extends Controller
{
    use HelperList;
    /**
     * @string tpl view file
     */
    protected $tpl;
    /**
     * @string title of page
     */
    protected $title;

    /**
     * @string table
     */
    protected $table;

    /**
     * @string model
     */
    protected $model;
    /*
     * @string className
     */
    protected $className;
    public function __construct()
    {
        // run some method before routing
      //  $this->beforeProcess(\Route::getCurrentRoute()->getActionName());
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
            'css/AdminLTE.css',
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
        // add datable if bulder is created
        if($this->listBuilder)
        {
            app('assign')->addCSS('plugins/datatables/dataTables.bootstrap.css');
            app('assign')->addJS([
                'plugins/datatables/jquery.dataTables.min.js',
                'plugins/datatables/dataTables.bootstrap.min.js'

            ]);
        }
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
        // set media
        $this->setMedia();
        // assign general hooks
         app('assign')->view([
            'HOOK_HEADER'=>Hook::execute('displayAdminHeader'),
            'HOOK_FOOTER'=>Hook::execute('displayAdminFooter'),
            'HOOK_NAV'=>Hook::execute('displayAdminNav'),
             'css'=>app('assign')->getCSS(),
             'javascript_files'=>app('assign')->getJS(),
             'javascript_vars'=>app('assign')->getJSVars(),
             'title'=>$this->title,
        ]);
        // analyze tpl name and render view
        return view($this->renderTplName(),app('assign')->getViewData());
    }

    /**
     * render tpl name and return string of full tpl name
     * @return string
     */
    protected function renderTplName()
    {
        $data = explode('.',$this->tpl);
        if(count($data)==1)
            return 'admin.'.$this->tpl.'.main';
        elseif(count($data)==2)
            return 'admin.'.$this->tpl;
        else
            return $this->tpl;
    }
}