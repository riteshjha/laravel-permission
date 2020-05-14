<?php

namespace Rkj\Permission;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/views', 'permission');

        $this->publishes([
            __DIR__.'/config/permission.php' => config_path('permission.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/permission'),
        ], 'views');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');

    }
}
