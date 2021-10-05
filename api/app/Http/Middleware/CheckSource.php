<?php

namespace App\Http\Middleware;

use Closure;

class CheckSource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tag = 'MicroMessenger';
        $source = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($source,$tag)) {
            return $next($request);
        } else {
            return response()->json([
                'info' => '请使用微信访问',
                'status' => 'ok',
                'status_code' => 200
            ], 200);

        }
    }
}
