<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Show admin profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:10',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
            }

            // Store new profile picture
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile-pictures', $filename, 'public');
            
            $validated['profile_picture'] = $filename;
        }

        // Update user settings
        $settings = $user->settings ?? [];
        
        if ($request->has('language')) {
            $settings['display']['language'] = $request->language;
        }
        
        if ($request->has('timezone')) {
            $settings['display']['timezone'] = $request->timezone;
        }
        
        if ($request->has('date_format')) {
            $settings['display']['date_format'] = $request->date_format;
        }
        
        if ($request->has('time_format')) {
            $settings['display']['time_format'] = $request->time_format;
        }
        
        $validated['settings'] = $settings;

        $user->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('admin.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Update profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
        }

        // Store new profile picture
        $file = $request->file('profile_picture');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('profile-pictures', $filename, 'public');

        $user->update([
            'profile_picture' => $filename,
        ]);

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Delete profile picture
     */
    public function deleteProfilePicture()
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
            
            $user->update([
                'profile_picture' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Profile picture removed successfully!');
    }

    /**
     * Get notification preferences
     */
    public function getNotificationPreferences()
    {
        $user = Auth::user();
        return response()->json([
            'preferences' => $user->notification_preferences,
        ]);
    }

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'shipment_updates' => 'nullable|boolean',
            'payment_updates' => 'nullable|boolean',
            'document_updates' => 'nullable|boolean',
            'system_updates' => 'nullable|boolean',
            'marketing_emails' => 'nullable|boolean',
        ]);

        // Convert checkboxes to boolean values (they will be "on" if checked, null if not)
        $preferences = [];
        foreach ($validated as $key => $value) {
            $preferences[$key] = $request->has($key);
        }

        // Get current preferences from the user model
        $currentPreferences = $user->notification_preferences;
        
        // Merge with new preferences
        $updatedPreferences = array_merge($currentPreferences, $preferences);
        
        // Update user's notification preferences in settings
        $settings = $user->settings ?? [];
        $settings['notifications'] = $updatedPreferences;
        $user->settings = $settings;
        $user->save();

        return redirect()->back()
            ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePictureUrl()
    {
        $user = Auth::user();
        return response()->json([
            'url' => $user->profile_picture_url,
        ]);
    }

    /**
     * Get user settings
     */
    public function getSettings()
    {
        $user = Auth::user();
        return response()->json([
            'settings' => $user->settings,
            'display_preferences' => $user->display_preferences,
            'notification_preferences' => $user->notification_preferences,
        ]);
    }

    /**
     * Update display preferences
     */
    public function updateDisplayPreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:10',
            'theme' => 'nullable|string|in:light,dark,system',
        ]);

        $settings = $user->settings ?? [];
        $settings['display'] = array_merge($settings['display'] ?? [], $validated);
        $user->settings = $settings;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Display preferences updated successfully!',
            'preferences' => $user->display_preferences,
        ]);
    }

    /**
     * Export user data (for GDPR compliance)
     */
    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'company' => $user->company,
                'bio' => $user->bio,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'last_login_at' => $user->last_login_at,
                'last_login_ip' => $user->last_login_ip,
            ],
            'settings' => $user->settings,
            'addresses' => $user->addresses,
            'shipments' => $user->shipments()->with(['senderAddress', 'recipientAddress'])->get(),
            'documents' => $user->documents,
            'payments' => $user->payments,
        ];

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="user-data-' . $user->id . '-' . date('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Get user activity log (optional enhancement)
     */
    public function getActivityLog(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->notifications();
        
        if ($request->has('type')) {
            $query->where('category', $request->type);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->latest()->paginate(15);
        
        return response()->json([
            'activities' => $activities,
            'total' => $activities->total(),
        ]);
    }

    /**
     * Get security information
     */
    public function getSecurityInfo()
    {
        $user = Auth::user();
        
        return response()->json([
            'two_factor_enabled' => $user->two_factor_enabled,
            'password_changed_at' => $user->password_changed_at,
            'password_recently_changed' => $user->passwordRecentlyChanged(),
            'active_sessions' => $user->activeSessions(),
            'login_history' => [
                'last_login_at' => $user->last_login_at,
                'last_login_ip' => $user->last_login_ip,
            ],
        ]);
    }
}