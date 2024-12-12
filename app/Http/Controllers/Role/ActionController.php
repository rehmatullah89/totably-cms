<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/28/2017
 * Time: 3:48 PM
 */

namespace App\Http\Controllers\Role;

use App\Idea\Base\BaseController;
use App\Repositories\Role\ActionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActionController extends BaseController
{
    protected $actionRepository;

    /**
     * @param ActionRepository $actionRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(ActionRepository $actionRepository, Request $request)
    {
        parent::__construct($request);
        $this->actionRepository = $actionRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [];
    }

    /**
     * Function to return all actions
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successData($this->actionRepository->findAll());
    }
}
