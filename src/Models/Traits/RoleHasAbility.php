<?php
namespace Rkj\Permission\Models\Traits;

trait RoleHasAbility
{
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

}