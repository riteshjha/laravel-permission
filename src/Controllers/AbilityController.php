<?php

namespace Rkj\Permission\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

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
            'items' => config('permission.model.role')::with('users')->paginate(config('permission.itemPerPage')),
            'roleGroups' => config('permission.model.ability')::roleGroups()
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
            'items' => config('permission.model.ability')::with('roles')->paginate(config('permission.itemPerPage')),
            'roleGroups' => config('permission.model.ability')::roleGroups()
        ];

        return view('permission::abilities', $data);
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

        config('permission.model.ability')::update($params, $id);

        return response()->json();
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

        $role = config('permission.model.role')::findOrFail($roleId);

        $data = [
            'items' => $role->abilitables()->paginate($perPage),
            'roleGroups' => config('permission.model.ability')::roleGroups(),
            'permissionLevels' => config('permission.model.ability')::permissionLevels(),
            'roles' => config('permission.model.role')::noSuperAdmin()->get(),
            'selectedRole' => $role
        ];

        return view('permission::index', $data);
    }

    /**
     * Update role permission
     */
    public function updateRoleAbility($roleId)
    {
        $role = config('permission.model.role')::findOrFail($roleId);

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
