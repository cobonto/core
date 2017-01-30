<?php

namespace Cobonto\Classes;

use Module\Classes\Module;

class Tools
{
    /**
     * search in multidemential array with key and value
     * @param array $array
     * @param $key
     * @param $value
     * @return array
     */
    public static function searchInMultiArray(array $array, $key, $value, $firstResult = false)
    {
        $results = [];

        if (is_array($array))
        {
            if (isset($array[$key]) && $array[$key] == $value)
            {
                // check if we need to first result
                if ($firstResult)
                    return $array;
                $results[] = $array;
            }

            foreach ($array as $subarray)
            {
                if(is_array($subarray))
                $results = array_merge($results, self::searchInMultiArray($subarray, $key, $value));
            }
        }

        return $results;
    }

    /**
     * get specific methods name
     * @param \ReflectionClass $class
     * @param int $filter
     * @param bool $containName
     * @return array|\ReflectionMethod[]
     */
    public static function getMethods(\ReflectionClass $class, $filter = \ReflectionMethod::IS_PUBLIC, $containName = false)
    {
        $methods = $class->getMethods($filter);
        if ($methods && count($methods) && $containName)
        {
            $remove = 'first';
            if ($containName[0] == '%' && $containName[strlen($containName) - 1] == '%')
            {

                $remove = false;
            }

            elseif ($containName[0] != '%' && $containName[strlen($containName) - 1] == '%')
            {
                $remove = 'first';
            }
            elseif ($containName[0] == '%' && $containName[strlen($containName) - 1] != '%')
            {
                $remove = 'last';
            }
            $containName = trim($containName,'%');
            $newMethods = [];
            foreach ($methods as $method)
            {
                if (strpos($method->name,$containName) !== false)
                    if ($remove == 'first')
                    {

                        $newMethods[] = lcfirst(substr($method->name,strlen($containName),strlen($method->name)));
                    }
                    elseif ($remove == 'last')
                    {
                        $newMethods[] = lcfirst(substr($method->name,-strlen($containName),strlen($method->name)));
                    }
            }
            return $newMethods;
        }
        else
            return $methods;
    }
}