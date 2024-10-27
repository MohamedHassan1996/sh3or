<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PrivateBroadcastAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        //dd('test');

        //Mocking authenticated user list without database
        $authenticatedUsers = array([
            'codewithgun' => 'gunwithpassword'
        ]);

        $authenticatedUsers = array(
            'codewithgun' => 'gunwithcode'
        );

        $user = $request->header('user');
        $password = $request->header('password');
        if (!array_key_exists($user, $authenticatedUsers)) {
            return response('Unauthorized', 401);
        }
        if ($authenticatedUsers[$user] != $password) {
            return response('Unauthorized', 401);
        }
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
