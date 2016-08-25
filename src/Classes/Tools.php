<?php

namespace Cobonto\Classes;

class Tools
{
    /**
     * search in multidemential array with key and value
     * @param array $array
     * @param $key
     * @param $value
     * @return array
     */
    public static function searchInMultiArray(array $array, $key, $value,$firstResult=false)
    {
        $results = [];

        if (is_array($array))
        {
            if (isset($array[$key]) && $array[$key] == $value)
            {
                // check if we need to first result
                if($firstResult)
                    return $array;
                $results[] = $array;
            }

            foreach ($array as $subarray)
            {
                $results = array_merge($results, self::search($subarray, $key, $value));
            }
        }

        return $results;
    }
}