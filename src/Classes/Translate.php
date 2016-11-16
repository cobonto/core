<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/15/2016
 * Time: 9:08 AM
 */

namespace Cobonto\Classes;


class Translate
{
    /**
     * get languages from folder lang
     * @return array
     */
    public static function getLanguages()
    {
        $langs = \File::directories(resource_path('lang'));
        if($langs && count($langs))
        {
            foreach($langs as &$language)
            {
                $language = basename($language);
            }
        }
        return $langs;
    }
    /**
     * @param string $language
     * get core files names for translate
     * @return array
     */
    public static function getCoreFiles($language)
    {
        $files = \File::files(resource_path('lang'.DIRECTORY_SEPARATOR.$language));
        if($files && count($files))
        {
            foreach($files as &$file)
            {
                $file = basename($file);
            }
        }
        return $files;
    }
    /**
     * @param string $language
     * get core files names for translate
     * @return array
     */
    public static function getModuleFiles($language)
    {
       $modules = \Cache::get('modules');
        $source = app_path('Modules'.DIRECTORY_SEPARATOR.'{author}'.DIRECTORY_SEPARATOR.'{module}'.DIRECTORY_SEPARATOR.'translate'.DIRECTORY_SEPARATOR.$language.'.php');
        $files = [];
        if($modules && count($modules))
        foreach($modules as $author=>$subModules)
        {
            foreach($subModules as $module)
            {
                $data = ['{author}'=>$module['author'],'{module}'=>$module['name']];
                $real_source = str_replace(array_keys($data),array_values($data),$source);
                if(\File::exists($real_source))
                {
                    $files[] =$author.DIRECTORY_SEPARATOR.$module['name'];
                }
            }
        }
        return $files;
    }
    /**
     * return entire lang file data
     * @var string $path
     * @rturn array
     */
    public static function getFile($path)
    {
       return include($path);
    }

    /**
     * save new data in file
     * @param $path
     * @param $data
     */
    public static function saveFile($path,$data)
    {
        // we know file exists but we check it again
        if(\File::exists($path))
        {
            $data = "<?php \n return ".var_export($data,true).';';
            return  \File::put($path, $data);
        }
        else
            return false;
    }
}