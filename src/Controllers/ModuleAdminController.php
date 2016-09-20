<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 9/7/2016
 * Time: 9:40 PM
 */

namespace Cobonto\Controllers;

use Illuminate\Http\Request;
use Module\Classes\Module;

class ModuleAdminController extends AdminController
{
    // protected module name like Cobonto.test we
    protected $moduleName;
    /** @var \Module\Classes\Module */
    protected $module;
    /** @var bool ovveride_view_files */
    protected $ovverrideViewFile = false;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    protected function setProperties()
    {
        parent::setProperties();
        $this->prefix_model = $this->app->getNamespace().'Modules\\'.$this->module->author.'\\'.$this->module->name.'\\Models\\';
    }
    protected function loadModule()
    {
        //
        $data = explode('.', $this->moduleName);
        if (count($data) != '2')
            $this->errors[] = parent::lang('module_name_invalid');
        else
        {
            $this->module = Module::getInstance($data[0], $data[1]);
            if (!is_object($this->module))
            {
                $this->errors[] = parent::lang('module_not_loaded');
            }

        }
    }

    protected function renderTplName()
    {
        if(!$this->ovverrideViewFile)
        {
            return parent::renderTplName();
        }
        else
        {
            // check module is loaded or not
            if(!is_object($this->module))
            {
                return parent::renderTplName();
            }
                $data = explode('.', $this->tpl);
                if (count($data) == 1)
                    $this->tpl = 'admin.' . $this->tpl . '.main';
                elseif (count($data) == 2)
                    $this->tpl = 'admin.' . $this->tpl;
                else
                    return 'module_resource::' . $this->module->author . '.' . $this->module->name . '.resources.' . $this->tpl;


        }

    }

    public function lang($string)
    {
       return $this->module->lang($string);
    }
}