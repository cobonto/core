<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/10/2016
 * Time: 1:08 PM
 */

namespace Cobonto\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        // we read from events from bootstrap cache if not we created it and leave it alone
       if(app('files')->exists($this->getEventCachedPath()))
        {
            // fill listen and subscribe
            $event = app('files')->getRequire($this->getEventCachedPath());
            $this->listen =$event['listen'];
            $this->subscribe =$event['subscribe'];
        }
        else
            $this->createEventCachedFile();
        parent::register();
    }
    public function getEventCachedPath()
    {
       return app('path.bootstrap').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'events.php';
    }
    protected function createEventCachedFile()
    {
        $data = ['listen'=>[],'subscribe'=>[]];
        app('files')->put($this->getEventCachedPath(),'<?php'.PHP_EOL.'return '.var_export($data,true).';'.PHP_EOL);
    }
}