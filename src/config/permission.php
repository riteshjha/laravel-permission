<?php

return [

    // set permission level ('account' or 'owner') when you select account level then be sure you have account model 
    'level' => 'owner', 

    'disableAbilityGroup' => false,

    'model' => [      
        'namespace' => 'App',

        'user' => "User", //user model

        'role' => "Role", //role model

        'ability' => "Rkj\Permission\Models\Ability", //Ability model that contain abilities

        'account' => "Account"  //account model, if using account level check
    ],

    'role' => [
        'superAdmin' => 'superadmin', //specify role name which has all permissions
    ],

    'adminPrefix' => 'admin', //prefix used for admin routes

    'itemPerPage' => 15
];