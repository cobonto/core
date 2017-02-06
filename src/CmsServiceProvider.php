<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/29/2016
 * Time: 1:58 AM
 */

namespace Cobonto;

use Dingo\Api\Auth\Provider\JWT;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Cobonto\Classes\Assign;
use Cobonto\Classes\CmsBladeCompiler;
use Cobonto\Classes\Settings;
use Cobonto\Commands\ExportCommand;
use Cobonto\Commands\ImportCommand;
use Cobonto\Commands\ModelCommand;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerProviders();
        $this->registerCommands();
        $this->registerSettings();
        $this->registerHelpers();
     //   $this->registerCompiler();
    }
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes/routes.php';
        }
        $this->bootValidators();
        $this->publishes([
            __DIR__.'/copy/config'=>base_path('config'),
            __DIR__.'/copy/resources/views'=>resource_path('views'),
            __DIR__.'/copy/resources/lang'=>resource_path('lang'),
            __DIR__.'/copy/app'=>app_path(),
            __DIR__.'/copy/database'=>database_path(),
        ]);
        $this->registerApi();
    }
    protected function registerCommands()
    {
        $commands = ['Import', 'Model', 'Export'];
        foreach ($commands as $command)
        {
            $this->{'register' . $command . 'Command'}();
        }

        $this->commands(
            'cobonto.import',
            'cobonto.model',
            'cobonto.export'
        );
    }
    protected function registerImportCommand()
    {
        $this->app->singleton('cobonto.import', function ($app)
        {
            return new ImportCommand($app['files']);
        });
    }

    protected function registerModelCommand()
    {
        $this->app->singleton('cobonto.model', function ($app)
        {
            return new ModelCommand($app['files']);
        });
    }

    protected function registerExportCommand()
    {
        $this->app->singleton('cobonto.export', function ($app)
        {
            return new ExportCommand($app['files']);
        });
    }
    protected function registerSettings(){
        $this->app->singleton('assign', function ($app) {
            return new Assign($app['files']);
        });
        $this->app->singleton('settings', function ($app)
        {
            return new Settings([],true);
        });
    }

    /**
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     */
    protected function registerCompiler()
    {
        $resolver = app('view.engine.resolver');
        $app = $this->app;
        $app->singleton('blade.compiler', function($app)
        {
            $cache = $app['config']['view.compiled'];
            return new CmsBladeCompiler($app['files'], $cache);
        });

        $resolver->register('blade', function () use ($app) {
            return new CompilerEngine($app['blade.compiler']);
        });
    }
    /** register helpers functions */
    protected function registerHelpers()
    {
        // require all helpers files in Helper folder
        foreach (glob(dirname(__FILE__).'/Helpers/*.php') as $filename){
            require_once($filename);
        }
    }
    protected function bootValidators()
    {
        \Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
    }
    protected function registerProviders()
    {
        $providers = [
            'Cobonto\Providers\EventServiceProvider',
            'Cobonto\Providers\MiddlewareServiceProvider'
        ];
        foreach($providers as $provider)
            $this->app->register($provider);
    }

    protected function registerApi()
    {
        app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
            return new JWT($app['tymon.jwt.auth']);
        });
    }
}