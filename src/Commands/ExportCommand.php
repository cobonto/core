<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/6/2016
 * Time: 2:29 AM
 */

namespace RmsCms\Commands;


class ExportCommand extends RMSCommand
{
    /**
     * @param string $name
     */
    protected $name = 'rms:export';
    /**
     * @param string $description
     */
    protected $description ='export files from system and import to package';

    public function handle()
    {
        $sp = DIRECTORY_SEPARATOR;
        // we start to copy data from system to package name of folders
        $folders = ['app/Http/Controllers/Admin',
                    'database/migrations',
                    'database/seeds',
                    'resources/views/admin'];
        $files = [
            'config/app.php',
            'app/Http/Kernel.php',
            'app/Http/routes.php',
            'app/User.php',
        ];
        // change separtor
        foreach($folders as &$folder)
        {
            $folder = str_replace('/',$sp,$folder);
        }
        foreach($folders as $folder)
        {
            if($this->files->copyDirectory(base_path($folder),$this->package_path.$folder))
                $this->info($folder .' is exported');
            else
                $this->error($folder.' is not exported, manually updated');
        }
         foreach($files as $file)
         {// we have to check first

                 if($this->files->copy(base_path($file),$this->package_path.$file))
                     $this->info($file .' is exported');
                 else
                     $this->error($file.' is not exported, manually updated');


         }
    }
}