<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/29/2016
 * Time: 9:39 PM
 */

namespace Cobonto\Classes;

use Illuminate\Filesystem\Filesystem;

/**
 * Class Assign responsible for store and pass data to view files
 * @package Cobonto\Classes
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
     * @var string $enviroment
     */
    protected $environment='';
    /**
     * __construct
     * @var Filesystem $file
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * add js file to system
     * @param array|string file name $files
     * @param bool $external file may be external of admin path
     * @return void
     */
    public function addJS($files,$external=false)
    {
        if (!is_array($files))
            $files = array($files);
        foreach ($files as $file)
        {
            if(!$external)
                $file = $this->environment.$file;
            if ($this->files->exists(public_path($file)))
            {
                $this->JSFiles[] = $file;
            }
        }
    }

    /**
     * add css file to system
     * @param array|string file name $files
     * @param bool $module assign for modules
     * @return void
     */
    public function addCSS($files,$module=false)
    {
        if (!is_array($files))
            $files = array($files);
        foreach ($files as $file)
        {
            if(!$module)
            {
                $file = $this->environment.$file;
            }

            // check for load css rtl
            if(config('app.rtl') && $module)
            {
                // we change .css filename to _rtl.css and check is exists or not
                $rtl_file = str_replace('.css','_rtl.css',$file);
                if($this->files->exists(public_path($rtl_file)))
                    $file = $rtl_file;
            }

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
           if($this->files->exists('plugins/' . $name . '/' . $name . '.min.js'))
                $this->JSFiles[]='plugins/' . $name . '/' . $name . '.min.js';
            if($this->files->exists('plugins/' . $name . '/' . $name . '.css'))
                $this->CSSFiles[]='plugins/' . $name . '/' . $name . '.css';
    }
    /**
     * add jquery ui to system
     * @param array|string file name $files
     * @return void
     */
    public function addUI($name)
    {

    }

    /**
     * assign variable to view
     * @param $key
     * @param null|string $value
     */
    public function params($key, $value = null)
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

    /**
     * set environment to load files
     * @param $environment
     */
    public function setEnvironment($environment)
    {
        if($environment !='')
            $this->environment = $environment.'/';
    }
    public function getEnvironment()
    {
        return $this->environment;
    }
}