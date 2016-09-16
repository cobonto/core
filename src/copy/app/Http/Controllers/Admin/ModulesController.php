<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ZipUploadRequest;
use Cobonto\Controllers\AdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Module\Classes\Module;

class ModulesController extends AdminController
{
    //
    protected $tpl = 'module';
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
        $this->title = $this->lang('modules');
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
        $this->assign->params(
            [
                'modules' => $modules,
                'upload_url' => route($this->route_name . 'uploadModule'),
                'clear_cache_url' => route($this->route_name . 'rebuildList'),
            ]
        );
        return parent::index();
    }

    protected function install()
    {
        if (!$this->inDisk)
            $this->errors[] = $this->lang('module_not_exists');
        else
        {
            // check module is installed or not
            if (Module::isInstalled($this->author, $this->name))
                $this->errors[] = $this->lang('module_is_installed');
            else
            {

                $module = Module::getInstance($this->author, $this->name);
                if (is_object($module))
                {
                    if (!$module->install())
                        $this->errors = array_merge($this->errors, $module->errors);

                }
                else
                    $this->errors[] = $this->lang('module_is_not_loaded');
            }
        }
        return $this->redirect($this->lang('module_installed_successfully'));
    }

    protected function uninstall()
    {
        if (!$this->inDisk)
            $this->errors[] = $this->lang('module_not_exists');
        else
        {
            // check module is installed or not
            if (!Module::isInstalled($this->author, $this->name))
                $this->errors[] = $this->lang('module_already_is_uninstalled');
            else
            {
                $module = Module::getInstance($this->author, $this->name);
                if (is_object($module))
                {
                    if (!$module->uninstall())
                        $this->errors = array_merge($this->errors, $module->errors);
                }
                else
                    $this->errors[] = $this->lang('module_is_not_loaded');
            }
        }
        return $this->redirect($this->lang('uninstalled_successfully'));
    }

    protected function enable()
    {
        if (!$this->inDisk)
            $this->errors[] = $this->lang('module_not_exists');
        else
        {
            // check module is installed or not
            if (!Module::isInstalled($this->author, $this->name))
                $this->errors[] = $this->lang('module_not_installed');
            else
            {
                $module = Module::getInstance($this->author, $this->name);
                if (is_object($module))
                {
                    \DB::table('modules')->where('name', $this->name)->where('author', $this->author)->update(['active' => 1]);
                }
                else
                    $this->errors[] = $this->lang('module_is_not_loaded');
            }
        }
        return $this->redirect($this->lang('module_enabled'));
    }

    protected function disable()
    {
        if (!$this->inDisk)
            $this->errors[] = $this->lang('module_not_exists');
        else
        {
            // check module is installed or not
            if (!Module::isInstalled($this->author, $this->name))
                $this->errors[] = $this->lang('module_not_installed');
            else
            {
                $module = Module::getInstance($this->author, $this->name);
                if (is_object($module))
                {
                    \DB::table('modules')->where('name', $this->name)->where('author', $this->author)->update(['active' => 0]);
                }
                else
                    $this->errors[] = $this->lang('module_is_not_loaded');
            }
        }
        return $this->redirect($this->lang('module_disabled'));
    }

    protected function configuration()
    {
        $isInstalled = Module::isInstalled($this->author, $this->name);
        if ($isInstalled)
        {
            $module = Module::getInstance($this->author, $this->name);
            if (is_object($module))
            {
                if (method_exists($module, 'configuration'))
                {
                    $this->tpl = 'module.configure';
                    $this->title = 'Configure ' . $this->name;
                    $html = $module->configuration();
                    $this->assign->params(['html' => $html]);
                    return parent::view();
                }
                else
                    $this->errors[] = $this->lang('no_config_method');

            }
        }
        else
        {
            $this->errors[] = $this->lang('module_not_installed');
            return $this->redirect(false);
        }

    }

    public function uploadModule(ZipUploadRequest $request)
    {
        $file = $request->file('module');
        // check  file is valid
        if ($file->isValid() && $file->getMimeType() == 'application/zip')
        {
            // try to extract
            $Zip = new \ZipArchive();
            if ($Zip->open($file->getRealPath()))
            {
                try
                {
                    $Zip->extractTo(app_path('Modules'));
                } catch (\Exception $e)
                {
                    $this->errors[] = $this->lang('error_in_extract');
                }
            }
            $Zip->close();
        }
        else
            $this->errors[] = $this->lang('file_invalid');
        return $this->redirect($this->lang('uploaded_success'));


    }

    protected function save(Request $request)
    {
        $isInstalled = Module::isInstalled($this->author, $this->name);
        if ($isInstalled)
        {
            $module = Module::getInstance($this->author, $this->name);
            if (is_object($module))
            {
                if (method_exists($module, 'saveConfigure'))
                {
                    if (!$result = $module->saveConfigure($request))
                        return redirect(route($this->route_name . 'configure', ['author' => strtolower(camel_case($this->author)), 'name' => strtolower(camel_case($this->name))]))->withErrors($module->errors);
                    else
                        return redirect(route($this->route_name . 'configure', ['author' => strtolower(camel_case($this->author)), 'name' => strtolower(camel_case($this->name))]))->with('success', $result);
                }
                else
                    $this->errors[] = $this->lang('saveconfigure_not_exists');

            }
            else
            {
                $this->errors[] =$this->lang('module_not_exists');
            }
            return redirect(route($this->route_name . 'configure', ['author' => strtolower(camel_case($this->author)), 'name' => strtolower(camel_case($this->name))]))->withErrors($this->errors);

        }
        else
        {
            $this->errors[] = $this->lang('module_not_installed');
            return $this->redirect(false);
        }
    }

    public function destroy($id)
    {
        // fill data from post
        $this->fillData(true);
        if ($this->inDisk)
        {
            $this->app->files->deleteDirectory(app_path('Modules/' . $this->author . '/' . $this->name));
        }
        else
            $this->errors[] = $this->lang('module_not_exists');
        return $this->redirect($this->lang('delete_success'));
    }

    public function reBuildList()
    {
        $this->clearCache();
        return $this->redirect($this->lang('refresh_successfully'));
    }

    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addCSS('css/module.css');
        $this->assign->addJS('js/module.js');
    }

    protected function redirect($msg)
    {
        $this->clearCache();
        if (count($this->errors))
            return redirect(route($this->route_name . 'index'))->withErrors($this->errors);
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

    protected function fillData($post = false)
    {
        if (!$post)
        {
            if(is_object($this->request->route()))
            {
                $this->name = ucfirst(snake_case($this->request->route()->getParameter('name')));
                $this->author = ucfirst(snake_case($this->request->route()->getParameter('author')));
            }

        }
        else
        {
            $this->name = ucfirst(snake_case($this->request->input('name')));
            $this->author = ucfirst(snake_case($this->request->input('author')));
        }
        // check on disk
        $this->inDisk = 0;
        if ($this->name && $this->author)
        {
            if (\Module\Classes\Module::checkOnDisk($this->author, $this->name))
                $this->inDisk = 1;
        }
        $this->modulePath = app_path('/Modules/' . $this->author . '/' . $this->name);
    }

    protected function checkModulesInDb($modules)
    {
        foreach ($modules as $author => &$module)
        {
            foreach ($module as &$subModule)
            {
                if ($data = Module::isInstalled($author, $subModule['name']))
                {
                    $data = \DB::table('modules')->where('name', $subModule['name'])->where('author', $author)->first();
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
}
