<?php

namespace Cobonto\Controllers\Admin;
use Cobonto\Controllers\AdminController;

use Cobonto\Requests\ZipUploadRequest;
use Illuminate\Http\Request;
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

    public function setProperties()
    {
        $this->title = $this->lang('modules');
        parent::setProperties();
        $this->fillData();
    }

    protected function index()
    {
        // get modules name from disk
        $modules = Module::getModules();
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
            // check for core module or not
            $module = Module::getInstance($this->author, $this->name);
            if($module->core)
            {
                $this->errors[] = $this->lang('can_not_delete_core_module');
            }
            else
            $this->app->files->deleteDirectory($this->modulePath);
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
        $this->assign->addJSVars(['alert_msg'=>$this->lang('sure_to_delete')]);
        parent::setMedia();
        $this->assign->addCSS('css/module.css');
        $this->assign->addJS('js/module.js');
        $this->assign->addPlugin('confirm');
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
        \Cache::forget('moduleAdminRoutes');
        \Cache::forget('moduleFrontRoutes');
        app('cache')->flush();
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
            if (Module::checkOnDisk($this->author, $this->name))
                $this->inDisk = 1;
        }
        $this->modulePath = app_path('/Modules/' . $this->author . '/' . $this->name);
    }
}
