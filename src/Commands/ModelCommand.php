<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/6/2016
 * Time: 3:06 AM
 */

namespace RmsCms\Commands;


use Illuminate\Support\Str;
use Module\Commands\ModuleCommand;
use Symfony\Component\Console\Input\InputArgument;

class ModelCommand extends ModuleCommand
{
    /**
     * @string name
     */
    protected $name='rms:model';
    protected function getStub()
    {
        return __DIR__.'/stubs/model.stub';
    }
    protected function getDefaultNamespace($Class)
    {
        return 'RmsCms\\Classes';
    }
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function getArguments()
    {
        return [
            ['class', InputArgument::REQUIRED, 'The name of the model'],
        ];
    }
    public function handle()
    {
        $class = $this->argument('class');


        $path = $this->getPath($class);
        $name = $this->parseName($class);
        if ($this->alreadyExists($name)) {
            $this->error($this->type.' already exists!');

            return false;
        }
        $this->makeDirectory($path);
        // create Module.php file
        $this->files->put($path, $this->buildClass($name));
        $this->info($this->type.' created successfully.');
    }
    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function parseName($name)
    {
        return ($this->getDefaultNamespace('').'\\'.$name);
    }
    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = base_path('vendor/spyp/rmscms/src/Classes');
        return $path.'/'.str_replace('\\', '/', $name).'.php';
    }


}