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
    
}