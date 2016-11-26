<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/26/2016
 * Time: 12:42 AM
 */

namespace Cobonto\Controllers;


use Module\Classes\Module;

class ModuleFrontController extends FrontController
{
    // protected module name like Cobonto.test we
    protected $moduleName;
    /** @var \Module\Classes\Module */
    protected $module;
    /** @var bool ovveride_view_files */
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
    /**
     * get translated file
     * @param $string
     * @return string
     */
    public function lang($string)
    {
        return $this->module->lang($string);
    }

    /**
     * get translated file
     * @param $string
     * @return string
     */
    public function l($string)
    {
        return $this->lang($string);
    }
    protected function renderTplName()
    {
        return 'module_resource::' . $this->module->author . '.' . $this->module->name . '.resources.' . $this->tpl;
    }
}