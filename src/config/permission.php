<?php

return [

    'level' => 'account', // set permission level ('account' or 'owner') 

    'model' => [
        'account' => "App\Account", //account model namespace, if using account level check

        'user' => "App\User", //user model namespace

        'role' => "App\Role", //role model namespace

        'ability' => "Rkj\Permission\Models\Ability" //Ability model that contain abilities
    ],

    'role' => [
        'superAdmin' => 'superadmin', //specify role name which has all permissions
    ],

    'cacheKey' => 'permission_cache',

    'itemPerPage' => 15
];