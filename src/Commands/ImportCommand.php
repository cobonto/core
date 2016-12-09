<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/6/2016
 * Time: 2:29 AM
 */

namespace Cobonto\Commands;


use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends CobontoCommand
{
    /**
     * @param string $name
     */
    protected $name = 'cobonto:import';
    /**
     * @param string $description
     */
    protected $description = 'Import asset files to system';

    public function handle()
    {
        $sp = DIRECTORY_SEPARATOR;
        // we start to copy data from system to package name of folders
        $folders = [
            'resources/views',
            'resources/lang',
           # 'app/Http/Controllers/Admin',
            'database/migrations',
            'database/seeds',
        ];
        $files = [
            'config/app.php',
            'config/auth.php',
            'app/Http/Kernel.php',
            #'app/Http/routes.php',
            'app/User.php',
        ];
        $file_changes = [];
        foreach ($folders as $folder)
        {
            $folder = str_replace('/', $sp, $folder);
            $all_files = $this->files->allFiles($this->package_path . $folder);
            foreach ($all_files as $file)
            {
                $file_with_folder = str_replace($this->package_path . $folder, '', $file->getRealPath());
                $file = $folder . $file_with_folder;
                if ($this->files->isFile(base_path($file)))
                    $systemTime = $this->files->lastModified(base_path($file));
                else
                    $systemTime = 0;
                $packageTime = $this->files->lastModified($this->package_path . $file);

                if ($systemTime != $packageTime)
                {
                    if ($systemTime < $packageTime)
                    {
                        if ($this->files->copy($this->package_path . $file, base_path($file)))
                            $this->info($file . ' is imported');
                        else
                            $this->error($file . ' is not imported, manually updated');
                    }
                    else
                    {
                        $file_changes[] = $file;
                    }

                }
            }

        }
        foreach ($files as $file)
        {
            $file = str_replace('/', $sp, $file);
            $systemTime = $this->files->lastModified(base_path($file));
            $packageTime = $this->files->lastModified($this->package_path . $file);

            if ($systemTime != $packageTime)
            {
                if ((int)$systemTime < (int)$packageTime)
                {
                    if ($this->files->copy($this->package_path . $file, base_path($file)))
                        $this->info($file . ' is imported');
                    else
                        $this->error($file . ' is not imported, manually updated');
                }
                else
                {
                    $file_changes[] = $file;
                }

            }
        }
        // check file changes
        if (count($file_changes))
        {
            $this->info('----------------FILE CHANGES:--------------' . "\n");
            foreach ($file_changes as $file)
            {
                $this->info($file . "\n");
            }
            if ($this->confirm('This files is modified or time of this file is newer from package files do you want to change it?[yes|no]'))
            {
                foreach ($file_changes as $file)
                {
                    if ($this->files->copy($this->package_path . $file, base_path($file)))
                        $this->info($file . ' is imported');
                    else
                        $this->error($file . ' is not imported, manually updated');
                    $this->info($file . ' is imported');
                }
            }

        }
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'force copy'],
            ['lang', null, InputOption::VALUE_NONE, 'lang  copy'],
        ];
    }
}