<?php

namespace Rkj\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Rkj\Permission\Contracts\Permissionable;
use Rkj\Permission\Facades\Permission;
use Rkj\Permission\Models\Traits\HasPermission;

class Ability extends Model implements Permissionable
{
    use HasPermission;

    protected $fillable = ['name', 'label', 'group'];

    /**
     * Get all of the roles that are assigned this ability.
     */
    public function roles()
    {
        return $this->morphedByMany(Permission::roleModel(), 'abilitable')->withTimestamps()->withPivot('level');
    }

    /**
     * Get all of the users that are assigned this ability.
     */
    public function users()
    {
        return $this->morphedByMany(Permission::userModel(), 'abilitable')->withTimestamps()->withPivot('level');
    }

    /**
     * Check is system group ability
     *
     * @return boolean
     */
    public function isSystemGroup()
    {
        return $this->group == Permission::GROUP_SYSTEM;
    }

    /**
     * Check is system group ability
     *
     * @return boolean
     */
    public function isAccountGroup()
    {
        return $this->group == Permission::GROUP_ACCOUNT;
    }

    /**
     * Check the ability is field ability
     *
     * @return boolean
     */
    public function isFieldAbility()
    {
        return Str::of($this->name)->contains(':');
    }

    /**
     * role groups
     *
     * @return void
     */
    public static function roleGroups()
    {
        return [Permission::GROUP_SYSTEM => 'System', Permission::GROUP_ACCOUNT => 'Account'];
    }

    
}
