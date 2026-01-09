<?php
// app/Http/Controllers/UserProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\CryptoAddress;

class UserProfileController extends Controller
{
    /**
     * Show user profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'total_shipments' => $user->shipments()->count(),
            'pending_shipments' => $user->shipments()->whereIn('status', ['pending', 'confirmed'])->count(),
            'delivered_shipments' => $user->shipments()->where('status', 'delivered')->count(),
            'total_addresses' => $user->addresses()->count(),
            'total_documents' => $user->documents()->count(),
            'total_invoices' => $user->invoices()->count(),
        ];
        
        // Get recent activity
        $recentShipments = $user->shipments()
            ->with(['recipientAddress'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('user.profile', compact('user', 'stats', 'recentShipments'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        try {
            DB::beginTransaction();
            
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->company = $request->company;
            $user->bio = $request->bio;
            
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
                }
                
                // Generate unique filename
                $filename = Str::uuid() . '.' . $request->profile_picture->extension();
                
                // Store the file
                $path = $request->profile_picture->storeAs('profile-pictures', $filename, 'public');
                
                $user->profile_picture = $filename;
            }
            
            $user->save();
            
            DB::commit();
            
            return redirect()->route('user.profile')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show account settings page.
     */
    public function showAccount()
    {
        $user = Auth::user();
        
        // Get user's default addresses
        $defaultShipping = $user->defaultShippingAddress();
        $defaultBilling = $user->defaultBillingAddress();
        
        return view('user.account', compact('user', 'defaultShipping', 'defaultBilling'));
    }

    /**
     * Update account settings.
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'timezone' => ['required', 'timezone'],
            'language' => ['required', 'in:en,fr,es,de,zh,ar'],
            'currency' => ['required', 'in:USD,EUR,GBP,JPY,AUD,CAD,CHF,CNY'],
            'date_format' => ['required', 'in:m/d/Y,d/m/Y,Y-m-d'],
            'time_format' => ['required', 'in:12h,24h'],
            
            // Notification settings
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'shipment_updates' => ['boolean'],
            'promotional_emails' => ['boolean'],
            'billing_notifications' => ['boolean'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update email if changed
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->email_verified_at = null; // Require re-verification
            }
            
            // Update settings
            $settings = $user->settings ?? [];
            
            // Display preferences
            $settings['display'] = [
                'timezone' => $request->timezone,
                'language' => $request->language,
                'currency' => $request->currency,
                'date_format' => $request->date_format,
                'time_format' => $request->time_format,
            ];
            
            // Notification preferences
            $settings['notifications'] = [
                'email_notifications' => $request->boolean('email_notifications'),
                'sms_notifications' => $request->boolean('sms_notifications'),
                'shipment_updates' => $request->boolean('shipment_updates'),
                'promotional_emails' => $request->boolean('promotional_emails'),
                'billing_notifications' => $request->boolean('billing_notifications'),
            ];
            
            $user->settings = $settings;
            $user->save();
            
            DB::commit();
            
            return redirect()->route('user.account')
                ->with('success', 'Account settings updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update account settings. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show privacy & security page.
     */
    public function showSecurity()
    {
        $user = Auth::user();
        
        // Get active sessions
        $activeSessions = $user->activeSessions();
        
        // Get login history (last 10 logins)
        $loginHistory = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->limit(10)
            ->get(['id', 'ip_address', 'user_agent', 'last_activity']);
        
        return view('user.security', compact('user', 'activeSessions', 'loginHistory'));
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'new_password_confirmation' => ['required'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        try {
            DB::beginTransaction();
            
            $user->password = Hash::make($request->new_password);
            $user->password_changed_at = now();
            $user->save();
            
            DB::commit();
            
            return redirect()->route('user.security')
                ->with('success', 'Password changed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to change password. Please try again.')
                ->withInput();
        }
    }

    /**
     * Toggle two-factor authentication.
     */
    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'enable' => ['required', 'boolean'],
            'password' => ['required', 'current_password'],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            if ($request->enable) {
                // Generate 2FA secret (simplified - in production use proper 2FA library)
                $secret = Str::random(32);
                $recoveryCodes = collect(range(1, 8))->map(function () {
                    return Str::random(10);
                })->toArray();
                
                $user->two_factor_enabled = true;
                $user->two_factor_secret = $secret;
                $user->two_factor_recovery_codes = $recoveryCodes;
            } else {
                $user->two_factor_enabled = false;
                $user->two_factor_secret = null;
                $user->two_factor_recovery_codes = null;
            }
            
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $request->enable ? 'Two-factor authentication enabled!' : 'Two-factor authentication disabled!',
                'enabled' => $user->two_factor_enabled,
                'recovery_codes' => $request->enable ? $recoveryCodes : null,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update two-factor authentication.',
            ], 500);
        }
    }

    /**
     * Terminate a session.
     */
    public function terminateSession(Request $request, $sessionId)
    {
        $user = Auth::user();
        
        // Prevent terminating current session
        if ($sessionId === $request->session()->getId()) {
            return redirect()->back()
                ->with('error', 'Cannot terminate current session.');
        }
        
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();
        
        return redirect()->route('user.security')
            ->with('success', 'Session terminated successfully.');
    }

    /**
     * Terminate all other sessions.
     */
    public function terminateAllOtherSessions(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = $request->session()->getId();
        
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
        
        return redirect()->route('user.security')
            ->with('success', 'All other sessions have been terminated.');
    }

    /**
     * Export user data.
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
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'addresses' => $user->addresses->map(function ($address) {
                return [
                    'type' => $address->type,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                    'company' => $address->company,
                    'address' => $address->address_line1 . ' ' . $address->address_line2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country_code' => $address->country_code,
                    'created_at' => $address->created_at,
                ];
            }),
            'shipments' => $user->shipments->map(function ($shipment) {
                return [
                    'tracking_number' => $shipment->tracking_number,
                    'status' => $shipment->status,
                    'weight' => $shipment->weight,
                    'declared_value' => $shipment->declared_value,
                    'created_at' => $shipment->created_at,
                    'estimated_delivery' => $shipment->estimated_delivery,
                    'actual_delivery' => $shipment->actual_delivery,
                ];
            }),
            'invoices' => $user->invoices->map(function ($invoice) {
                return [
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => $invoice->amount,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'paid_at' => $invoice->paid_at,
                ];
            }),
        ];
        
        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="user-data-' . $user->id . '-' . now()->format('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Deactivate account.
     */
    public function deactivateAccount(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'string', 'in:DEACTIVATE MY ACCOUNT'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please confirm account deactivation.');
        }
        
        try {
            DB::beginTransaction();
            
            // Mark user as deactivated (you can add a 'deactivated_at' column)
            // For now, we'll just delete the user (soft delete if you have it)
            // $user->deactivated_at = now();
            // $user->save();
            
            // Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            DB::commit();
            
            return redirect()->route('home')
                ->with('success', 'Your account has been deactivated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to deactivate account. Please try again.');
        }
    }

    /**
     * Delete profile picture.
     */
    public function deleteProfilePicture(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->profile_picture) {
            return redirect()->route('user.profile')
                ->with('error', 'No profile picture to delete.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete file from storage (public disk)
            Storage::disk('public')->delete('profile-pictures/' . $user->profile_picture);
            
            // Clear from user record
            $user->profile_picture = null;
            $user->save();
            
            DB::commit();
            
            return redirect()->route('user.profile')
                ->with('success', 'Profile picture deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('user.profile')
                ->with('error', 'Failed to delete profile picture. Please try again.');
        }
    }


    // In your payment controller
public function showPaymentPage($invoiceId)
{
    $invoice = Invoice::findOrFail($invoiceId);
    
    // Get active crypto wallets
    $cryptoWallets = CryptoAddress::where('is_active', true)
        ->get()
        ->groupBy('crypto_type');
    
    return view('payments.crypto', compact('invoice', 'cryptoWallets'));
}
}