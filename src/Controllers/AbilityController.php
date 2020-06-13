<?php

namespace Rkj\Permission\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Rkj\Permission\Facades\Permission;

class AbilityController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $searchKey = 'search';

    protected $adminRoutePrefix = '';

    /**
     * Construct base controller
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            View::share('searchKey', $this->searchKey);

            return $next($request);
        });

        $this->adminRoutePrefix = config('permission.adminRoutePrefix');
    }

    /**
     * Sync Ability
     *
     * @return void
     */
    public function sync()
    {
        $this->authorize($this->adminRoutePrefix . '.permission.syncAbilities');

        Artisan::call('ability:record');

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
        $this->authorize($this->adminRoutePrefix . '.permission.listRoles');

        $data = [
            'items' => Permission::roleModel()::with('users')->paginate(config('permission.itemPerPage')),
            'roleGroups' => Permission::abilityModel()::roleGroups()
        ];

        return view('permission::roles', $data);
    }

    /**
     * List abilities
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function index()
    {
        $this->authorize($this->adminRoutePrefix . '.permission.listAbilities');

        $query = Permission::abilityModel()::with('roles');

        $searchKey = request($this->searchKey, false);

        if($searchKey){
            $query->search(['name', 'label'], $searchKey);
        }

        $data = [
            'items' => $query->paginate(config('permission.itemPerPage')),
            'roleGroups' => Permission::abilityModel()::roleGroups()
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
        $this->authorize($this->adminRoutePrefix . '.permission.updateAbility');

        $params[request('name')] = request('value');

        Permission::abilityModel()::where('id', $id)->update($params);

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
        $this->authorize($this->adminRoutePrefix . '.permission.roleAbilities');

        $role = Permission::roleModel()::findOrFail($roleId);

        $data = [
            'items' => $this->fetchRoleAbilities($role),
            'roleGroups' => Permission::abilityModel()::roleGroups(),
            'permissionLevels' => Permission::abilityModel()::permissionLevels(),
            'roles' => Permission::roleModel()::noSuperAdmin()->get(),
            'selectedRole' => $role
        ];

        return view('permission::permissions', $data);
    }

    /**
     * Update role permission
     * 
     * @param int $roleId
     */
    public function updateRoleAbility($roleId)
    {
        $this->authorize($this->adminRoutePrefix . '.permission.updateRoleAbility');

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

    /**
     * Fetch role abilities
     *
     * @param Illuminate\Database\Eloquent\Model $role
     * @return void
     */
    protected function fetchRoleAbilities($role)
    {
        $searchKey = request($this->searchKey, false);

        $perPage = request('perPage', config('permission.itemPerPage'));

        $query = Permission::abilityModel()::with(['roles' => function ($query) use ($role) {
            $query->where('id', $role->id);
        }]);
        
        if($searchKey){
            $query->search(['name', 'label'], $searchKey);
        }

        return  config('permission.disableAbilityGroup') 
                ? $query->paginate($perPage)
                : $query->where('group', $role->group)->paginate($perPage);
        
    }
}
