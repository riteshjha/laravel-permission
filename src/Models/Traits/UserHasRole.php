<?php
namespace Rkj\Permission\Models\Traits;

use Illuminate\Support\Str;
use Rkj\Permission\Facades\Permission;

trait UserHasRole
{
    /**
     * A user may be assigned many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Permission::roleModel())->withTimestamps();
    }

    /**
     * Systems roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function systemRoles()
    {
        return $this->roles()->where('group', Permission::abilityModel()::GROUP_SYSTEM);
    }

    /**
     * Systems roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accountRoles()
    {
        return $this->roles()->where('group', Permission::abilityModel()::GROUP_ACCOUNT);
    }

    /**
     * Get all User abilities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function abilitables()
    {
        return $this->morphToMany(Permission::abilityModel(), 'abilitable')->withTimestamps()->withPivot('level');
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
            $role = Permission::roleModel()::whereName($role)->firstOrFail();
        }

        $this->roles()->sync($role, false);
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Permission::superAdminRole());
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isSystemUser()
    {
        return $this->roles->pluck('group')->contains(Permission::abilityModel()::GROUP_SYSTEM);
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isAccountUser()
    {
        return $this->roles->pluck('group')->contains(Permission::abilityModel()::GROUP_ACCOUNT);
    }

    /**
     * Fetch the user's abilities.
     *
     * @return Illuminate\Support\Collection
     */
    public function abilities()
    {
        $roleAbilities = $this->roles->map->abilitables->flatten();

        return $roleAbilities->merge($this->abilitables);
    }

    /**
     * Check user has access to ability
     *
     * @param string $ability
     * @return boolean
     */
    public function hasAccess($ability, $params)
    {
        $result = false;

        $model = null;

        $permission = $this->abilities()->where('name', $ability)->first();
        
        $level = ($permission) ? $permission->pivot->level : 0;

        if ($level > 0) { //$level > 0 means has permission

            $result = true;

            $model = (count($params) > 0) ? $params[0] : null;

            if (!$this->isSystemUser() && $model) {
                $result = $model->hasPermission($this, $ability, $level);
            }
        }

        return $this->afterAccess($result, $ability, $model);
    }

    /**
     * This method can be used to overwrite the permission result 
     *
     * @param boolean $result
     * @param string $ability
     * @param Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    protected function afterAccess($result, $ability, $model = null)
    {
        return $result;
    }
}