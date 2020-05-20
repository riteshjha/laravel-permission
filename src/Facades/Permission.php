<?php

namespace Rkj\Permission\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

class Permission extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'permission';
    }

    /**
     * Permission routes
     *
     * @return void
     */
    public static function adminRoutes()
    {
        $namespace = '\Rkj\Permission\Controllers\\';

        Route::get('permission/roles', $namespace .'AbilityController@roles')->name('permission.listRoles');
        Route::get('permission/roles/{role}/abilities', $namespace .'AbilityController@roleAbilities')->name('permission.roleAbilities');
        Route::post('permission/roles/{role}/abilities', $namespace .'AbilityController@updateRoleAbility')->name('permission.updateRoleAbility');
        Route::get('/permission/abilities', $namespace .'AbilityController@index')->name('permission.listAbilities');
        Route::get('/permission/abilities/sync', $namespace .'AbilityController@sync')->name('permission.syncAbilities');
        Route::post('/permission/abilities/{ability}', $namespace .'AbilityController@update')->name('permission.updateAbility');        
    }
}