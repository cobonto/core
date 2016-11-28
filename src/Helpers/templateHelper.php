<?php
if (!function_exists('transTpl'))
{
    /**
     * translate for tpl
     * @param $string
     * @param string $file
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    function transTpl($string, $file = 'admin')
    {
        return trans($file . '.' . $string);
    }
}
if (!function_exists('transModule'))
{
    /**
     * translate for module core
     * @param $string
     * @param Module\Classes\Module|null $module
     * @param null $author
     * @param null $name
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    function transModule($string, $module = null, $author = null, $name = null)
    {
        if ($module)
        {
            $author = $module->author;
            $name = $module->name;
        }
        else
        {
            $author = ucfirst($author);
            $name = ucfirst($name);
        }
        return trans('Modules::' . $author . '.' . $name . '.' . $string);
    }
}
if (!function_exists('activeLink'))
{
    /**
     * determine menu is active or not
     * @param $string
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    function activeMenu($string,$class_name='active')
    {
        $string = config('app.admin_url').'/'.$string;
        if(\Request::is($string) || \Request::is($string.'/*'))
            return $class_name;
        else
            return '';

    }
}
if (!function_exists('adminRoute'))
{
    function adminRoute($name, $parameters = [], $absolute = true)
    {
        $name = config('app.admin_url').'.'.$name;
        return app('url')->route($name, $parameters, $absolute);
    }
}
