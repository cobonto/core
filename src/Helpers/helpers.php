<?php
if (!function_exists('hasAccess'))
{
    /**
     *  determine this route has access for this employee or not
     * @param string|array $routes
     * @param string $method
     * @return bool
     */
    function hasAccess($routes, $method = 'index')
    {
        if (\Auth::guard('admin')->user()->role_id == 1)
            return true;
        // we check at least one them is true or all of theme must be true
        $allTrue = true;
        if (!is_array($routes))
            $routes = [$routes];
        else
            $allTrue = false;
        $result = true;
        foreach ($routes as $route)
        {
            if($method!=null)
                $route .='.'.$method;
            $hasPermission = \Auth::guard('admin')->user()->role()->hasPermission($route);
            if (!$hasPermission && $allTrue)
                return false;
            elseif ($hasPermission && !$allTrue)
                return true;
            else
                $result &= $hasPermission;
        }
        return $result;
    }
}
if (!function_exists('hook'))
{
    /**
     * execute hook helper function
     * @param string $hook
     * @param array $params
     * @return html
     */
    function hook($hook, $params = [])
    {
        return \Module\Classes\Hook::execute($hook, $params);
    }
}

if (!function_exists('displayPrice'))
{
    /**
     * Display price
     * @param $price
     * @param string $sign
     * @return string
     */
    function displayPrice($price, $sign = false)
    {
        if (!$sign)
            $sign = config('app.currency', 'ریال');
        return number_format($price, 0, '.', ',') . ' ' . $sign;
    }
}

if(!function_exists('settings')){
    function settings($key=null){
        if(!$key)
            return app('settings');
        else
            return app('settings')->get($key);
    }
}