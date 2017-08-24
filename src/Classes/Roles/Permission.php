<?php

namespace Cobonto\Classes\Roles;

use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Arr;
use LaravelArdent\Ardent\Ardent;

class Permission extends Ardent
{
    protected $table = 'permissions';
    //   public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
    public $autoPurgeRedundantAttributes = true;
    public $autoHashPasswordAttributes = true;
    public $timestamps = false;
    protected static $admin_url;
    protected static $ignore = ['AuthController', 'ModulePositionsController', 'PermissionsController'];
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
     * get all permissions
     * @return collection
     */
    public static function getControllers()
    {
        $results = [
            [
                'title'=>transTpl('core','permission'),
                'controllers'=>[
                    [
                        'name' => transTpl('modules', 'permission'),
                        'route' => 'modules',
                        'controller' => 'ModulesController',
                        'exceptions' => [],
                    ],
                    [
                        'name' => transTpl('module_positions', 'permission'),
                        'route' => 'positions',
                        'controller' => 'ModulesController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('permissions', 'permission'),
                        'route' => 'positions',
                        'controller' => 'ModulesController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('module_positions', 'permission'),
                        'route' => 'positions',
                        'controller' => 'ModulesController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('settings', 'permission'),
                        'route' => 'settings.settings',
                        'controller' => 'SettingsController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('translate', 'permission'),
                        'route' => 'translates',
                        'controller' => 'TranslatesController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('dashboard', 'permission'),
                        'route' => 'dashboard',
                        'controller' => 'DashboardController',
                        'exceptions' => ['destroy', 'create', 'edit'],
                    ],
                    [
                        'name' => transTpl('users', 'permission'),
                        'route' => 'users',
                        'controller' => 'UsersController',
                        'exceptions' => [],
                    ],
                    [
                        'name' => transTpl('roles', 'permission'),
                        'route' => 'roles',
                        'controller' => 'RolesController',
                        'exceptions' => [],
                    ],
                    [
                        'name' => transTpl('groups', 'permission'),
                        'route' => 'groups',
                        'controller' => 'GroupsController',
                        'exceptions' => [],
                    ],
                    [
                        'name' => transTpl('admins', 'permission'),
                        'route' => 'admins',
                        'controller' => 'AdminsController',
                        'exceptions' => [],
                    ],
                ]
            ],
        ];
        hook('permissions',[
            'permissions'=>&$results,
        ]);
        /**
         * $routes = \Route::getRoutes();
         * $results = [];
         * self::$admin_url = config('app.admin_url');
         * foreach($routes as $route)
         * {
         * $actionName = $route->getActionName();
         * if(trim($route->getPrefix(),'/')!=self::$admin_url || count(explode('@',$actionName))<2)
         * continue;
         * // get controller from actionName
         *
         * list($controller,$action) = explode('@',$actionName);
         * $route_name = $route->getAction();
         * if(!isset($route_name['as']))
         * continue;
         *
         * if(!isset($results[$controller]))
         * {
         * /**
         * maybe we have same controller name with different namespaces so we know one of theme come from module
         * for modules we know is App\Modules\Author\Module\Controolers\controller so we remove App\Modules\ and get module name and author name
         *
         * $controllersArray = explode('\\',$controller);
         * // check is module controller or not
         * if(isset($controllersArray[1]) && $controllersArray[1]=='modules')
         * // we need two and three index of array
         * $class_basename = $controllersArray[2].'-'.$controllersArray['3'].'-'.class_basename($controller);
         * else
         * $class_basename = class_basename($controller);
         * $results[$controller] =[
         * 'name'=>$class_basename,
         * 'route'=>self::getRoute($route_name['as'])
         * ];
         * }
         * } */
        return $results;
    }

    /**
     * @param $route
     * @return string
     */
    protected static function getRoute($route)
    {
        // remove prefix
        $routeArray = explode('.', $route);
        // remove prefix
        unset($routeArray[0]);
        // now check for how much route we have
        if (count($routeArray) < 2)
            return $routeArray[1];
        else {
            $actions = ['index', 'edit', 'create', 'destroy', 'store', 'install'];
            if (in_array(last($routeArray), $actions)) {
                $reverse = array_reverse($routeArray);
                unset($reverse[0]);
                return implode('.', $reverse);
            } else
                return implode('.', $routeArray);
        }
    }
}