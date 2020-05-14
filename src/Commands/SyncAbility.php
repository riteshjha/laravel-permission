<?php

namespace Rkj\Permission\Commands;

use Rkj\Permission\Models\Ability;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SyncAbility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ability:parse {--fresh}';

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

            $group = (Str::of($route->getPrefix())->ltrim('/') == 'admin') 
                        ? Ability::GROUP_SYSTEM 
                        : Ability::GROUP_ACCOUNT;

            if (in_array('auth', $middlewares) && !empty($name)) {
                
                Ability::updateOrCreate(
                    ['name' => $name], 
                    [
                        'name' => $name,
                        'label' => Str::of($name)->replace(['.', ':'], ' ')->title(),
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

        Ability::truncate();

        Schema::enableForeignKeyConstraints();
    }
}
