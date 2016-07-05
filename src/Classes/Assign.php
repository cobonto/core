<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/29/2016
 * Time: 9:39 PM
 */

namespace RmsCms\Classes;

use Illuminate\Filesystem\Filesystem;

/**
 * Class Assign responsible for store and pass data to view files
 * @package RmsCms\Classes
 */
class Assign
{
    /**
     * @var array javascript files
     */
    protected $JSFiles=[];
    /**
     * @var array javascript vars
     */
    protected $JSVars=[];
    /**
     * @var array css files
     */
    protected $CSSFiles=[];
    /**
     * @var array global template vars
     */
    protected $TPLVars=[];
    /**
     * @var Filesystem $files
     */
    protected $files=[];

    /**
     * __construct
     * @var Filesystem $file
     */
    public function __construct($files)
    {
        $this->files = $files;
    }

    /**
     * add js file to system
     * @param array|string file name $files
     * @return void
     */
    public function addJS($files)
    {
        if (!is_array($files))
            $files = array($files);
        foreach ($files as $file)
        {
            if ($this->files->exists(public_path($file)))
            {
                $this->JSFiles[] = $file;
            }
        }
    }

    /**
     * add css file to system
     * @param array|string file name $files
     * @return void
     */
    public function addCSS($files)
    {
        if (!is_array($files))
            $files = array($files);
        foreach ($files as $file)
        {
            if ($this->files->exists(public_path($file)))
            {
                $this->CSSFiles[] = $file;
            }
        }
    }

    /**
     * add jquery plugin to system
     * @param array|string file name $files
     * @return void
     */
    public function addPlugin($name)
    {
        if ($this->files->exists(public_path('plugins/' . $name)))
        {
            $this->addJS('plugins/' . $name . '/' . $name . '.min.js');
            $this->addCSS('plugins/' . $name . '/' . $name . '.css');
        }
    }

    /**
     * assign variable to view
     * @param $key
     * @param null|string $value
     */
    public function view($key, $value = null)
    {
        if ($value == null)
        {
            if (is_array($key))
            {
                foreach ($key as $var => $value)
                    $this->TPLVars[$var] = $value;
            }
        }
        else
        {
            $this->TPLVars[$key] = $value;
        }
    }

    /**
     * add js variables to view
     * @param $key
     * @param null $value
     */
    public function addJSVars($key,$value=null)
    {
        if ($value == null)
        {
            if (is_array($key))
            {
                foreach ($key as $var => $value)
                    $this->JSVars[$var] = $value;
            }
        }
        else
        {
            $this->JSVars[$key] = $value;
        }
    }
    /** return javascript files
     * @return array
     */
    public function getJS()
    {
        return $this->JSFiles;
    }
    /** return css files
     * @return array
     */
    public function getCSS()
    {
        return $this->CSSFiles;
    }
    /** return variables to view files
     * @return array
     */
    public function getViewData()
    {
        return $this->TPLVars;
    }
    /** return javascript vars
     * @return array
     */
    public function getJSVars()
    {
        return $this->JSVars;
    }
}