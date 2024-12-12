<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Base;

use App\Models\Idea\RolePermission;
use App\Models\Idea\UserRole;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

/**
 * Http Controller Idea class
 */
abstract class BaseController extends Controller
{
    use ExceptionType;

    /*
     * BaseResponse
     */
    protected $response;

    /*
     * request
     */
    protected $request;

    /*
     * action
     */
    protected $action;

    /*
     * user
     */
    protected $user;

    /*
     * Permission
     */
    protected $permissions = [];

    /*
     * Model
     */
    protected $model;

    public $with = [];
    public $withImage = false;
    public $withImageThumb = false;
    public $imageName = "image";
    public $thumbnailName = "thumbnail";
    public $filePath = "1";

    /*
     * Messages
     */
    public $messages = [
        "destroy_error"   => "Cannot delete record",
        "destroy_success" => "Record deleted successfully",
        "save_error"      => "Cannot add record",
        "save_success"    => "Record added successfully",
        "update_success"    => "Record updated successfully",
        "update_error"    => "Cannot updated record",
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new BaseResponse();

        //Catch the action
        if (!$this->catchLumenAction()) {
            $this->raiseHttpResponseException("page_not_exit");
        }

        //Validate Permission
        if (!$this->validatePermission()) {
            $this->raiseHttpResponseException("access_denied");
        }

        $this->validateRequestParams();

        $this->user = \Auth::user();

        $this->init();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    protected function catchLumenAction()
    {
        $route = $this->request->route();
        //as we are using Lumen, not laravel
        //this is a simple workaround to catch the action
        if (isset($route['1']) && !empty($route['1']['uses'])) {
            $exploded = explode("@", $route['1']['uses']);

            if (!empty($exploded['0']) && !empty($exploded['1'])) {
                $this->action = $exploded['1'];

                return true;
            }
        }

        return false;
    }

    protected function validatePermission()
    {
        if (empty($this->permissions[$this->action])) {
            return true;
        }

        $userRoles = UserRole::where('user_id', \Auth::user()->id)->pluck('role_id');

        //Permission
        $code = $this->permissions[$this->action]['code'];
        //Action
        $action = $this->permissions[$this->action]['action'];

        //verify permission
        $permission = RolePermission::whereIn("role_id", $userRoles)
            ->whereHas(
                'permission',
                function ($query) use ($code) {
                    $query->where('code', $code);
                }
            )
            ->whereHas(
                'action',
                function ($query) use ($action) {
                    $query->where('name', $action);
                }
            )->first();

        //if permission exist return true
        if ($permission) {
            return true;
        }

        return false;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequestParams()
    {
        //validate the request
        $validator = $this->getValidationFactory()->make(
            $this->request->all(),
            $this->getRules(),
            $this->getMessages()
        );

        if ($validator->fails()) {
            $this->raiseValidationException($validator);
        }
    }

    private function getMessages()
    {
        $rules = $this->validationMessages();
        if (empty($rules) || !$this->action || empty($rules[$this->action])) {
            return [];
        }

        return $rules[$this->action];
    }


    private function getRules()
    {
        $rules = $this->validationRules();
        if (empty($rules) || !$this->action || empty($rules[$this->action])) {
            return [];
        }

        return $rules[$this->action];
    }

    abstract protected static function validationRules();

    protected static function validationMessages()
    {
        return [];
    }

    protected function init()
    {
        return true;
    }

    public function in($key, $default = null)
    {
        return $this->request->input($key, $default);
    }

    public function success($message = null, $data = null)
    {
        return $this->response->success($message, $data);
    }

    public function successData($data = null)
    {
        return $this->response->success(BaseResponse::STATUS_SUCCESS, $data);
    }

    public function successWithItems($message = null, $data = null)
    {
        return $this->response->successWithItems($message, $data);
    }

    public function failed($message = null, $data = null)
    {
        $message = $message ?: BaseResponse::STATUS_SUCCESS;

        return $this->response->failedWithErrors($message, $data);
    }

    public function failedData($data = null)
    {
        return $this->response->failedWithErrors(BaseResponse::STATUS_SUCCESS, $data);
    }

    public function failedWithErrors($errors = [])
    {
        return $this->response->failedWithErrors(BaseResponse::STATUS_SUCCESS, $errors);
    }
}
