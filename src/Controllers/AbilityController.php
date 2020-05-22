<?php

namespace Rkj\Permission\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Rkj\Permission\Facades\Permission;

class AbilityController extends Controller
{
    /**
     * Sync Ability
     *
     * @return void
     */
    public function sync()
    {
        Artisan::call('ability:sync');

        return '';
    }

    /**
     * Fetch all roles
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function roles()
    {
        $data = [
            'items' => Permission::roleModel()::with('users')->paginate(config('permission.itemPerPage')),
            'roleGroups' => Permission::abilityModel()::roleGroups()
        ];

        return view('permission::roles', $data);
    }

    /**
     * List users
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function index()
    {
        $data = [
            'items' => Permission::abilityModel()::with('roles')->paginate(config('permission.itemPerPage')),
            'roleGroups' => Permission::abilityModel()::roleGroups()
        ];

        return view('permission::abilities', $data);
    }

    /**
     * List permission based on roles
     *
     * @param [type] $roleId
     * @return void
     */
    public function roleAbilities($roleId)
    {
        $perPage = request('perPage', config('permission.itemPerPage'));

        $role = Permission::roleModel()::findOrFail($roleId);

        $abilities = Permission::abilityModel()::with(['roles' => function ($query) use ($roleId) {
            $query->where('id', $roleId);
        }])->where('group', $role->group)->paginate($perPage);

        $data = [
            'items' => $abilities,
            'roleGroups' => Permission::abilityModel()::roleGroups(),
            'permissionLevels' => Permission::abilityModel()::permissionLevels(),
            'roles' => Permission::roleModel()::noSuperAdmin()->get(),
            'selectedRole' => $role
        ];

        return view('permission::permissions', $data);
    }

    /**
     * Update ability partials
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $params[request('name')] = request('value');

        Permission::abilityModel()::update($params, $id);

        return response()->json();
    }

    /**
     * Update role permission
     */
    public function updateRoleAbility($roleId)
    {
        $role = Permission::roleModel()::noSuperAdmin()->findOrFail($roleId);

        $data = [
            'abilities' => array_keys(request('permissions')),
            'permissions' => array_values(request('permissions'))
        ];

        $validator = $this->validatePermission($data);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $role->abilitables()->sync(request('permissions'), false);

        return response()->json();
    }

    /**
     * Validate permission rule
     *
     * @param array $data
     * @return void
     */
    public function validatePermission($data)
    {
        return Validator::make($data, [
            "abilities"    => "required|array",
            'abilities.*' => 'required|exists:abilities,id',
            "permissions"    => "required|array",
            'permissions.*.level' => 'required|in:0,1,2',
        ]);
    }
}
