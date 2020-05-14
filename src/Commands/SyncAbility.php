<?php

namespace Rkj\Permission\Commands;

use Rkj\Permission\Models\Ability;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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
            $middlewares = $route->gatherMiddleware();

            if (in_array('auth', $middlewares)) {
                Ability::updateOrCreate(['name' => $route->getName()]);
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
