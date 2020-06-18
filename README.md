# Laravel Permission (Account and Owner Level Check)
This package allows you to manage user permissions in a database using role and direct user based permission with Account (Organization) level and owner level permission check. It handle 2 types of abilities (route ability and field ability).

## Installation

1. Require it with Composer

    ```
    composer require riteshjha/laravel-permission    
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

3. All model that you want to include in permission should implements ``` Permissionable ``` interface and add ``` HasPermission ``` trait.

## Ability

Package handle 2 types of ability (route ability and field ability). Package parse Auth route (route which has auth middleware) and store route name as a Route Ability. For field ability, you have to define it in your model like this:

    class Project extends Model
    {
        protected $fieldAbilities = ['cost', 'estimated_cost'] ; //list projects table fields on which you want to apply permission
    }

You can use ``` Project::allowFieldAbilities(['cost']) ``` in your seeder to alow default field ability. For details check ``` PermissionSeeder ``` in tests.
    
## Role and Ability Group

Role and Ability is divided in 2 groups (SYSTEM and ACCOUNT). All admin users that mange admin tasks will under SYSTEM group role.
All users that signup or login as front end user will under ACCOUNT group. Similarly All ability (route name) which is used for admin
interface will be under SYSTEM group and all ability which is used for front-end will be under ACCOUNT group. To disable it change ``` disableAbilityGroup ```  to true in config.

## Sync Ability

    php artisan ability:record  // if need fresh then add --fresh

## Admin Interface

There is an admin interface with routes and views for handling ability and permissions. You have to add package routes in your admin route group.

    Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
        Permission::routes();
    });
        
Now you can access permission interface via ```admin/permission/roles``` routes

Note: If you use admin routes prefix other than 'admin' then change ```adminRoutePrefix``` value in ```config/permission.php```

## Usage

Package use laravel gate, so you can use ``` can('project.create') ``` in view and ``` $this->authorize('project.create') ``` in controller for route ability. And ``` can('projects::cost') ``` for field ability in view. Here projects is a table name.

For Details check <a href="https://github.com/riteshjha/laravel-permission/tree/master/tests">Tests</a>

#### Create/Update

When creating or updating record in model then filter data using ``` filterFieldAccess ``` method like :

    $data = Project::filterFieldAccess($data)
