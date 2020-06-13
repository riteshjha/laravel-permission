<?php 

namespace Rkj\Permission\Tests;

use \Orchestra\Testbench\TestCase as FrameworkTestCase;
use Rkj\Permission\PermissionServiceProvider;
use Rkj\Permission\Tests\Models\User;

class TestCase extends FrameworkTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->seed(PermissionSeeder::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Permission' => Rkj\Permission\Facades\Permission::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('permission', [
            'level' => env('PERMISSION_LEVEL', 'owner'), 
            'disableAbilityGroup' => env('PERMISSION_DISABLE_ABILITY_GROUP', false),
            'model' => [      
                'namespace' => env('PERMISSION_MODEL_NAMESPACE','App'),
                'user' => env('PERMISSION_MODEL_USER','User'),
                'role' => env('PERMISSION_MODEL_ROLE','Role'),
                'ability' => env('PERMISSION_MODEL_ABILITY', 'Rkj\Permission\Models\Ability'),
                'account' => env('PERMISSION_MODEL_ACCOUNT','Account'),
            ],
            'role' => [
                'superAdmin' => env('PERMISSION_ROLE_SUPPERADMIN','superadmin'),
            ],
            'adminPrefix' => env('PERMISSION_ADMIN_ROUTE_PREFIX','admin'),
            'itemPerPage' => env('PERMISSION_ITEM_PERPAGE',15),
        ]);
    }


    //User defained methods

    protected function createUser()
    {
        return factory(User::class)->create();
    }

    protected function sellerUser()
    {
        return $this->createUser()->assignRole('seller');        
    }

    protected function superAdminUser()
    {
        return $this->createUser()->assignRole('superadmin');          
    }

    protected function loadRoutesFrom($path)
    {
        require $path;    
    }
}