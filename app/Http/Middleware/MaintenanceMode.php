<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled in settings
        if (setting('maintenance_mode', false)) {
            // Allow admin and super admin users to access
            if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())) {
                return $next($request);
            }
            
            // Return maintenance mode response for other users
            return response(view('maintenance'), 503);
        }
        
        return $next($request);
    }
}
