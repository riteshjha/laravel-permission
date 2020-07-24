<?php

return [

    // set permission level ('account' or 'owner') when you select account level then be sure you have account model 
    'level' => env('PERMISSION_LEVEL', 'owner'), 

    'disableAbilityGroup' => env('PERMISSION_DISABLE_ABILITY_GROUP', false),

    'model' => [      
        'namespace' => env('PERMISSION_MODEL_NAMESPACE','App'),

        'user' => env('PERMISSION_MODEL_USER','User'), //user model

        'role' => env('PERMISSION_MODEL_ROLE','Role'), //role model

        'ability' => env('PERMISSION_MODEL_ABILITY', 'Rkj\Permission\Models\Ability'), //Ability model that contain abilities

        'account' => env('PERMISSION_MODEL_ACCOUNT','Account'), //account model, if using account level check
    ],

    'role' => [
        'superAdmin' => env('PERMISSION_ROLE_SUPPERADMIN','superadmin'), //specify role name which has all permissions
    ],

    'adminRoutePrefix' => env('PERMISSION_ADMIN_ROUTE_PREFIX','admin'), //prefix used for admin routes

    'itemPerPage' => env('PERMISSION_ITEM_PERPAGE',15),

    'authMiddleware' => env('PERMISSION_AUTH_MIDDLEWARE','auth')
];