<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $this->insertDefaultSettings();
    }

    private function insertDefaultSettings(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'GlobalSkyFleet', 'type' => 'string', 'group' => 'general', 'description' => 'Website name'],
            ['key' => 'site_tagline', 'value' => 'Your Trusted Global Courier Partner', 'type' => 'string', 'group' => 'general', 'description' => 'Website tagline'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'string', 'group' => 'general', 'description' => 'Site logo path'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'string', 'group' => 'general', 'description' => 'Site favicon path'],
            ['key' => 'site_email', 'value' => 'info@globalskyfleet.com', 'type' => 'string', 'group' => 'general', 'description' => 'Contact email'],
            ['key' => 'site_phone', 'value' => '+1 (555) 123-4567', 'type' => 'string', 'group' => 'general', 'description' => 'Contact phone'],
            ['key' => 'site_address', 'value' => '123 Shipping Street, Logistics City, LC 10001', 'type' => 'text', 'group' => 'general', 'description' => 'Business address'],
            ['key' => 'site_currency', 'value' => 'USD', 'type' => 'string', 'group' => 'general', 'description' => 'Default currency'],
            ['key' => 'site_timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'general', 'description' => 'Default timezone'],
            ['key' => 'site_language', 'value' => 'en', 'type' => 'string', 'group' => 'general', 'description' => 'Default language'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'description' => 'Maintenance mode status'],
            
            // Shipping Settings
            ['key' => 'shipping_services', 'value' => json_encode(['express', 'standard', 'economy']), 'type' => 'json', 'group' => 'shipping', 'description' => 'Available shipping services'],
            ['key' => 'default_shipping_service', 'value' => 'standard', 'type' => 'string', 'group' => 'shipping', 'description' => 'Default shipping service'],
            ['key' => 'max_weight_kg', 'value' => '50', 'type' => 'decimal', 'group' => 'shipping', 'description' => 'Maximum weight per shipment (kg)'],
            ['key' => 'max_dimension_cm', 'value' => '150', 'type' => 'decimal', 'group' => 'shipping', 'description' => 'Maximum dimension (cm)'],
            ['key' => 'dimensional_weight_divisor', 'value' => '5000', 'type' => 'integer', 'group' => 'shipping', 'description' => 'Dimensional weight divisor'],
            ['key' => 'enable_customs_declaration', 'value' => '1', 'type' => 'boolean', 'group' => 'shipping', 'description' => 'Enable customs declaration'],
            ['key' => 'require_id_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'shipping', 'description' => 'Require ID verification'],
            
            // Payment Settings
            ['key' => 'invoice_prefix', 'value' => 'GSF', 'type' => 'string', 'group' => 'payment', 'description' => 'Invoice number prefix'],
            ['key' => 'invoice_terms', 'value' => 'Payment due within 30 days', 'type' => 'text', 'group' => 'payment', 'description' => 'Default invoice terms'],
            ['key' => 'invoice_due_days', 'value' => '30', 'type' => 'integer', 'group' => 'payment', 'description' => 'Default due days for invoices'],
            ['key' => 'enable_crypto_payments', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable crypto payments'],
            ['key' => 'crypto_expiry_hours', 'value' => '24', 'type' => 'integer', 'group' => 'payment', 'description' => 'Crypto payment expiry hours'],
            ['key' => 'min_payment_amount', 'value' => '10', 'type' => 'decimal', 'group' => 'payment', 'description' => 'Minimum payment amount'],
            ['key' => 'max_payment_amount', 'value' => '10000', 'type' => 'decimal', 'group' => 'payment', 'description' => 'Maximum payment amount'],
            
            // Email Settings
            ['key' => 'mail_from_name', 'value' => 'GlobalSkyFleet', 'type' => 'string', 'group' => 'email', 'description' => 'Email sender name'],
            ['key' => 'mail_from_address', 'value' => 'noreply@globalskyfleet.com', 'type' => 'string', 'group' => 'email', 'description' => 'Email sender address'],
            ['key' => 'mail_host', 'value' => env('MAIL_HOST', 'smtp.mailgun.org'), 'type' => 'string', 'group' => 'email', 'description' => 'SMTP host'],
            ['key' => 'mail_port', 'value' => env('MAIL_PORT', '587'), 'type' => 'integer', 'group' => 'email', 'description' => 'SMTP port'],
            ['key' => 'mail_username', 'value' => env('MAIL_USERNAME', ''), 'type' => 'string', 'group' => 'email', 'description' => 'SMTP username'],
            ['key' => 'mail_password', 'value' => env('MAIL_PASSWORD', ''), 'type' => 'string', 'group' => 'email', 'description' => 'SMTP password'],
            ['key' => 'mail_encryption', 'value' => env('MAIL_ENCRYPTION', 'tls'), 'type' => 'string', 'group' => 'email', 'description' => 'SMTP encryption'],
            ['key' => 'mail_driver', 'value' => env('MAIL_MAILER', 'smtp'), 'type' => 'string', 'group' => 'email', 'description' => 'Mail driver'],
            
            // Notification Settings
            ['key' => 'notify_shipment_created', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify on shipment creation'],
            ['key' => 'notify_shipment_status', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify on status changes'],
            ['key' => 'notify_payment_received', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify on payment received'],
            ['key' => 'notify_invoice_created', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify on invoice creation'],
            ['key' => 'notify_document_uploaded', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify on document upload'],
            
            // Security Settings
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'integer', 'group' => 'security', 'description' => 'Minimum password length'],
            ['key' => 'password_require_numbers', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require numbers in passwords'],
            ['key' => 'password_require_special_chars', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require special characters'],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'integer', 'group' => 'security', 'description' => 'Session lifetime (minutes)'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'description' => 'Maximum login attempts'],
            ['key' => 'lockout_time', 'value' => '15', 'type' => 'integer', 'group' => 'security', 'description' => 'Lockout time (minutes)'],
            ['key' => 'enable_2fa', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'description' => 'Enable two-factor authentication'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};