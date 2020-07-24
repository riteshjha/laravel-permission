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
        $permission = require __DIR__ . '/../config/permission.php';

        $app['config']->set('permission', $permission);
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