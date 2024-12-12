<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:05 PM
 */

namespace App\Http\Controllers\Role;

use App\Idea\Base\BaseController;
use App\Models\Idea\Permission;
use App\Repositories\PageRepository;
use App\Repositories\Role\PermissionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    protected $permissions = [
        "index" => ["code" => "user_roles", "action" => "read"],
        "destroy" => ["code" => "user_roles", "action" => "write"],
        "store" => ["code" => "user_roles", "action" => "write"],
        "update" => ["code" => "user_roles", "action" => "write"],
        "updateAll" => ["code" => "user_roles", "action" => "write"],
    ];

    /**
     * @var PageRepository|PermissionRepository
     */
    private $permissionRepository;

    /**
     * @param PermissionRepository $permissionRepository
     * @param Request $request
     */
    public function __construct(PermissionRepository $permissionRepository, Request $request)
    {
        parent::__construct($request);
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [];
    }

    /**
     * Init
     */
    public function init()
    {
        $this->setModel(new Permission());
    }

    /**
     * Description: The following method will list permissions.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->permissionRepository->findAllPermissionsByRole());
    }
}
