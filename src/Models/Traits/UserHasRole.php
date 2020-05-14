<?php
namespace Rkj\Permission\Models\Traits;

use Illuminate\Support\Arr;
use Rkj\Permission\Models\Ability;

trait UserHasRole
{
    /**
     * A user may be assigned many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('permission.model.role'))->withTimestamps();
    }

    /**
     * Systems roles
     *
     * @return void
     */
    public function systemRoles()
    {
        return $this->roles()->where('group', Ability::GROUP_SYSTEM);
    }

    /**
     * Systems roles
     *
     * @return void
     */
    public function accountRoles()
    {
        return $this->roles()->where('group', Ability::GROUP_ACCOUNT);
    }

    /**
     * Check user has specific role
     *
     * @param mix $role
     * @return boolean
     */
    public function hasRole($role, $strict = false)
    {
        $roles = $this->roles()->pluck('name');

        if (is_object($role)) {
            $role = $role->name;
        }else if(is_string($role)){
            $role = explode(',', $role);
        }

        $found = $roles->intersect($role)->count();

        return ($strict) ? $found == count($role) : $found > 0;
    }

    /**
     * Assign a new role to the user.
     *
     * @param  mixed  $role
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = config('permission.model.role')::whereName($role)->firstOrFail();
        }

        $this->roles()->sync($role, false);
    }
}