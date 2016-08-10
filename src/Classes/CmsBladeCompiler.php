<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/16/2016
 * Time: 11:12 PM
 */

namespace RmsCms\Classes;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;

class CmsBladeCompiler extends BladeCompiler
{
    public function __construct(Filesystem $files, $cachePath)
    {
        if (! \Config::get('view.cache'))
            $cachePath=null;
        parent::__construct($files,$cachePath);
    }
}