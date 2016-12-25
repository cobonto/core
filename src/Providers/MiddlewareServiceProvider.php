<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/24/2016
 * Time: 6:03 PM
 */

namespace Cobonto\Providers;


use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider
{
    public $middleware = [];

    public function register()
    {
        // we read from middleware from bootstrap cache if not we created it and leave it alone
        if (app('files')->exists($this->getMiddlewareCachedPath()))
        {
            // fill listen and subscribe
            $this->middleware = app('files')->getRequire($this->getMiddlewareCachedPath());
        }
        else
            $this->createMiddlewareCachedFile();
    }

    public function boot(Router $router)
    {
        if (count($this->middleware))
            foreach ($this->middleware as $name => $class)
                $router->middleware($name, $class);
    }

    public function getMiddlewareCachedPath()
    {
        return app('path.bootstrap') . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'middleware.php';
    }

    protected function createMiddlewareCachedFile()
    {
        $data = [];
        app('files')->put($this->getMiddlewareCachedPath(), '<?php' . PHP_EOL . 'return ' . var_export($data, true) . ';' . PHP_EOL);
    }
}