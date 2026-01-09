<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show settings index page
     */
    public function index()
    {
        $settings = Setting::getAllGrouped();
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            // Handle file uploads
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                Setting::set($key, $path);
                continue;
            }
            
            // Handle checkboxes (boolean values)
            if (in_array($key, [
                'maintenance_mode', 'enable_customs_declaration', 'require_id_verification',
                'enable_crypto_payments', 'notify_shipment_created', 'notify_shipment_status',
                'notify_payment_received', 'notify_invoice_created', 'notify_document_uploaded',
                'password_require_numbers', 'password_require_special_chars', 'enable_2fa'
            ])) {
                Setting::set($key, $request->has($key) ? '1' : '0', 'boolean');
                continue;
            }
            
            // Handle arrays (JSON)
            if (in_array($key, ['shipping_services'])) {
                Setting::set($key, $value, 'json');
                continue;
            }
            
            // Handle numbers
            if (in_array($key, [
                'max_weight_kg', 'max_dimension_cm', 'dimensional_weight_divisor',
                'invoice_due_days', 'crypto_expiry_hours', 'min_payment_amount', 'max_payment_amount',
                'mail_port', 'password_min_length', 'session_lifetime', 'max_login_attempts', 'lockout_time'
            ])) {
                Setting::set($key, $value, is_float($value) ? 'decimal' : 'integer');
                continue;
            }
            
            // Default string
            Setting::set($key, $value);
        }
        
        // Clear settings cache if you're using caching
        if (function_exists('cache')) {
            cache()->forget('settings');
        }
        
        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
    
    /**
     * Show email templates
     */
    public function emailTemplates()
    {
        $templates = [
            'welcome' => [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to GlobalSkyFleet',
                'description' => 'Sent when a new user registers',
                'variables' => ['{{user_name}}', '{{user_email}}', '{{login_url}}']
            ],
            'invoice_created' => [
                'name' => 'Invoice Created',
                'subject' => 'Your Invoice from GlobalSkyFleet',
                'description' => 'Sent when a new invoice is created',
                'variables' => ['{{invoice_number}}', '{{amount}}', '{{due_date}}', '{{invoice_url}}']
            ],
            'invoice_paid' => [
                'name' => 'Invoice Paid',
                'subject' => 'Payment Confirmed - GlobalSkyFleet',
                'description' => 'Sent when a payment is confirmed',
                'variables' => ['{{invoice_number}}', '{{amount}}', '{{payment_method}}', '{{payment_date}}']
            ],
            'shipment_created' => [
                'name' => 'Shipment Created',
                'subject' => 'Shipment Created - Tracking #{{tracking_number}}',
                'description' => 'Sent when a new shipment is created',
                'variables' => ['{{tracking_number}}', '{{shipment_status}}', '{{estimated_delivery}}', '{{tracking_url}}']
            ],
            'shipment_status' => [
                'name' => 'Shipment Status Update',
                'subject' => 'Shipment Status Update - {{tracking_number}}',
                'description' => 'Sent when shipment status changes',
                'variables' => ['{{tracking_number}}', '{{old_status}}', '{{new_status}}', '{{tracking_url}}']
            ],
            'shipment_delivered' => [
                'name' => 'Shipment Delivered',
                'subject' => 'Your Shipment Has Been Delivered!',
                'description' => 'Sent when shipment is delivered',
                'variables' => ['{{tracking_number}}', '{{delivery_date}}', '{{delivery_location}}']
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'subject' => 'Reset Your Password',
                'description' => 'Sent when user requests password reset',
                'variables' => ['{{reset_url}}', '{{expiry_time}}']
            ],
        ];
        
        return view('admin.settings.email-templates', compact('templates'));
    }
    
    /**
     * Update email template
     */
    public function updateEmailTemplate(Request $request, $template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Save template to database or config file
        Setting::set("email_template_{$template}_subject", $request->subject);
        Setting::set("email_template_{$template}_content", $request->content, 'text');
        
        return redirect()->route('admin.settings.email')
            ->with('success', 'Email template updated successfully!');
    }
    
    /**
     * Clear cache
     */
    public function clearCache()
    {
        // Clear various caches
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        
        return redirect()->route('admin.settings')
            ->with('success', 'Cache cleared successfully!');
    }
    
    /**
     * Backup database
     */
    public function backupDatabase()
    {
        \Artisan::call('backup:run', ['--only-db' => true]);
        
        return redirect()->route('admin.settings')
            ->with('success', 'Database backup created successfully!');
    }
}