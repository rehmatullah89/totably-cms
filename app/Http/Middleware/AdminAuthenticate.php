<?php

/*
 * This file is part of the IdeaToLife package.
 * This is a middleware that ensure following things:
 * 1. First the incoming request has a content-type of JSON.
 * 2. Secondly the given json in the request has a valid json structure
 *
 * (c) Shuja Ahmed <shuja.ahmed@ideatolife.me>
 *
 */

namespace App\Idea\Http\Middleware;

use App\Idea\Types\ExceptionType;
use App\Models\Idea\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class AdminAuthenticate extends Authenticate
{
    use ExceptionType;

    public function authenticate(Request $request)
    {
        $this->checkForToken($request);

        try {
            $token = $this->auth->parseToken();
            $tokenSign = $token->getPayload()->get('jwt_sign');
            $isCmsUser = $token->getPayload()->get(User::$jwtCmsKey);
            if (!$isCmsUser) {
                $this->raiseAuthorizationException("invalid_admin_jwt", ['invalid_admin_jwt']);
            }

            $user = $token->authenticate();

            if (!$user || !$user->active) {
                $this->raiseAuthorizationException("user_not_exist_or_blocked", ["user_not_exist_or_blocked"]);
            }

            if ($tokenSign != $user->jwt_sign) {
                $this->raiseAuthorizationException("user_credentials_update_please_login_again", ["user_credentials_update_please_login_again"]);
            }
        } catch (JWTException $e) {
            $this->raiseAuthorizationException("invalid_jwt_sign", ["invalid_jwt_sign"]);
        }
    }
}
