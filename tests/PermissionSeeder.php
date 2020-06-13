<?php
namespace Rkj\Permission\Tests;

use Rkj\Permission\Tests\Models\Role;
use Rkj\Permission\Tests\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rkj\Permission\Models\Ability;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => '1', 'name' => 'superadmin', 'label' => 'Super Admin', 'group' => Role::GROUP_SYSTEM, 'created_at' => now(), 'updated_at' => now()],
            ['id' => '2', 'name' => 'admin', 'label' => 'Admin', 'group' => Role::GROUP_SYSTEM, 'created_at' => now(), 'updated_at' => now()],
            ['id' => '10', 'name' => 'seller', 'label' => 'Seller', 'group' => Role::GROUP_ACCOUNT, 'created_at' => now(), 'updated_at' => now()],
            ['id' => '11', 'name' => 'manager', 'label' => 'Manager', 'group' => Role::GROUP_ACCOUNT, 'created_at' => now(), 'updated_at' => now()],
            ['id' => '20', 'name' => 'vendor', 'label' => 'Vendor', 'group' => Role::GROUP_ACCOUNT, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);

        $this->createSuperUser();

        $this->recordAbility();

        $this->defaultRoleAbilityPermission();
    }

    /**
     * Create super user
     *
     * @return void
     */
    protected function createSuperUser()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'rkj@hello.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        $user->assignRole('superadmin');
    }

    /**
     * Record ability based on route name. 
     *
     * @return void
     */
    protected function recordAbility()
    {
        Artisan::call('ability:record --fresh');
    }

    /**
     * Default role ability permission
     *
     * @return void
     */
    protected function defaultRoleAbilityPermission()
    {
        $permissions = [
            'seller' => $this->sellerDefaultAbility(),

            'manager' => $this->managerDefaultAbility(),
        ];

        //dd($permissions);

        collect($permissions)->map(function ($values, $roleName) {
            $this->assignRoleAbility(collect($values['abilities'])->flatten(1)->all(), $values['level'], $roleName);
        });
    }

    /**
     * Contractor Default Ability
     *
     * @return array
     */
    protected function sellerDefaultAbility()
    {
        return [
            'abilities' => [
                'user.info',
                'user.profile'
            ],
            'level' => Ability::LEVEL_ACCOUNT
        ];
    }   
    
    /**
     * Contractor Default Ability
     *
     * @return array
     */
    protected function managerDefaultAbility()
    {
        return [
            'abilities' => [
                'user.info',
                'user.profile'
            ],
            'level' => Ability::LEVEL_OWNER
        ];
    }   

    /**
     * Assign Role Abilities
     *
     * @param [type] $abilities
     * @param [type] $roleName
     * @return void
     */
    protected function assignRoleAbility($abilities, $level, $roleName)
    {
        $abilityIds = Ability::whereIn('name', $abilities)->pluck('id');

        $ids = $abilityIds->mapWithKeys(function ($value) use ($level) {
            return [$value => ['level' => $level]];
        });

        $role = Role::whereName($roleName)->first();

        $role->abilitables()->sync($ids, false);
    }
    
}
