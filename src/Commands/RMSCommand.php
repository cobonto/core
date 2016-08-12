<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/6/2016
 * Time: 2:26 AM
 */

namespace RmsCms\Commands;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Console\Migrations\BaseCommand;

class RMSCommand extends BaseCommand
{
    /**
     * @var Filesystem
     */
    protected $files;
    /**
     * @var string package path
     */
    protected $package_path;
    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $sp = DIRECTORY_SEPARATOR;
        $this->files = $file;
        $this->package_path = base_path('vendor'.$sp.'rmscms'.$sp.'core'.$sp.'src'.$sp.'copy'.$sp);
        parent::__construct();
    }

}