<?php

return [

    'model' =>[
        'user' => "App/User", //user model namespace

        'role' => "App/Role", //role model namespace

        'ability' => "Rkj/Models/Ability" //Ability model that contain abilities
    ],

    'superAdmin' => 'superadmin', //specify super admin role. this role has all permissions

    


];