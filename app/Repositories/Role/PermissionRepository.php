<?php


namespace App\Repositories\Role;

use App\Models\Idea\Permission;
use App\Models\Idea\UserRole;
use App\Idea\Base\BasePaging;
use App\Idea\Types\ExceptionType;

/**
 * Description: The following repository is used to handle all function related to permissions
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class PermissionRepository
{
    use ExceptionType;

    protected $permission;
    protected $userRole;

    public function __construct(Permission $permission, UserRole $userRole)
    {
        $this->permission = $permission;
        $this->userRole = $userRole;
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
     * Description: This function returns all permissions by users
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Role
     */
    public function findAllPermissionsByUser()
    {
        //verify permission
        $userRoles = $this->userRole::where('user_id', \Auth::user()->id)->pluck('role_id');
        $query     = $this->permission::with(
            array(
                'rolePermissions' => function ($query) use ($userRoles) {
                    $query->whereIn('role_permissions.role_id', $userRoles);
                },
            )
        );
        $query = new BasePaging($query);
        return $query;
    }
}
