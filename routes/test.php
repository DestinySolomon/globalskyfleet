<?php

// This file contains test routes for debugging
// Route: GET /test-timezone

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/test-timezone', function() {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }
    
    $user = Auth::user();
    
    return response()->json([
        'user_id' => $user->id,
        'email' => $user->email,
        'stored_timezone' => $user->timezone,
        'app_timezone' => config('app.timezone'),
        'server_time_utc' => now()->utc()->format('Y-m-d H:i:s A e'),
        'user_timezone_converted' => now()->setTimezone($user->timezone ?? config('app.timezone'))->format('Y-m-d H:i:s A e'),
        'refresh' => 'To test: curl -X POST http://localhost:8000/api/timezone/detect -H "Content-Type: application/json" -d \'{"timezone":"Africa/Lagos"}\''
    ]);
});
