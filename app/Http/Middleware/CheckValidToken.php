<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Token is missing, invalid, or expired. Please login again.'
            ], 401);
        }

        return $next($request);
    }
}
