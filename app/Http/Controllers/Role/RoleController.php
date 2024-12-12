<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/29/2017
 * Time: 3:23 PM
 */

namespace App\Http\Controllers\Role;

//hassan please rewrite after removing BaseCrud, you need to check with the angular team what api are needed only.

use App\Idea\Base\BaseController;
use App\Models\Idea\Role;
use App\Repositories\Role\PermissionRepository;
use App\Repositories\Role\RoleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    protected $permissions = [
        "index" => ["code" => "user_roles", "action" => "read"],
        "getAllRoles" => ["code" => "user_roles", "action" => "read"],
        "permissionsByRole" => ["code" => "user_roles", "action" => "read"],
        "destroy" => ["code" => "user_roles", "action" => "write"],
        "store" => ["code" => "user_roles", "action" => "write"],
        "update" => ["code" => "user_roles", "action" => "write"],
        "updateRole" => ["code" => "user_roles", "action" => "write"],
        "setRolePermissions" => ["code" => "user_roles", "action" => "write"],
    ];

    protected $roleRepository;
    protected $permissionRepository;

    /**
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository, Request $request)
    {
        parent::__construct($request);
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'updateRole' => [
                'role_id' => 'required',
            ],
            'permissionsByRole' => [
                'role_id' => 'required',
            ],
            'store' => [
                'slug' => 'required|unique:roles,slug',
            ],
        ];
    }

    /**
     * Validation Message
     */
    protected static function validationMessages()
    {
        return [
            'store' => [
                'slug.unique' => 'Please use a different role as it is already used',
            ],
        ];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new Role());
    }

    /**
     * Function to return all roles
     *
     * @return JsonResponse
     */
    public function getAllRoles()
    {
        return $this->successData($this->roleRepository->findAll());
    }

    /**
     * Function to return all permissions by role
     *
     * @return JsonResponse
     */
    public function permissionsByRole()
    {
        return $this->successData($this->permissionRepository->findAllPermissionsByRole());
    }

    /**
     * Function to return all permissions by user
     *
     * @return JsonResponse
     */
    public function permissionsByUser()
    {
        return $this->successData($this->permissionRepository->findAllPermissionsByUser());
    }

    /**
     * Function to set Permissions by role
     *
     * @string other fields
     * @param $roleId
     * @return JsonResponse
     */
    public function setRolePermissions($roleId)
    {
        return $this->success('idea::general.update_success', $this->roleRepository->saveRolePermission($roleId));
    }

    /**
     * Description: The following method will update new admin to the system
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success('idea::general.update_success', $this->roleRepository->updateRole($id));
    }
}
