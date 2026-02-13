<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimezoneController extends Controller
{
    /**
     * Auto-detect and save user's timezone from browser
     */
    public function detectAndSave(Request $request)
    {
        $timezone = $request->input('timezone');
        
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }
        
        // List of valid timezones
        $validTimezones = timezone_identifiers_list();
        
        if (!in_array($timezone, $validTimezones)) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid timezone',
                'received' => $timezone,
                'valid' => in_array($timezone, $validTimezones)
            ], 422);
        }
        
        try {
            // Update user's timezone
            Auth::user()->update(['timezone' => $timezone]);
            
            // Log the update
            \Log::info('User timezone auto-detected and saved', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'timezone' => $timezone
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Timezone saved successfully',
                'timezone' => $timezone,
                'current_time' => now()->setTimezone($timezone)->format('Y-m-d H:i:s A')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving timezone: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user's current timezone
     */
    public function get(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['timezone' => null]);
        }
        
        return response()->json([
            'timezone' => Auth::user()->timezone
        ]);
    }
}
