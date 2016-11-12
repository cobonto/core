<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/6/2016
 * Time: 3:07 PM
 */

namespace Cobonto\Controllers\Admin;


use Cobonto\Classes\Roles\Permission;
use Cobonto\Classes\Roles\Role;
use Cobonto\Classes\Roles\RolePermission;
use Cobonto\Controllers\AdminController;
use Illuminate\Http\Request;

class PermissionsController extends AdminController
{
    protected $tpl='permission';
    protected function setProperties()
    {
        parent::setProperties();
        $this->title = $this->lang('permissions');
    }
    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addCSS('css/bootstrap.vertical-tabs.css');
        $this->assign->addJS('js/permissions.js');
        $this->assign->addPlugin('growl');
    }

    protected function index()
    {
        // get all roles
        $this->assign->addJSVars([
           'load_role_url'=>route('admin.permissions.role'),
           'update_permissions_url'=>route('admin.permissions.update'),
        ]);

        $this->assign->params([
            'roles'=>Role::where('admin',1)->get(),
        ]);
        return parent::view();
    }
    protected function updatePermissions(Request $request)
    {
        $role_id = $request->input('role');
        $Role = Role::find($role_id);
        $route = $request->input('route');
        Role::clearCache();
        // check route is exists or not
        if(is_object($Role))
        {
            $permission = Permission::where('name',$route)->first();
            $new = false;
            if(!$permission)
            {
                // add to permission
                $permission = new Permission();
                $permission->name = $route;
                $permission->save();
                $new = true;
            }
            // check if exists delete if not add to permission roles
            if($new)
            {
                $role_permission = new RolePermission();
                $role_permission->id = $permission->id;
                $role_permission->role_id = $role_id;
                $role_permission->save();
                return  response()->json(['status'=>'success','msg'=>$this->lang('update_success')]);
            }
            else
            {
                // check for this role is created or not
                $role_permission = RolePermission::where(['id'=>$permission->id,'role_id'=>$role_id])->first();
                if(is_object($role_permission))
                    $role_permission->delete();
                else
                {
                    $role_permission = new RolePermission();
                    $role_permission->id = $permission->id;
                    $role_permission->role_id = $role_id;
                    $role_permission->save();
                }
                return  response()->json(['status'=>'success','msg'=>$this->lang('update_success')]);
            }

        }
        else
        {
            return  response()->json(['status'=>'error','msg'=>$this->lang('something_wrong')]);
        }
    }
    protected function role(Request $request)
    {
        $role_id = $request->input('role');
        $Role = Role::find($role_id);
        if(is_object($Role))
        {
            $permissions = $Role->getRolePermissions($Role->id);
            $html = view('admin.permission.content',[
                'permissions'=>$permissions,
                'controllers'=>Permission::getControllers(),
                'role'=>$Role,
            ])->render();
            return response()->json(['status'=>'success','html'=>$html]);
        }
    }
}