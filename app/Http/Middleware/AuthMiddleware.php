<?php

namespace App\Http\Middleware;

use App\Classes\ApiResponseClass;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $resource = $request->route()->getName();
        $ip = $request->getClientIp();
        if (!Auth::guard($guards)->check()) {
            Log::error("Unauthorize can access to $resource from $ip.");
            return response()->json([
                'message' => 'You are not allowed to access this resource',
                'error_code' => "BP00401"
            ], 401);
        }
        return $next($request);
    }
}
