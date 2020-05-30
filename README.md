# Laravel Permission (Account and Owner Level Check)
This package allows you to manage user permissions in a database using role and direct user based permission with Account (Organization) level and owner level permission check.

## Installation

1. Update composer.json and add a repository:

    ```
    "repositories" : [
            {
                "type": "vcs",
                "url": "https://github.com/riteshjha/laravel-permission.git"   
            }
        ],
    
    "require": {
            "riteshjha/permission": "dev-master"
    }
    ```
2. Run Composer: ``` composer update ```

3. Optional: The service provider and alias will automatically get registered. Or you may manually add the service provider in your config/app.php file:

    ```
    'providers' => [
        // ...
        Rkj\Permission\PermissionServiceProvider::class,
    ];

    'aliases' => [
        //...
        'Permission' => Rkj\Permission\Facades\Permission::class
    ];
    ```
4. You can publish the migrations, views, assets and the config file with:

    ```
    php artisan vendor:publish --provider="Rkj\Permission\PermissionServiceProvider"
    ```
5. This package publishes a ```config/permission.php``` file. Check this file and change accourding to your needs for different (Ability, Role and User) model name and other config options.

6. Run migrations: ``` php artisan migrate ```

## Configuration

1. First configure models namesapce in ```config/permission.php```

2. Add ```UserHasRole``` trait to your User model and ```RoleHasAbility``` to Role model.

3. Each model in your application must have ```owner``` relationship except User and Role model

    ```
    public function owner(){
        return $this->belongsTo(User::class); //assuming user_id as a foreign key, if not add second parameter in relation
    }
    ```

    In Account model there should be a hasOne relation or it depends on your application organization handling

    ```
    public function owner(){
        return $this->hasOne(User::class)->oldest(); //assuming account_id is the foreign key in user table and the oldest user is the owner of an account
    }
    ```

## Admin Interface

There is an admin interface with routes and views for handling ability and permissions. You have to add package routes in your admin route group.

```
    Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
        Permission::routes();
    });
```

Now you can access permission interface via ```admin/permission/roles``` routes

Note: If you use admin routes prefix other than 'admin' then change ```adminPrefix``` value in ```config/permission.php```


