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
        if (\Auth::user()->role_id == 1)
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
            $hasPermission =\Auth::user()->role()->hasPermission($route . '.' . $method);
            if (!$hasPermission && $allTrue)
                return false;
            elseif($hasPermission && !$allTrue)
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
    function hook($hook, $params=[])
    {
        return \Module\Classes\Hook::execute($hook,$params);
    }
}