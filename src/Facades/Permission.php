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
     * User model name with namespace
     *
     * @return string
     */
    public static function userModel()
    {
        return config('permission.model.user');
    }

    /**
     * Role model name with namespace
     *
     * @return string
     */
    public static function roleModel()
    {
        return config('permission.model.role');
    }

    /**
     * Ability Model name with namespace
     *
     * @return string
     */
    public static function abilityModel()
    {
        return config('permission.model.ability');
    }

    /**
     * Account model name with namespace
     *
     * @return string
     */
    public static function accountModel()
    {
        return config('permission.model.account');
    }

    /**
     * Get super admin role
     *
     * @return string
     */
    public static function superAdminRole()
    {
        return config('permission.role.superAdmin');
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
        Route::get('permission/roles/{roleId}/abilities', $namespace .'AbilityController@roleAbilities')->name('permission.roleAbilities');
        Route::post('permission/roles/{roleId}/abilities', $namespace .'AbilityController@updateRoleAbility')->name('permission.updateRoleAbility');
        Route::get('/permission/abilities', $namespace .'AbilityController@index')->name('permission.listAbilities');
        Route::get('/permission/abilities/sync', $namespace .'AbilityController@sync')->name('permission.syncAbilities');
        Route::post('/permission/abilities/{id}', $namespace .'AbilityController@update')->name('permission.updateAbility');        
    }
}