<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Http\Controllers\Auth;

use App\Idea\Base\BaseController;
use App\Idea\Types\ExceptionType;
use App\Idea\Types\SocialType;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends BaseController
{

    use SocialType, ExceptionType;

    protected $userAccountRepository;

    /**
     * @param UserAccountRepository $userAccountRepository
     * @param Request $request
     */
    public function __construct(UserAccountRepository $userAccountRepository, Request $request)
    {
        parent::__construct($request);

        //five request per second only
        $this->middleware('throttle:5,1');

        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'login' => [
                'username' => 'required',
                'password' => 'required',
            ]
        ];
    }


    /**
     * Function to login user
     *
     * @string username
     * @string password
     * @return JsonResponse
     */
    public function login()
    {
        $username = request('username');
        $password = request('password');

        try {
            return $this->success('idea::general.login_success', $this->userAccountRepository->userLogin($username, $password));
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->failed('idea::general.could_not_authenticate_user');
        }
    }
}
