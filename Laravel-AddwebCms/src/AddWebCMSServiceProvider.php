<?php
namespace AddWeb\CMS;
use AddWeb\CMS\View\Component\AddWebViewComponentClass;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AddWebCMSServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
            __DIR__.'/../config/addWebCms.php' => config_path('addWebCms.php'),
            __DIR__.'/../resources/js' => public_path('vendor/addwebcms')
        ],'add-web-cms');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/view','add-web-view');

        Blade::component('add-web-cms', AddWebViewComponentClass::class);
    }

}