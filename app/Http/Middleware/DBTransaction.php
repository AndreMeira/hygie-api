<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class DBTransaction
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

        if (is_callable([$response, "getStatusCode"])
        && $response->getStatusCode() > 399) {
            \DB::rollBack();
        } else {
            \DB::commit();
        }

        return $response;
    }
}
