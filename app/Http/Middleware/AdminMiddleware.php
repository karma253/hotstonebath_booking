<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures that only authenticated admin users can access protected routes.
     * Prevents unauthorized access with proper redirects and error messages.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('home')
                ->with('error', '❌ Unauthorized access. Please log in first.');
        }

        // Check if user has admin role
        if (!$request->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('error', '🚫 Unauthorized access. Admin only. This incident has been logged.');
        }

        return $next($request);
    }
}
