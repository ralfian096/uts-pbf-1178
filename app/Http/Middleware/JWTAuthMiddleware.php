<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = 'all')
    {
        try {

            // Otentikasi client
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'You are not logged yet'
                ], 401);
            }

            // Cek role
            if ($role != 'all') {
                if ($user->role != $role) {
                    return response()->json([
                        'message' => 'You do not have right to access this resource'
                    ], 403);
                }
            }

            return $next($request);
        } catch (JWTException $e) {

            return response()->json([
                'message' => 'You are not logged yet'
            ], 401);
        }
    }
}
