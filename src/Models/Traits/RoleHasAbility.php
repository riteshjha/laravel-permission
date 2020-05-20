<?php
namespace Rkj\Permission\Models\Traits;

trait RoleHasAbility
{
    /**
     * A role may be assigned many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('permission.model.user'))->withTimestamps();
    }

    /**
     * Get all Role abilities
     *
     * @return void
     */
    public function abilitables()
    {
        return $this->morphToMany(config('permission.model.ability'), 'abilitable')->withTimestamps()->withPivot('level');
    }

    /**
     * No SuperAdmin scope filter
     *
     * @param [type] $query
     * @return void
     */
    public function scopeNoSuperAdmin($query)
    {
        $query->where('name', '<>', config('permission.role.superAdmin'));
    }

    /**
     * Check is super admin role
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->name == config('permission.role.superAdmin');
    }

}