<?php
namespace Rkj\Permission\Models\Traits;

use Illuminate\Support\Str;

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
        return $this->roles()->where('group', config('permission.model.ability')::GROUP_SYSTEM);
    }

    /**
     * Systems roles
     *
     * @return void
     */
    public function accountRoles()
    {
        return $this->roles()->where('group', config('permission.model.ability')::GROUP_ACCOUNT);
    }

    /**
     * Get all User abilities
     *
     * @return void
     */
    public function abilitables()
    {
        return $this->morphToMany(config('permission.model.ability'), 'abilitable')->withTimestamps()->withPivot('level');
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

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(config('permission.role.superAdmin'));
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isSystemUser()
    {
        return $this->roles->pluck('group')->contains(config('permission.model.ability')::GROUP_SYSTEM);
    }

    /**
     * Check user is at system level
     *
     * @return boolean
     */
    public function isAccountUser()
    {
        return $this->roles->pluck('group')->contains(config('permission.model.ability')::GROUP_ACCOUNT);
    }

    /**
     * Fetch the user's abilities.
     *
     * @return array
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

            $model = (count($params) > 0) ? $params[0] : null;

            if (config('permission.level') == 'account' && $this->isAccountUser() && $model) {
                $result = $this->accountLevelPermission($model, $level);
            }else if($this->isAccountUser() && $model){
                $result = $this->isOwner($model);
            }

            $result = true;
        }

        return $this->afterAccess($result, $ability, $model);
    }

    /**
     * This method can be used to overwrite the permission result 
     *
     * @param boolean $result
     * @param string $ability
     * @param App\Models\Model $model
     * @return void
     */
    protected function afterAccess($result, $ability, $model = null)
    {
        return $result;
    }

    /**
     * Check Acount level permission
     *
     * @param App\Models\Model $model
     * @param int $level
     * @return void
     */
    protected function accountLevelPermission($model, $level)
    {
        return $level == config('permission.model.ability')::LEVEL_ACCOUNT
            ? $this->isAccountOwner($model)
            : $this->isOwner($model);
    }

    /**
     * Check user is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isOwner($model){
        $user_id = Str::of(config('permission.model.user'))->append('_id');

        return $model->{$user_id} = $this->id;
    }

    /**
     * Check user's account is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isAccountOwner($model){
        $account_id = Str::of(config('permission.model.account'))->append('_id');

        return $model->owner->{$account_id} = $this->{$account_id};
    }
}