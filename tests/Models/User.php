<?php

namespace Rkj\Permission\Tests\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rkj\Permission\Contracts\Permissionable;
use Rkj\Permission\Models\Traits\HasPermission;
use Rkj\Permission\Models\Traits\UserHasRole;

class User extends Authenticatable implements Permissionable
{
    use Notifiable, UserHasRole, HasPermission;

    protected $fieldAbilities = ['remember_token'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
