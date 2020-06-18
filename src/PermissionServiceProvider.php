<?php

namespace Rkj\Permission;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;
use Rkj\Permission\Commands\RecordAbility;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bootCommands();

        $this->loadViewsFrom($this->getViewPath(), 'permission');

        $this->publishConfig();

        $this->publishMigrations();

        $this->publishViews();

        $this->publishAssets();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user, $ability, $params) {
            if ($user->isSuperAdmin() || $user->hasAccess($ability, $params)) {
                return true;
            }
        });

        $this->searchMacro();

        $this->stringableMacro();
    }

    /**
     * Publish package config
     *
     * @return void
     */
    protected function publishConfig()
    {
        $path = $this->getConfigPath();

        $this->publishes([$path => config_path('permission.php')], 'config');
    }

    /**
     * Publish package migrations
     *
     * @return void
     */
    protected function publishMigrations()
    {
        $path = $this->getMigrationsPath();

        $this->publishes([
            $path . '/' . 'create_permission_setup_table.php.stub'  => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_permission_setup_table.php'),
        ], 'migrations');
    }

    /**
     * Publish package views
     *
     * @return void
     */
    protected function publishViews()
    {
        $path = $this->getViewPath();
        
        $this->publishes([$path => resource_path('views/vendor/permission')], 'views');
    }

    /**
     * Publish package assets
     *
     * @return void
     */
    protected function publishAssets()
    {        
        $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/permission')], 'public');
    }

    /**
     * Get package config path
     *
     * @return void
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/permission.php';
    }

    /**
     * Get package migrations path
     *
     * @return void
     */
    protected function getMigrationsPath()
    {
        return __DIR__ . '/../database/migrations';
    }

    /**
     * Get package view path
     *
     * @return void
     */
    protected function getViewPath()
    {
        return __DIR__.'/../resources/views';
    }

    /**
     * Register package commands
     *
     * @return void
     */
    protected function bootCommands()
    {
        $this->commands([
            RecordAbility::class,
        ]);
    }

    /**
     * Micro for search using builder
     *
     * @return void
     */
    protected function searchMacro()
    {
        //Search micro
        Builder::macro('search', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }

    /**
     * Macro on stringable
     *
     * @return void
     */
    public function stringableMacro()
    {
        Stringable::macro('foreignKey', function () {
            return $this->value->basename()->lower()->append('_id');
        }); 
    }
}
