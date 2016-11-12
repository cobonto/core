<?php

namespace Cobonto\Classes\Roles;

use LaravelArdent\Ardent\Ardent;

class Role extends Ardent
{
    protected $table = 'roles';
    public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
    public $autoPurgeRedundantAttributes = true;
    public $autoHashPasswordAttributes = true;
    public $timestamps =false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    public static $rules = [
        'name' => 'required|string|between:3,255',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function users()
    {
        return $this->hasMany('App\Users');
    }

    public function permissions()
    {
        return $this->hasManyThrough('Cobonto\Classes\Roles\Permission', 'Cobonto\Classes\Roles\RolePermission', null, 'id');
    }

    /**
     * check this role has permission
     * @param $access
     * @return bool
     */
    public function hasPermission($access)
    {
        $rolePermissions = self::getRolePermissions($this->id);
        if (isset($rolePermissions[$access]))
            return true;
        else
            return false;
    }

    public static function clearCache()
    {
        \Cache::forget('roles');
    }

    /**
     * get permissions for role
     * @param $id
     * @return array
     */
    public static function getRolePermissions($id)
    {
        $rolePermissions = \Cache::remember('roles', 10000, function ()
        {
            $permissions = [];
            $roles = Role::all();
            foreach ($roles as $role)
            {
                $permissionsInRole = $role->permissions;
                if (count($permissionsInRole) > 0)
                {
                    foreach ($permissionsInRole as $permission)
                    {
                        $permissions[$role->id][$permission->name] = 1;
                    }
                }

            }
            return $permissions;
        });
        return isset($rolePermissions[$id])?$rolePermissions[$id]:[];
    }
}