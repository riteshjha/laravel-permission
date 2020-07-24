<?php

namespace Rkj\Permission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Rkj\Permission\Facades\Permission;

class RecordAbility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ability:record {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse and create ability from route name';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(!App::environment('testing')) echo "Processing Route ability... \n";

        if ($this->option('fresh')) $this->truncateAbility();

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            $middlewares = $route->gatherMiddleware();

            $group = (Str::of($route->getPrefix())->ltrim('/') == config('permission.adminPrefix')) 
                        ? Permission::GROUP_SYSTEM 
                        : Permission::GROUP_ACCOUNT;

            if (in_array(config('permission.authMiddleware'), $middlewares) && !empty($name)) {
                $this->updateOrCreateAbility($name, $group);
            }
        }

        if(!App::environment('testing')) echo "Processing Field ability... \n";

        $modelNamespace = config('permission.model.namespace');

        collect(glob(base_path($modelNamespace . '/*.php')))->map(function ($filename) use ($modelNamespace) {

            $modelWithNamespace = $modelNamespace . '\\' . basename($filename, '.php');
          
            if(in_array('Rkj\Permission\Contracts\Permissionable', class_implements($modelWithNamespace))){

                $fields = (new $modelWithNamespace)->fieldAvilities();

                foreach($fields as $field){
                    $group = Permission::GROUP_ACCOUNT;

                    $this->updateOrCreateAbility($field, $group);
                }
            }
        });

        if(!App::environment('testing')) echo "Done\n";
    }

    /**
     * Create ability
     *
     * @param string $name
     * @param int $group
     * @return void
     */
    protected function updateOrCreateAbility($name, $group)
    {
        Permission::abilityModel()::updateOrCreate(
            ['name' => $name], 
            [
                'name' => $name,
                'label' => Str::of($name)->snake()->replace(['.', ':', '_'], ' ')->title(),
                'group' => $group
            ]
        );
    }

    /**
     * Truncaste ability
     *
     * @return void
     */
    protected function truncateAbility()
    {
        Schema::disableForeignKeyConstraints();

        Permission::abilityModel()::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
