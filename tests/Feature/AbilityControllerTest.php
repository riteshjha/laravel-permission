<?php

namespace Rkj\Permission\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Rkj\Permission\Facades\Permission;
use Rkj\Permission\Models\Ability;

class AbilityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * Test Role listing
     *
     * @return void
     */
    public function testRoleList()
    {
        $url = route('permission.listRoles');

        $this->actingAs($this->sellerUser())
            ->get($url)
            ->assertStatus(403);

        $this->actingAs($this->superAdminUser())
            ->get($url)
            ->assertStatus(200)
            ->assertSee('Roles')
            ->assertSee('superadmin');
    }

    /**
     * Test Ability list
     *
     * @return void
     */
    public function testAbilityList()
    {
        $url = route('permission.listAbilities');

        $this->actingAs($this->sellerUser())
            ->get($url)
            ->assertStatus(403);
            
        $this->actingAs($this->superAdminUser())
            ->get($url)
            ->assertStatus(200)
            ->assertSee('Abilities')
            ->assertSee('permission.listAbilities');
    }

    /**
     * Test Ability list
     *
     * @return void
     */
    public function testRoleAbilityList()
    {
        $url = route('permission.roleAbilities', 10);

        $this->actingAs($this->sellerUser())
            ->get($url)
            ->assertStatus(403);
            
        $this->actingAs($this->superAdminUser())
            ->get($url)
            ->assertStatus(200)
            ->assertSee('Permissions')
            ->assertSee('Ability')
            ->assertSee('Group')
            ->assertSee('Permission');

        $url = route('permission.roleAbilities', 15);
        $this->actingAs($this->superAdminUser())
            ->get($url)
            ->assertStatus(404);
    }

    /**
     * Test record ability
     *
     * @return void
     */
    public function testRecordAbilities()
    {
        $url = route('permission.recordAbilities');

        $this->actingAs($this->sellerUser())
            ->get($url)
            ->assertStatus(403);
            
        $this->actingAs($this->superAdminUser())
            ->get($url)
            ->assertStatus(200);
    }

    public function testUpdateAbility()
    {
        $ability = factory(Ability::class)->create();

        $url = route('permission.updateAbility', $ability->id);

        $postData = ['name' => 'label', 'value' => 'Test label' ];

        $this->actingAs($this->sellerUser())
            ->post($url, $postData)
            ->assertStatus(403);

        $this->actingAs($this->superAdminUser())
            ->post($url, $postData)
            ->assertStatus(200);
    }

    public function testUpdateRoleAbility()
    {
        $ability = factory(Ability::class)->create();

        $postData = [
            'permissions' => [
                $ability->id => ['level' => 2]
            ]
        ];

        $url = route('permission.updateRoleAbility', 10);

        $this->actingAs($this->sellerUser())
            ->post($url, $postData)
            ->assertStatus(403);

        $this->actingAs($this->superAdminUser())
            ->post($url, $postData)
            ->assertStatus(200);
    }
}