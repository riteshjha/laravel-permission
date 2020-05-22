<?php

namespace Rkj\Permission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Rkj\Permission\Facades\Permission;

class SyncAbility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ability:sync {--fresh}';

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
        echo "Processing ability \n";

        if ($this->option('fresh')) $this->truncateAbility();

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            $middlewares = $route->gatherMiddleware();

            $group = (Str::of($route->getPrefix())->ltrim('/') == config('permission.adminPrefix')) 
                        ? config('permission.model.ability')::GROUP_SYSTEM 
                        : config('permission.model.ability')::GROUP_ACCOUNT;

            if (in_array('auth', $middlewares) && !empty($name)) {
                
                Permission::abilityModel()::updateOrCreate(
                    ['name' => $name], 
                    [
                        'name' => $name,
                        'label' => Str::of($name)->snake()->replace(['.', ':', '_'], ' ')->title(),
                        'group' => $group
                    ]
                );
            }
        }

        echo "Done\n";
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
