<?php

namespace Rkj\Permission\Contracts;

interface Permissionable
{
    /**
     * Should be either belongsTo or hasOne relation to User model
     *
     * @return Model
     */
    public function owner();


    /**
     * List all field abilities for this model
     *
     * @return array
     */
    public function fieldAbilities();

    /**
     * Handle model level permision
     *
     * @param User $authUser
     * @param string $ability
     * @param int $level
     * @return void
     */
    public function hasPermission($authUser, $ability, $level);
}