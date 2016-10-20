<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/6/2016
 * Time: 2:29 AM
 */

namespace Cobonto\Commands;


class ExportCommand extends CobontoCommand
{
    /**
     * @param string $name
     */
    protected $name = 'cobonto:export';
    /**
     * @param string $description
     */
    protected $description ='export files from system and import to package';

    public function handle()
    {
        $sp = DIRECTORY_SEPARATOR;
        // we start to copy data from system to package name of folders
        $folders = [
            'resources/views',
            'resources/lang',
            'database/migrations'
        ];
        $files = [
            'config/app.php',
            'app/Http/Kernel.php',
            #'app/Http/routes.php',
           # 'app/User.php',
        ];
        // change separtor
        $index=0;
        foreach($folders as $folder)
        {

            $folders[$index] = trim(str_replace('/',$sp,$folder));
            $index++;
        }
        foreach($folders as $folder)
        {
            if($this->files->copyDirectory(base_path($folder),$this->package_path.$folder))
                $this->info($folder .' is exported');
            else
                $this->info($folder.' is not exported, manually updated');
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