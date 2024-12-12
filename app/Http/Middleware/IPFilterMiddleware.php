<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Muhammad Abid
 *
 */

namespace App\Idea\Http\Middleware;

use Closure;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Idea\Http\Response\BaseResponse;
use App\Models\Idea\IPFilter;
use RuntimeException;

//hassan, I didn't get exactly the purpose behind this, can you check with Muhammad Abid or Shuja
class IPFilterMiddleware
{
    use ExceptionType;
    public $request;


    /**
     * Create a new BaseMiddleware instance.
     * @param Idea\Models\IPFilter
     * @return void
     */
    public function __construct(IPFilter $ipFilterModel)
    {
        $this->ipFilterModel = $ipFilterModel;
    }

    /**
     * Description: The following method is used to IP filter the incoming request
     *
     * @author Muhammad Abid - I2L
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new BaseResponse;
        // check if the request IP is allow or not
        if ($this->ipFilterModel->where('ip', $request->ip())->where('status', '1')->exists()) {
            return $next($request);
        } else {
            throw new HttpResponseException($response->failed($request->ip() .' this IP is not allowed'));
        }
    }
}
