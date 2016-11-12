<?php

namespace Cobonto\Classes\Roles;

use LaravelArdent\Ardent\Ardent;

class RolePermission extends Ardent
{
    //
    protected $table = 'roles_permissions';
    public $incrementing = false;
    public $timestamps = false;
    /* The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'id','role_id'
    ];
    public static $rules = [
        'id' => 'required|numeric',
        'role_id' => 'required|numeric',
    ];
}