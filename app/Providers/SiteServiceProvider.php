<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SiteManager;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $smanager = new SiteManager;

        $this->app->instance('App\Services\SiteManager', $smanager);
        $this->app->bind('App\Site', function () use ($smanager) {

            return $smanager->getSite();

        });

        //db set
        $this->app['db']->extend('site', function ($config, $name) use ($smanager) {
            $site = $smanager->getSite();
            if ($site) {
                $config['database'] = 'site_' . $site->code;
            }
            return $this->app['db.factory']->make($config, $name);
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
