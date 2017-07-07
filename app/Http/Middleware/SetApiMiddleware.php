<?php

namespace App\Http\Middleware;

use Closure;

class SetApiMiddleware
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
        if($request->header('APIKEY') != '@2017'){
            $message = array('status'=>false, 'message' => "you don't have permission to access");
            return response()->json($message);
        }
        return $next($request);
    }
}
