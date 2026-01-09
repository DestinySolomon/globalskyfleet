<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        // Check if user is admin OR super admin
        if (!Auth::user()->isAdminOrSuperAdmin()) {
            // If user is not admin/super admin, redirect to dashboard with error
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}