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
    ]
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
