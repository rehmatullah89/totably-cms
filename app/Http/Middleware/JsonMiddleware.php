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

use Closure;
use App\Models\Idea\Device;
use App\Idea\Types\ExceptionType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

//hassan, I didn't get exactly the purpose behind this, can you check with Shuja
class JsonMiddleware
{
    use ExceptionType;
    public $request;
    private $jsonResponseType = 'application/json';


    /**
     * Description: The following method is used to handle the incoming request for the current middleware
     * @author Shuja Ahmed - I2L
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->request = $request;
        $contentType = $request->headers->get('Content-Type');

        if (0 !== strpos($contentType, $this->jsonResponseType)) {
            $this->raiseInvalidJsonException('invalid_json_content_type');
        }
        if (!$this->isInputValidJson($this->request->getContent())) {
            $this->raiseInvalidJsonException('invalid_json_content_type');
        }

        $response = $next($request);

        return $response;
    }

    /**
     * Description: The following method is used to check whether the respective string contains a valid json
     * @author Shuja Ahmed - I2L
     * @param $string
     * @return bool
     */
    protected function isInputValidJson($string)
    {
        return is_object(json_decode($string));
    }
}
