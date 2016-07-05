<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 6/29/2016
 * Time: 1:58 AM
 */

namespace RmsCms;

use Illuminate\Support\ServiceProvider;
use RmsCms\Classes\Assign;

class CmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('assign', function ($app) {
            return new Assign($app['files']);
        });
    }
    public function boot()
    {
        $this->publishes([
            __DIR__.'/copy/config'=>base_path('config'),
            __DIR__.'/copy/resources/views'=>resource_path('views'),
            __DIR__.'/copy/app'=>app_path(),
            __DIR__.'/copy/database'=>database_path(),
        ]);
    }
}