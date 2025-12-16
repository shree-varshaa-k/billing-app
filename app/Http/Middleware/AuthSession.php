<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is stored in session
        if (!Session::has('user')) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        // Continue request
        return $next($request);
    }
}
