<?php


namespace App\Repositories\Role;

use App\Models\Idea\Action;
use App\Models\Idea\Permission;
use App\Models\Idea\Role;
use App\Models\Idea\RolePermission;
use App\Idea\Base\BasePaging;
use App\Idea\Types\ExceptionType;
use App\Services\TranslationService;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to roles
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RoleRepository
{
    use ExceptionType;

    protected $role;
    protected $permission;
    protected $rolePermission;
    protected $action;
    protected $translationService;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Role $role, Permission $permission, RolePermission $rolePermission, Action $action, Request $request, TranslationService $translation)
    {
        $this->request = $request;
        $this->role = $role;
        $this->permission = $permission;
        $this->rolePermission = $rolePermission;
        $this->action = $action;
        $this->translationService = $translation;
    }

    /**
     * Description: This function returns all roles
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Role
     */
    public function findAll()
    {
        $query = $this->role::with("translations")->where("slug", "!=", "external")->withCount('users');
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns all permissions by roles
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Role
     */
    public function findAllPermissionsByRole()
    {
        $query = $this->permission::with(
            array(
                'rolePermissions' => function ($query) {
                    $query->where('role_permissions.role_id', request("role_id"));
                },
            )
        );
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns all permissions by roles
     * @param $roleId
     * @return \App\Models\Idea\Role
     * @author Hassan Mehmood - I2L
     */
    public function saveRolePermission($roleId)
    {
        $role = $this->role::find($roleId);
        if (! $role) {
            return $this->failed("wrong_role_id");
        }

        $actions = $this->action::pluck('id', 'name')->toArray();

        $data = $this->request->all();

        //delete all permission before we start
        $this->rolePermission::byRoleId($roleId)->delete();

        foreach ($data as $permission) {
            //check actions
            if (empty($permission['actions'])) {
                continue;
            }

            //retrieve permission
            $permissionObj = $this->permission::find($permission['id']);
            if (! $permissionObj) {
                continue;
            }

            //loop through permission
            foreach ($permission['actions'] as $action => $value) {
                if (! $value) {
                    continue;
                }

                //save record
                $record                = new RolePermission();
                $record->permission_id = $permissionObj->id;
                $record->role_id       = $roleId;
                $record->action_id     = $actions[$action];
                $record->save();
            }
        }

        return true;
    }

    /**
     * Description: The following method will update role to the system
     *
     * @param email    : the admin email address
     * @param name     : the admin name
     * @param role_id  : the role_id of the admin
     * @param username : the admin username
     * @param password : the admin password
     *
     * @return success or failure
     */
    public function updateRole($id)
    {
        $data = $this->request->all();

        if (empty($data)) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_role_please_try_again_later');
        }

        $role = $this->role::find($id);
        if (! $role) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_role_please_try_again_later');
        }

        $role->slug = $data['slug'];

        if (! $role->save()) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_role_please_try_again_later');
        }

        $this->translationService->insertTranslations($data, $role);
        return $role;
    }
}
