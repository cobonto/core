<?php

namespace App\Http\Controllers\Admin;

use Cobonto\Controllers\AdminController;

use Illuminate\Http\Request;
use Module\Classes\Module;

class ModulesController extends AdminController
{
    //
    protected $tpl = 'module';
    protected $title = 'Modules';
    /** @var  string module name */
    protected $name;
    /** @var  string author name */
    protected $author;
    /** @var bool inDisk */
    protected $inDisk;
    // modules module path
    protected $modulePath;
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->fillData();
    }
    protected function index()
    {
        // get modules name from disk
        $modules = \Cache::remember('modules', 604800, function ()
        {
            $diskModules = Module::getModulesFromDisk();
            if ($diskModules)
                return $this->checkModulesInDb($diskModules);
            else
                return [];
        });
       # $this->clearCache();
        $this->assign->params(
            [
                'modules' => $modules,
            ]
        );
        return parent::index();
    }

    protected function install()
    {
        if(!$this->inDisk)
            $this->errors[] = 'Module is not exists in Host';
        else
        {
            // check module is installed or not
            if(Module::isInstalled($this->author,$this->name))
                $this->errors[] = 'Module is already installed';
            else
            {

                $module = Module::getInstance($this->author,$this->name);
                if(is_object($module))
                {
                    if(!$module->install())
                        $this->errors = array_merge($this->errors,$module->errors);

                }
                else
                    $this->errors[] = 'Module is not loaded successfully';
            }
        }
        return $this->redirect('Installed successfully');
    }

    protected function uninstall()
    {
        if(!$this->inDisk)
            $this->errors[] = 'Module is not exists in Host';
        else
        {
            // check module is installed or not
            if(!Module::isInstalled($this->author,$this->name))
                $this->errors[] = 'Module is already uninstalled';
            else
            {
                $module = Module::getInstance($this->author,$this->name);
                if(is_object($module))
                {
                    if(!$module->uninstall())
                        $this->errors = array_merge($this->errors,$module->errors);
                }
                else
                    $this->errors[] = 'Module is not loaded successfully';
            }
        }
        return $this->redirect('Uninstalled successfully');
    }

    protected function enable()
    {
        if(!$this->inDisk)
            $this->errors[] = 'Module is not exists in Host';
        else
        {
            // check module is installed or not
            if(!Module::isInstalled($this->author,$this->name))
                $this->errors[] = 'Module is not installed';
            else
            {
                $module = Module::getInstance($this->author,$this->name);
                if(is_object($module))
                {
                    \DB::table('modules')->where('name',$this->name)->where('author',$this->author)->update(['active'=>1]);
                }
                else
                    $this->errors[] = 'Module is not loaded successfully';
            }
        }
        return $this->redirect('Module is enabled');
    }

    protected function disable()
    {
        if(!$this->inDisk)
            $this->errors[] = 'Module is not exists in Host';
        else
        {
            // check module is installed or not
            if(!Module::isInstalled($this->author,$this->name))
                $this->errors[] = 'Module is not installed';
            else
            {
                $module = Module::getInstance($this->author,$this->name);
                if(is_object($module))
                {
                   \DB::table('modules')->where('name',$this->name)->where('author',$this->author)->update(['active'=>0]);
                }
                else
                    $this->errors[] = 'Module is not loaded successfully';
            }
        }
        return $this->redirect('Module is disabled');
    }

    protected function configuration()
    {
        $isInstalled = Module::isInstalled($this->author,$this->name);
        if($isInstalled)
        {
            $module = Module::getInstance($this->author,$this->name);
            if(is_object($module))
            {
                if(method_exists($module,'configuration'))
                {
                    $this->tpl = 'module.configure';
                    $this->title = 'Configure '.$this->name;
                    $html = $module->configuration();
                    $this->assign->params(['html'=>$html]);
                    return parent::view();
                }
                else
                    $this->errors[] = 'Configuration method is not exists in module';

            }
        }
        else
        {
            $this->errors[] = 'Module is not installed';
            return $this->redirect(false);
        }

    }
    protected function save(Request $request)
    {
        $isInstalled = Module::isInstalled($this->author,$this->name);
        if($isInstalled)
        {
            $module = Module::getInstance($this->author,$this->name);
            if(is_object($module))
            {
                if(method_exists($module,'saveConfigure'))
                {
                   if(!$result =$module->saveConfigure($request))
                        return redirect(route($this->route_name.'configure',['author'=>strtolower(camel_case($this->author)),'name'=>strtolower(camel_case($this->name))]))->withErrors($module->errors);
                    else
                        return redirect(route($this->route_name.'configure',['author'=>strtolower(camel_case($this->author)),'name'=>strtolower(camel_case($this->name))]))->with('success',$result);
                }
                else
                    $this->errors[] = 'saveConfigure method is not exists in module';

            }
            else
            {
                $this->errors[] = 'Module is not found';
            }
            return redirect(route($this->route_name.'configure',['author'=>strtolower(camel_case($this->author)),'name'=>strtolower(camel_case($this->name))]))->withErrors($this->errors);

        }
        else
        {
            $this->errors[] = 'Module is not installed';
            return $this->redirect(false);
        }
    }
    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addCSS('css/module.css');
        $this->assign->addJS('js/module.js');
    }

    protected function checkModulesInDb($modules)
    {
        foreach ($modules as $author => &$module)
        {
            foreach ($module as &$subModule)
            {
                if ($data = Module::isInstalled($author, $subModule['name']))
                {
                    $data = \DB::table('modules')->where('name',$subModule['name'])->where('author',$author)->first();
                    $subModule['installed'] = 1;
                    $subModule['active'] = $data->active;
                    $moduleClass = Module::getInstance($author, $subModule['name']);
                    if (is_object($moduleClass))
                    {
                        if (method_exists($moduleClass, 'configuration'))
                            $subModule['configurable'] = 1;
                    }

                }
            }
        }
        return $modules;
    }
    protected function redirect($msg)
    {
        $this->clearCache();
        if(count($this->errors))
            return redirect(route($this->route_name.'index'))->withErrors($this->errors);
        else
        {
            $this->loadMsgs();

            return parent::redirect($msg);
        }

    }
    protected function clearCache()
    {
        \Cache::forget('modules');
    }
    protected function fillData()
    {
        $this->name = ucfirst(snake_case($this->request->route()->getParameter('name')));
        $this->author = ucfirst(snake_case($this->request->route()->getParameter('author')));
        // check on disk
        $this->inDisk = 0;
        if ($this->name && $this->author)
        {
            if (\Module\Classes\Module::checkOnDisk($this->author,$this->name))
                $this->inDisk = 1;
        }
        $this->modulePath = app_path('/Modules/'.$this->author.'/'.$this->name);
    }
    /**
     * migrate module files
     */
    public function migrateModule()
    {
        $files = app('files')->allFiles($this->modulePath.'/db/');
        dd($files);
    }
}
