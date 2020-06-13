<?php

namespace Rkj\Permission\Tests\Models;
namespace Rkj\Permission\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Rkj\Permission\Models\Traits\RoleHasAbility;

class Account extends Model
{
    use RoleHasAbility;
}
