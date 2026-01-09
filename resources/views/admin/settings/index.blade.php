@extends('layouts.admin')

@section('page-title', 'Website Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="settingsTab" role="tablist">
                        <a class="nav-link active" data-bs-toggle="pill" href="#general">
                            <i class="ri-settings-3-line me-2"></i>General
                        </a>
                        <a class="nav-link" data-bs-toggle="pill" href="#shipping">
                            <i class="ri-ship-line me-2"></i>Shipping & Services
                        </a>
                        <a class="nav-link" data-bs-toggle="pill" href="#payment">
                            <i class="ri-money-dollar-circle-line me-2"></i>Payment
                        </a>
                        <a class="nav-link" data-bs-toggle="pill" href="#email">
                            <i class="ri-mail-line me-2"></i>Email & Notifications
                        </a>
                        <a class="nav-link" data-bs-toggle="pill" href="#security">
                            <i class="ri-shield-keyhole-line me-2"></i>Security
                        </a>
                        <hr class="my-2">
                        <a class="nav-link" href="{{ route('admin.settings.email') }}">
                            <i class="ri-mail-send-line me-2"></i>Email Templates
                        </a>
                        <a class="nav-link" href="#tools" data-bs-toggle="pill">
                            <i class="ri-tools-line me-2"></i>System Tools
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Content -->
        <div class="col-lg-9">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-settings-3-line me-2"></i>General Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Name *</label>
                                        <input type="text" name="site_name" class="form-control" 
                                               value="{{ setting('site_name', 'GlobalSkyFleet') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Tagline *</label>
                                        <input type="text" name="site_tagline" class="form-control" 
                                               value="{{ setting('site_tagline', 'Your Trusted Global Courier Partner') }}" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Logo</label>
                                        @if(setting('site_logo'))
                                        <div class="mb-2">
                                            <img src="{{ Storage::url(setting('site_logo')) }}" 
                                                 alt="Site Logo" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 80px;">
                                        </div>
                                        @endif
                                        <input type="file" name="site_logo" class="form-control" accept="image/*">
                                        <small class="text-muted">Recommended: 200x50px PNG or SVG</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Favicon</label>
                                        @if(setting('site_favicon'))
                                        <div class="mb-2">
                                            <img src="{{ Storage::url(setting('site_favicon')) }}" 
                                                 alt="Favicon" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 32px;">
                                        </div>
                                        @endif
                                        <input type="file" name="site_favicon" class="form-control" accept="image/*">
                                        <small class="text-muted">Recommended: 32x32px or 64x64px ICO/PNG</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Email *</label>
                                        <input type="email" name="site_email" class="form-control" 
                                               value="{{ setting('site_email', 'info@globalskyfleet.com') }}" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Phone *</label>
                                        <input type="text" name="site_phone" class="form-control" 
                                               value="{{ setting('site_phone', '+1 (555) 123-4567') }}" required>
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Business Address</label>
                                        <textarea name="site_address" class="form-control" rows="3">{{ setting('site_address', '123 Shipping Street, Logistics City, LC 10001') }}</textarea>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Default Currency *</label>
                                        <select name="site_currency" class="form-select" required>
                                            @foreach(['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CNY'] as $currency)
                                            <option value="{{ $currency }}" {{ setting('site_currency', 'USD') == $currency ? 'selected' : '' }}>
                                                {{ $currency }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Timezone *</label>
                                        <select name="site_timezone" class="form-select" required>
                                            @foreach(timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}" {{ setting('site_timezone', 'UTC') == $timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Language</label>
                                        <select name="site_language" class="form-select">
                                            <option value="en" {{ setting('site_language', 'en') == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="es" {{ setting('site_language', 'en') == 'es' ? 'selected' : '' }}>Spanish</option>
                                            <option value="fr" {{ setting('site_language', 'en') == 'fr' ? 'selected' : '' }}>French</option>
                                            <option value="de" {{ setting('site_language', 'en') == 'de' ? 'selected' : '' }}>German</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="maintenance_mode" 
                                                   id="maintenance_mode" {{ setting('maintenance_mode', false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="maintenance_mode">
                                                Enable Maintenance Mode
                                            </label>
                                            <small class="text-muted d-block">When enabled, only admins can access the site</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping & Services -->
                    <div class="tab-pane fade" id="shipping">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-ship-line me-2"></i>Shipping & Services Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Available Shipping Services</label>
                                        <div class="border rounded p-3">
                                            @php
                                                $services = setting('shipping_services', ['express', 'standard', 'economy']);
                                            @endphp
                                            @foreach(['express' => 'Express (1-3 days)', 'standard' => 'Standard (5-7 days)', 'economy' => 'Economy (10-14 days)'] as $key => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="shipping_services[]" 
                                                       value="{{ $key }}" id="service_{{ $key }}"
                                                       {{ in_array($key, $services) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="service_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Default Shipping Service</label>
                                        <select name="default_shipping_service" class="form-select">
                                            @foreach(['express' => 'Express', 'standard' => 'Standard', 'economy' => 'Economy'] as $key => $label)
                                            <option value="{{ $key }}" {{ setting('default_shipping_service', 'standard') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Max Weight per Shipment (kg)</label>
                                        <input type="number" name="max_weight_kg" class="form-control" 
                                               value="{{ setting('max_weight_kg', 50) }}" step="0.1" min="0.1">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Max Dimension (cm)</label>
                                        <input type="number" name="max_dimension_cm" class="form-control" 
                                               value="{{ setting('max_dimension_cm', 150) }}" step="1" min="1">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dimensional Weight Divisor</label>
                                        <input type="number" name="dimensional_weight_divisor" class="form-control" 
                                               value="{{ setting('dimensional_weight_divisor', 5000) }}" step="1" min="1">
                                        <small class="text-muted">Formula: (L × W × H) / divisor = dimensional weight</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_customs_declaration" 
                                                   id="enable_customs" {{ setting('enable_customs_declaration', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_customs">
                                                Enable Customs Declaration
                                            </label>
                                        </div>
                                        
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="require_id_verification" 
                                                   id="require_id" {{ setting('require_id_verification', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="require_id">
                                                Require ID Verification for International Shipments
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Settings -->
                    <div class="tab-pane fade" id="payment">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-money-dollar-circle-line me-2"></i>Payment Settings</h6>
                            </div>
                            <div class="card-body">
                                <h6 class="border-bottom pb-2 mb-3">Invoice Settings</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Invoice Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control" 
                                               value="{{ setting('invoice_prefix', 'GSF') }}">
                                        <small class="text-muted">e.g., GSF20240001</small>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Default Due Days</label>
                                        <input type="number" name="invoice_due_days" class="form-control" 
                                               value="{{ setting('invoice_due_days', 30) }}" min="1" max="365">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Default Terms</label>
                                        <textarea name="invoice_terms" class="form-control" rows="1">{{ setting('invoice_terms', 'Payment due within 30 days') }}</textarea>
                                    </div>
                                </div>
                                
                                <h6 class="border-bottom pb-2 mb-3">Payment Limits</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Minimum Payment Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="min_payment_amount" class="form-control" 
                                                   value="{{ setting('min_payment_amount', 10) }}" step="0.01" min="0.01">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Maximum Payment Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="max_payment_amount" class="form-control" 
                                                   value="{{ setting('max_payment_amount', 10000) }}" step="0.01" min="0.01">
                                        </div>
                                    </div>
                                </div>
                                
                                <h6 class="border-bottom pb-2 mb-3">Crypto Payment Settings</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="enable_crypto_payments" 
                                                   id="enable_crypto" {{ setting('enable_crypto_payments', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_crypto">
                                                Enable Crypto Payments
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Crypto Payment Expiry (hours)</label>
                                        <input type="number" name="crypto_expiry_hours" class="form-control" 
                                               value="{{ setting('crypto_expiry_hours', 24) }}" min="1" max="168">
                                        <small class="text-muted">Time before pending crypto payments expire</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email & Notifications -->
                    <div class="tab-pane fade" id="email">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-mail-line me-2"></i>Email Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">From Name</label>
                                        <input type="text" name="mail_from_name" class="form-control" 
                                               value="{{ setting('mail_from_name', 'GlobalSkyFleet') }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">From Address</label>
                                        <input type="email" name="mail_from_address" class="form-control" 
                                               value="{{ setting('mail_from_address', 'noreply@globalskyfleet.com') }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Host</label>
                                        <input type="text" name="mail_host" class="form-control" 
                                               value="{{ setting('mail_host', env('MAIL_HOST', '')) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="number" name="mail_port" class="form-control" 
                                               value="{{ setting('mail_port', env('MAIL_PORT', 587)) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" name="mail_username" class="form-control" 
                                               value="{{ setting('mail_username', env('MAIL_USERNAME', '')) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="password" name="mail_password" class="form-control" 
                                               value="{{ setting('mail_password', '') }}" 
                                               placeholder="Leave blank to keep current">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Encryption</label>
                                        <select name="mail_encryption" class="form-select">
                                            <option value="" {{ setting('mail_encryption', env('MAIL_ENCRYPTION', 'tls')) == '' ? 'selected' : '' }}>None</option>
                                            <option value="ssl" {{ setting('mail_encryption', env('MAIL_ENCRYPTION', 'tls')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="tls" {{ setting('mail_encryption', env('MAIL_ENCRYPTION', 'tls')) == 'tls' ? 'selected' : '' }}>TLS</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mail Driver</label>
                                        <select name="mail_driver" class="form-select">
                                            <option value="smtp" {{ setting('mail_driver', env('MAIL_MAILER', 'smtp')) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="mailgun" {{ setting('mail_driver', env('MAIL_MAILER', 'smtp')) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                            <option value="ses" {{ setting('mail_driver', env('MAIL_MAILER', 'smtp')) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                            <option value="postmark" {{ setting('mail_driver', env('MAIL_MAILER', 'smtp')) == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                            <option value="log" {{ setting('mail_driver', env('MAIL_MAILER', 'smtp')) == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-notification-3-line me-2"></i>Notification Preferences</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_shipment_created" 
                                                   id="notify_shipment_created" {{ setting('notify_shipment_created', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_shipment_created">
                                                Notify on Shipment Creation
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_shipment_status" 
                                                   id="notify_shipment_status" {{ setting('notify_shipment_status', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_shipment_status">
                                                Notify on Status Changes
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_payment_received" 
                                                   id="notify_payment_received" {{ setting('notify_payment_received', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_payment_received">
                                                Notify on Payment Received
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_invoice_created" 
                                                   id="notify_invoice_created" {{ setting('notify_invoice_created', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_invoice_created">
                                                Notify on Invoice Creation
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_document_uploaded" 
                                                   id="notify_document_uploaded" {{ setting('notify_document_uploaded', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notify_document_uploaded">
                                                Notify on Document Upload
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Settings -->
                    <div class="tab-pane fade" id="security">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-shield-keyhole-line me-2"></i>Security Settings</h6>
                            </div>
                            <div class="card-body">
                                <h6 class="border-bottom pb-2 mb-3">Password Policies</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Minimum Password Length</label>
                                        <input type="number" name="password_min_length" class="form-control" 
                                               value="{{ setting('password_min_length', 8) }}" min="6" max="32">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="password_require_numbers" 
                                                   id="require_numbers" {{ setting('password_require_numbers', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="require_numbers">
                                                Require Numbers
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="password_require_special_chars" 
                                                   id="require_special" {{ setting('password_require_special_chars', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="require_special">
                                                Require Special Characters
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <h6 class="border-bottom pb-2 mb-3">Session & Login Security</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Session Lifetime (minutes)</label>
                                        <input type="number" name="session_lifetime" class="form-control" 
                                               value="{{ setting('session_lifetime', 120) }}" min="1" max="1440">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Max Login Attempts</label>
                                        <input type="number" name="max_login_attempts" class="form-control" 
                                               value="{{ setting('max_login_attempts', 5) }}" min="1" max="20">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lockout Time (minutes)</label>
                                        <input type="number" name="lockout_time" class="form-control" 
                                               value="{{ setting('lockout_time', 15) }}" min="1" max="1440">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="enable_2fa" 
                                                   id="enable_2fa" {{ setting('enable_2fa', false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enable_2fa">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Tools -->
                    <div class="tab-pane fade" id="tools">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0"><i class="ri-tools-line me-2"></i>System Tools</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light border">
                                            <div class="card-body">
                                                <h6 class="card-title">Clear Cache</h6>
                                                <p class="card-text text-muted small">
                                                    Clear application cache, config cache, and view cache.
                                                    Useful after updating settings.
                                                </p>
                                                <a href="{{ route('admin.settings.clear-cache') }}" 
                                                   class="btn btn-warning" 
                                                   onclick="return confirm('Are you sure you want to clear all cache?')">
                                                    <i class="ri-refresh-line me-2"></i>Clear Cache
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light border">
                                            <div class="card-body">
                                                <h6 class="card-title">Backup Database</h6>
                                                <p class="card-text text-muted small">
                                                    Create a backup of your database.
                                                    Ensure you have backup configured properly.
                                                </p>
                                                <a href="{{ route('admin.settings.backup') }}" 
                                                   class="btn btn-success" 
                                                   onclick="return confirm('Create a database backup?')">
                                                    <i class="ri-database-line me-2"></i>Backup Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line me-2"></i>
                                            <strong>Note:</strong> System tools require proper server configuration and permissions.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-save-line me-2"></i>Save All Settings
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">
                        <i class="ri-refresh-line me-2"></i>Reset Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-pills .nav-link {
        color: #495057;
        border-radius: 0.375rem;
        margin-bottom: 0.25rem;
        padding: 0.75rem 1rem;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0a2463;
        color: white;
    }
    
    .tab-content {
        min-height: 500px;
    }
    
    .form-check.form-switch {
        padding-left: 3.5em;
    }
    
    .form-check-input:checked {
        background-color: #0a2463;
        border-color: #0a2463;
    }
</style>
@endpush