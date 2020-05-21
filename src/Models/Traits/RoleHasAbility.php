<?php
namespace Rkj\Permission\Models\Traits;

use Rkj\Permission\Facades\Permission;

trait RoleHasAbility
{
    /**
     * A role may be assigned many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Permission::userModel())->withTimestamps();
    }

    /**
     * Get all Role abilities
     *
     * @return void
     */
    public function abilitables()
    {
        return $this->morphToMany(Permission::abilityModel(), 'abilitable')->withTimestamps()->withPivot('level');
    }

    /**
     * No SuperAdmin scope filter
     *
     * @param [type] $query
     * @return void
     */
    public function scopeNoSuperAdmin($query)
    {
        $query->where('name', '<>', Permission::superAdminRole());
    }

    /**
     * Check is super admin role
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->name == Permission::superAdminRole();
    }

}