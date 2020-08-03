<?php

namespace Rkj\Permission\Models\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Rkj\Permission\Facades\Permission;

trait HasPermission
{
    /**
     * Owner relation of this model 
     *
     * @return mix
     */
    public function owner()
    {
        $userId = Str::of(Permission::userModel())->foreignKey();

        if($this->hasColumn($userId)){
            return $this->belongsTo(Permission::userModel(), $userId);
        }elseif(get_class($this) == Permission::accountModel()) {
            $accountId = Str::of(Permission::accountModel())->foreignKey();
            return $this->hasOne(Permission::userModel(), $accountId);
        }else{
            return null;
        }
    }

    /**
     * Default model level permision
     *
     * @param User $authUser
     * @param string $ability
     * @param int $level
     * @return boolean
     */
    public function hasPermission($authUser, $ability, $level)
    {
        $result = false;

        if (config('permission.level') == 'account') {
            $result = $this->accountLevelPermission($authUser, $ability, $level);
        }else {
            $result = $this->isOwner($authUser, $ability, $level);
        }

        return $result;
    }

    /**
     * Get field abilities of this model
     *
     * @return array
     */
    public function fieldAbilities()
    {
        return collect($this->fieldAbilities)
                ->transform(function($field){
                    return $this->constructFieldAbility($field);
                })->toArray();
    }

    /**
     * Filter field access data
     *
     * @param array $data
     * @return array
     */
    public static function filterFieldAccess($data)
    {
        $model = new static;

        $abilities = $model->fieldAbilities();

        if(count($abilities) <= 0) return $data;

        $authUser = Auth::user();

        foreach ($data as $field => $value) {
            $fieldAbility = $model->constructFieldAbility($field);

            if (in_array($fieldAbility, $abilities) && !$authUser->can($fieldAbility)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Allow field abilities. Use it in seeder to allow field ability
     *
     * @param array|string $abilities
     * @return array
     */
    public static function allowFieldAbilities($abilities)
    {
        $arrAbilities = is_string($abilities) ? explode(',', $abilities)  : $abilities; 

        $obj = new static;

        return collect($arrAbilities)->transform(function($field) use ($obj) {
            return $obj->constructFieldAbility($field);
        })->toArray();
        
    }

    /**
     * Construct field ability
     *
     * @param string $field
     * @return string
     */
    protected function constructFieldAbility($field)
    {
        return $this->table() . ':' . $field;
    }

    /**
     * @return string
     */
    protected function table()
    {
        return with(new static)->getTable();
    }

    /**
     * Check table has column
     *
     * @param string $column
     * @return boolean
     */
    protected function hasColumn($column)
    {
        return Schema::hasColumn($this->table(), $column);
    }

    /**
     * Check Acount level permission
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param int $level
     * @return boolean
     */
    protected function accountLevelPermission($authUser, $ability, $level)
    {
        return $level == Permission::LEVEL_ACCOUNT
            ? $this->isAccountOwner($authUser)
            : $this->isOwner($authUser);
    }

    /**
     * Check user is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isOwner($authUser)
    {
        $userId = Str::of(Permission::userModel())->foreignKey();

        return $this->{$userId} = $authUser->id;
    }

    /**
     * Check user's account is an owner of model
     * @param Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    protected function isAccountOwner($authUser)
    {
        if(method_exists($this, 'owner')) $this->load('owner');

        $accountId = Str::of(Permission::accountModel())->foreignKey();

        return $this->owner->{$accountId} = $authUser->{$accountId};
    }
}