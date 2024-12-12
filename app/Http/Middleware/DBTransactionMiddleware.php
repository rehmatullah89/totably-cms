<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */
namespace App\Idea\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

//hassan this is very import, make sure you understand it
class DBTransactionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        \DB::beginTransaction();

        try {
            $response = $next($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

        if ($response instanceof Response && $response->getStatusCode() > 399) {
            \DB::rollBack();
        } else {
            \DB::commit();
        }

        return $response;
    }
}
