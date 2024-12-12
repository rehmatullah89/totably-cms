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
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;

//hassan can you check with Shuja where we are using this
class CheckRequestType
{
    use ExceptionType;

    /**
     * Description: The following method is used to make sure only GET or POST requests are allowed
     * @author Shuja Ahmed - I2L
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            // check if the method type is not post or get
            if (!$request->isMethod('get') && !$request->isMethod('post')) {
                $this->raiseInvalidRequestException("invalid_request_type", ["invalid_request_type"]);
            }
        } catch (HttpException $e) {
            $this->raiseInvalidRequestException("invalid_request_type", ["invalid_request_type"]);
        }

        $response = $next($request);

        return $response;
    }
}
