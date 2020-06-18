<?php

namespace Rkj\Permission\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Permission extends Facade
{
    public const LEVEL_OWNER = 1;
    public const LEVEL_ACCOUNT = 2;

    public const GROUP_SYSTEM = 1;
    public const GROUP_ACCOUNT = 2;

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
        return config('permission.model.namespace') . '\\' . config('permission.model.user');
    }

    /**
     * Role model name with namespace
     *
     * @return string
     */
    public static function roleModel()
    {
        return config('permission.model.namespace') . '\\' . config('permission.model.role');
    }

    /**
     * Ability Model name with namespace
     *
     * @return string
     */
    public static function abilityModel()
    {
        $abilityModelName = config('permission.model.ability');

        return  Str::of($abilityModelName)->contains('Rkj')
                ? $abilityModelName
                : config('permission.model.namespace') . '\\' . $abilityModelName;
    }

    /**
     * Account model name with namespace
     *
     * @return string
     */
    public static function accountModel()
    {
        return config('permission.model.namespace') . '\\' . config('permission.model.account');
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
     * permission levels
     *
     * @return void
     */
    public static function levels()
    {
        $levels = collect([static::LEVEL_OWNER => 'Owner', static::LEVEL_ACCOUNT => 'Account']);

        return config('permission.level') == 'account'
                ?  $levels->all()
                :  $levels->except(static::LEVEL_ACCOUNT)->all();
    }

    /**
     * Permission routes
     *
     * @return void
     */
    public static function routes()
    {
        $namespace = '\Rkj\Permission\Controllers\\';

        Route::get('permission/roles', $namespace .'AbilityController@roles')->name('permission.listRoles');
        
        Route::get('permission/abilities', $namespace .'AbilityController@index')->name('permission.listAbilities');
        Route::get('permission/abilities/record', $namespace .'AbilityController@record')->name('permission.recordAbilities');
        Route::post('permission/abilities/{id}', $namespace .'AbilityController@update')->name('permission.updateAbility'); 

        Route::get('permission/roles/{roleId}/abilities', $namespace .'AbilityController@roleAbilities')->name('permission.roleAbilities');
        Route::post('permission/roles/{roleId}/abilities', $namespace .'AbilityController@updateRoleAbility')->name('permission.updateRoleAbility');
    }
}