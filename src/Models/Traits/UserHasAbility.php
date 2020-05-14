<?php
namespace Rkj\Permission\Models\Traits;

use Rkj\Permission\Models\Ability;

trait UserHasAbility
{
    /**
     * Fetch the user's abilities.
     *
     * @return array
     */
    public function abilities()
    {
        $roleAbilities = $this->roles
            ->map->abilitables
            ->flatten();

        $abilities = $roleAbilities->merge($this->abilitables);

        return $abilities;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(config('permission.superAdmin'));
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isSystemUser()
    {
        return $this->roles->pluck('group')->contains(Ability::GROUP_SYSTEM);
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isAccountUser()
    {
        return $this->roles->pluck('group')->contains(Ability::GROUP_ACCOUNT);
    }

    /**
     * Check user has access to ability
     *
     * @param string $ability
     * @return boolean
     */
    public function hasAccess($ability, $params)
    {
        $permission = $this->abilities()->where('name', $ability)->first();

        $level = ($permission) ? $permission->pivot->level : 0;

        if ($level > 0) { //$level > 0 means has permission

            $model = (count($params) > 0) ? $params[0] : null;

            if (config('permission.level') == 'account' && $this->isAccountUser() && $model) {
                return $this->accountLevelPermission($model, $level);
            }else if($this->isAccountUser() && $model){
                return $this->isOwner($model);
            }

            return true;
        }

        return false;
    }

    /**
     * Check Acount level permission
     *
     * @param App/Models/Model $model
     * @param int $level
     * @return void
     */
    protected function accountLevelPermission($model, $level)
    {
        return $level == Ability::LEVEL_ACCOUNT
            ? $this->isAccountOwner($model)
            : $this->isOwner($model);
    }

    /**
     * Check user is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isOwner($model){
        return $model->user_id = $this->id;
    }

    /**
     * Check user's account is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isAccountOwner($model){
        return $model->owner->account_id = $this->account_id;
    }
}