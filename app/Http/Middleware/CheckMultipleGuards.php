<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMultipleGuards
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        $guard = $request->header('guard');


        if (!in_array($guard, ['daneshamooz', 'moshaver'])) {
            return response()->json(['error' => 'Invalid guard specified.'], 401);
        }


        Auth::shouldUse($guard);


        if (!Auth::guard($guard)->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
