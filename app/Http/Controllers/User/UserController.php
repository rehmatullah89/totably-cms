<?php
/**
 * UserController
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Http\Controllers\User;

use App\Repositories\User\UserAccountRepository;
use App\Idea\Base\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "user_roles", "action" => "read"],
        "getAllRoles"        => ["code" => "user_roles", "action" => "read"],
        "permissionsByRole"  => ["code" => "user_roles", "action" => "read"],
        "destroy"            => ["code" => "user_roles", "action" => "write"],
        "store"              => ["code" => "user_roles", "action" => "write"],
        "update"             => ["code" => "user_roles", "action" => "write"],
        "updateRole"         => ["code" => "user_roles", "action" => "write"],
        "setRolePermissions" => ["code" => "user_roles", "action" => "write"],
    ];

    protected $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository, Request $request)
    {
        parent::__construct($request);
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'store'  => [
                'email'    => 'required|email|unique:users,email',
                'password' => 'required',
                'name'     => 'required',
                'username' => 'required',
                'role_id'  => 'required|exists:roles,id',
            ],
            'update' => [
                'email'    => 'required',
                'name'     => 'required',
                'username' => 'required',
                'role_id'  => 'required|exists:roles,id',
            ],
        ];
    }

    /**
     * Description: The following method will fetch all Users.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->userAccountRepository->findAll());
    }

    /**
     * Description: The following method will fetch one User.
     *
     * @param int    : the admin id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->userAccountRepository->findOne($id));
    }

    /**
     * Description: The following method will add new admin to the system
     *
     * @return JsonResponse success or failure
     */
    public function store()
    {
        return $this->success('idea::general.register_success', $this->userAccountRepository->registerNewAdmin());
    }

    /**
     * Description: The following method will update new admin to the system
     *
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success('idea::general.update_success', $this->userAccountRepository->updateAdmin($id));
    }


    /**
     * Description: The following method will delete one User.
     *
     * @param int    : the admin id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->userAccountRepository->deleteAdmin($id) ? $this->success() : $this->failed();
    }
}
