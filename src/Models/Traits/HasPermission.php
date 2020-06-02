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
     * @return /Illuminate/Eloquent/Database/Model
     */
    public function owner()
    {
        $userId = Str::of(Permission::userModel())->append('_id');

        $accountId = Str::of(Permission::accountModel())->append('_id');

        if($this->hasColumn($userId)){
            return $this->belongsTo(Permission::userModel(), $userId);
        }elseif(get_class($this) == Permission::accountModel()) {
            return $this->hasOne(Permission::userModel(), $accountId);
        }else{
            return null;
        }
    }

    /**
     * Get field abilities of this model
     *
     * @return array
     */
    public function fieldAvilities()
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

        $abilities = $model->fieldAvilities();

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
        return Schema::hasColumn(static::table(), $column);
    }
}