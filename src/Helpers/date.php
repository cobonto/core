<?php

/**
 * different time for human view
 */
if (!function_exists('diffForHumans'))
{
    /**
     * Last activity date
     * @param $date
     * @param null $other
     * @return string
     */
    function diffForHumans($date,$other=null)
    {
        if(config('app.rtl')){
            JDate::setLocale('fa');
            return JDate::parse($date)->diffForHumans($other);
        }

        return \Carbon\Carbon::parse($date)->diffForHumans($other);
    }
}
/**
 * date format
 */
if (!function_exists('dateFormat'))
{
    /**
     * Format date
     * @param $date
     * @param $format
     * @return string
     */
    function dateFormat($date,$format)
    {
        if(config('app.rtl'))
            return Morilog\Jalali\jDateTime::strftime($format, strtotime($date)); // 1395-02-19
        return \Carbon\Carbon::parse($date)->format($format);
    }
}
/**
 * diff between two time
 */
if (!function_exists('diffTime')){

    /**
     * Format date
     * @param $date
     * @param $format
     * @return int
     */
    function diffTime($first_date,$second_date='NOW',$type='Seconds',$abs=true)
    {
        $second = new \Carbon\Carbon($second_date);
        $first = new \Carbon\Carbon($first_date);
        return $first->{"diffIn".$type}($second,$abs);
    }
}

