<?php

namespace Rkj\Permission\Tests\Models;
namespace Rkj\Permission\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Rkj\Permission\Contracts\Permissionable;
use Rkj\Permission\Models\Traits\HasPermission;
use Rkj\Permission\Models\Traits\RoleHasAbility;

class Role extends Model implements Permissionable
{
    use RoleHasAbility, HasPermission;
}
