<?php

namespace Cobonto\Controllers\Admin;

use App\User;
use Cobonto\Classes\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Cobonto\Controllers\AdminController;
use Module\Classes\Hook;
use Module\Classes\Module;


class ModulePositionsController extends AdminController
{
    protected function setProperties()
    {
        $this->route_name = 'positions';
        parent::setProperties();
        $this->title = $this->lang('module_positions_title');

    }

    protected function index()
    {
        $this->tpl = 'position';
        $hooks = Hook::getHooks();
        if ($hooks && count($hooks))
        {
            // get modules by position for each hook that we have
            foreach ($hooks as $hook)
            {
                $hook->modules = Hook::getModulesByName($hook->name);
            }
        }
        $this->assign->params([
            'hooks' => $hooks,
            'set_hook_url'=>$this->getRoute('set'),
        ]);
        // add some javascript vars
        $this->assign->addJSVars([
            'ajax_position_url' => $this->getRoute('update'),
            'ajax_position_unregister'=>$this->getRoute('unregister'),
        ]);
        $this->assign->addPlugin('tablednd');
        $this->assign->addJS('js/position.js');
        $this->assign->addCSS('css/position.css');

        return parent::index();
    }

    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addPlugin('growl');

    }

    public function updatePositions($positions=[])
    {
        $modules = $this->request->input('module');
        if (count($modules) && $modules)
        {
            $id_hook = explode('*', $modules[0])[1];
                // check hook is valid?
                $hook = Hook::find($id_hook);
                if (is_object($hook))
                {
                    // create update query
                    $position = 0;
                    foreach ($modules as $module)
                    {
                        $data = explode('*', $module);
                        \DB::table('hooks_modules')->
                        where('id_hook', '=', $id_hook)->
                        where('id_module', '=', $data[0])->
                            update(['position'=>$position]);
                        $position++;
                    }
                    app('cache')->flush();
                    $data = [
                        'status'=>'success',
                        'msg'=>$this->l('update_success'),
                    ];
                }
                else
                {
                    $data = [
                        'status'=>'error',
                        'msg'=>$this->l('invalid_data'),
                    ];
                }

        }
        else
        {
            $data = [
                'status'=>'error',
                'msg'=>$this->l('no_data_for_update'),
            ];
        }
        return response()->json($data);
    }
    protected function unRegister(Request $request)
    {
        $data = $request->input('data');
        $hookModule = explode('-',$data)[1];
        $module_id = explode('*',$hookModule)[0];
        $hook_id = explode('*',$hookModule)[1];
        // check for hook
        $Hook =Hook::find($hook_id);
        if(is_object($Hook))
        {
            // check for module
            $Module = Module::find($module_id);
            if(is_object($Module))
            {
                \DB::table('hooks_modules')->
                where('id_hook', '=', $hook_id)->
                where('id_module', '=', $module_id)->delete();
                // clear cache
                app('cache')->flush();
                return response()->json(['status'=>'success','msg'=>$this->lang('delete_success')]);

            }
            else
            {
                return response()->json(['status'=>'error','msg'=>$this->lang('invalid_data')]);
            }
        }
        else
        {
            return response()->json(['status'=>'error','msg'=>$this->lang('invalid_data')]);
        }
    }
    public function set()
    {
        $this->assign->addJS('js/register_to_hook.js');
        $data = Module::getModulesByFile();
        $modules[] = [
            'module'=>'0',
            'name'=>$this->l('select_module'),
        ];
        foreach($data as $module)
        {
            if($module['installed'])
                $modules[] = ['module'=>$module['name'],'name'=>$module['name']];
        }
        $this->title = $this->lang('register_to_hook');
        $this->fields_form = [
            [
                'title' => $this->lang('register_module_to_hook'),
                'input' => [
                    //select
                    [
                        'name' => 'module',
                        'defalut_value'=>1,
                        'type' => 'selecttwo',
                        'options'=>[
                            'query'=>$modules,
                            'key'=>'module',
                            'name'=>'name',
                        ],
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('module'),
                    ],
                    [
                        'name' => 'hook',
                        'defalut_value'=>1,
                        'type' => 'selecttwo',
                        'options'=>[
                            'query'=>[
                                ['hook'=>'--','name'=>'--']
                            ],
                            'key'=>'hook',
                            'name'=>'name',
                        ],
                        'class' => 'hidden',
                        'col' => '6',
                        'title' => $this->lang('hook'),
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' =>$this->lang('register'),
                        'type' => 'submit',
                    ],
                ],

            ],
        ];
        $this->fieldForm();
        $this->loadObject(false);
        // add some variable for view
        if ($this->tpl == false)
            $this->tpl = $this->tpl_form;
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->addJSVars([
           'loadHook'=>$this->getRoute('loadhooks'),
        ]);
        $this->assign->params([
            'id' => null,
            'form_url' => $this->getRoute('register'),
            'object' => $this->model ?: null,
            'route_list' => $this->getRoute('index'),
        ]);
        return parent::view();
    }
    public function loadHooks(Request $request)
    {
        if($request->ajax())
        {
            $validate = \Validator::make($request->all(),
                ['module'=>'required|string']
                );
            if($validate->fails())
            {
                return response()->json(['status'=>'error','msg'=>implode("\n",$validate->errors())]);
            }
            else
            {
               $Module = $this->loadModule($request->input('module'));
                if($Module && is_object($Module) && $Module->id)
                {
                    $Reflection = new \ReflectionClass($Module);
                    $methods = Tools::getMethods($Reflection,\ReflectionMethod::IS_PUBLIC,'hook%');
                    return response()->json(['status'=>'success','data'=>$methods]);
                }
                else
                {
                    return response()->json(['status'=>'error','msg'=>$this->lang('invalid_data')]);
                }
            }

        }
    }
    public function register(Request $request)
    {
        $validate = \Validator::make($request->all(),
            ['module'=>'required|string'],
            ['hook'=>'required|string']
        );
        if($validate->fails())
        {
            return redirect($this->getRoute('set'))->withErrors($validate->errors());
        }
        else
        {
            $hook = $request->input('hook');
            $Module = $this->loadModule($request->input('module'));
            if($Module && is_object($Module) && $Module->id)
            {
                // check method is exits
                if(method_exists($Module,'hook'.ucfirst($hook)))
                {
                    // check hook registered
                    $hook = Hook::isRegistered($hook);
                    if($hook && is_object($hook))
                    {
                      // check Module is registered in this hook
                        if(Hook::find($hook->id)->moduleIsRegistered($Module->id))
                            return redirect($this->getRoute('set'))->withErrors([$this->lang('hook_already_set')]);
                        $id_hook = $hook->id;
                    }
                    else
                    {
                        // register hook
                        $object = new Hook();
                        $object->name= $hook;
                        $object->save();
                        $id_hook = $object->id;
                    }

                    // register module
                    if(Hook::find($id_hook)->registerModule($Module->id))
                    {
                        \Cache::flush();
                        return $this->redirect($this->lang('hook_registered_successfully'));
                    }
                    else
                    return redirect($this->getRoute('set'))->withErrors([$this->lang('problem_register')]);
                }
                else
                {
                    return redirect($this->getRoute('set'))->withErrors([$this->lang('invalid_data')]);
                }
            }
            else
               return redirect($this->getRoute('set'))->withErrors([$this->lang('invalid_data')]);
        }
    }

    /**
     * load module by name
     * @param $name
     * @return bool|Module
     */
    protected function loadModule($name)
    {
        $data = explode(DIRECTORY_SEPARATOR,$name);
        $author = ucfirst($data[0]);
        $module = ucfirst($data[1]);
        return  Module::getInstance($author,$module);
    }

    protected function redirect($msg)
    {
        return redirect($this->getRoute('index'))->with('success', $msg);
    }
}
