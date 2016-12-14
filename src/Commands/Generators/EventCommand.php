<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/14/2016
 * Time: 1:48 AM
 */

namespace Cobonto\Commands;

use Illuminate\Console\GeneratorCommand;
class EventCommand extends GeneratorCommand
{

    /**
    * @param string $name
    */
    protected $name = 'cobonto:event';
    /**
     * @param string $description
     */
    protected $description ='make core events file';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Event';

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return class_exists($rawName);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/event.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Events';
    }
}