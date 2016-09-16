<?php

namespace Cobonto\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Input;
use Cobonto\Controllers\AdminController;
use Module\Classes\Hook;


class ModulePositionsController extends AdminController
{
    protected $tpl = 'position';

    protected function setProperties()
    {
        parent::setProperties();
        $this->title = $this->lang('module_positions_title');
        $this->route_name = 'positions';
    }

    protected function index()
    {
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
        ]);
        // add some javascript vars
        $this->assign->addJSVars([
            'ajax_position_url' => route($this->route_name . 'update'),
        ]);
        return parent::index();
    }

    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addCSS('css/position.css');
        $this->assign->addPlugin('tablednd');
        $this->assign->addPlugin('growl');
        $this->assign->addJS('js/position.js');
    }

    public function updatePositions()
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
}
