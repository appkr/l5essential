<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Middleware\BaseMiddleware;

class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $newToken = $this->auth->setRequest($request)->parseToken()->refresh();

        return response()->json([
            'code' => 201,
            'message' => 'success',
            'token' => $newToken,
        ], 201, [
            'Authorization' => "Bearer {$newToken}",
        ]);
    }
}