<?php

return [

    // set permission level ('account' or 'owner') when you select account level then be sure you have account model 
    'level' => 'owner', 

    'model' => [
        'user' => "App\User", //user model namespace

        'role' => "App\Role", //role model namespace

        'ability' => "Rkj\Permission\Models\Ability", //Ability model that contain abilities

        'account' => "App\Account"  //account model namespace, if using account level check
    ],

    'role' => [
        'superAdmin' => 'superadmin', //specify role name which has all permissions
    ],

    'adminPrefix' => 'admin',

    'itemPerPage' => 15
];