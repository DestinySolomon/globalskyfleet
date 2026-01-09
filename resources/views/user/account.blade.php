@extends('layouts.dashboard')

@section('title', 'Account Settings | GlobalSkyFleet')
@section('page-title', 'Account Settings')

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-line me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column: Navigation -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
                            <i class="ri-user-line me-2"></i>My Profile
                        </a>
                        <a href="{{ route('user.account') }}" class="list-group-item list-group-item-action active">
                            <i class="ri-settings-3-line me-2"></i>Account Settings
                        </a>
                        <a href="{{ route('user.security') }}" class="list-group-item list-group-item-action">
                            <i class="ri-shield-keyhole-line me-2"></i>Privacy & Security
                        </a>
                        <div class="list-group-item">
                            <small class="text-muted">QUICK LINKS</small>
                        </div>
                        <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action">
                            <i class="ri-map-pin-line me-2"></i>Address Book
                        </a>
                        <a href="{{ route('billing.index') }}" class="list-group-item list-group-item-action">
                            <i class="ri-bill-line me-2"></i>Billing & Payments
                        </a>
                        <a href="{{ route('documents.index') }}" class="list-group-item list-group-item-action">
                            <i class="ri-file-text-line me-2"></i>Documents
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Account Summary -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Account Summary</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-user-line text-muted me-2"></i>
                        <small class="text-muted">Account Type:</small>
                        <span class="ms-auto badge bg-primary-subtle text-primary">Business</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-calendar-line text-muted me-2"></i>
                        <small class="text-muted">Member Since:</small>
                        <span class="ms-auto">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ri-time-line text-muted me-2"></i>
                        <small class="text-muted">Last Login:</small>
                        <span class="ms-auto">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Account Settings Forms -->
        <div class="col-lg-9 col-md-8">
            <!-- Email & Basic Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-mail-line me-2"></i>Email & Basic Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.account.update') }}" method="POST" id="accountForm">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->email_verified_at)
                                <div class="form-text text-success">
                                    <i class="ri-checkbox-circle-line me-1"></i>Email verified
                                </div>
                                @else
                                <div class="form-text text-warning">
                                    <i class="ri-alert-line me-1"></i>Email not verified
                                </div>
                                @endif
                            </div>
                            
                            <!-- Timezone -->
                            <div class="col-md-6">
                                <label class="form-label">Timezone *</label>
                                <select name="timezone" class="form-select @error('timezone') is-invalid @enderror" required>
                                    <option value="">Select Timezone</option>
                                    @php
                                        $timezones = [
                                            'UTC' => 'UTC',
                                            'America/New_York' => 'Eastern Time (US & Canada)',
                                            'America/Chicago' => 'Central Time (US & Canada)',
                                            'America/Denver' => 'Mountain Time (US & Canada)',
                                            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
                                            'Europe/London' => 'London',
                                            'Europe/Paris' => 'Paris',
                                            'Europe/Berlin' => 'Berlin',
                                            'Asia/Dubai' => 'Dubai',
                                            'Asia/Kolkata' => 'India',
                                            'Asia/Singapore' => 'Singapore',
                                            'Asia/Tokyo' => 'Tokyo',
                                            'Australia/Sydney' => 'Sydney',
                                        ];
                                    @endphp
                                    @foreach($timezones as $value => $label)
                                    <option value="{{ $value }}" {{ old('timezone', $user->display_preferences['timezone'] ?? 'UTC') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Language -->
                            <div class="col-md-6">
                                <label class="form-label">Language *</label>
                                <select name="language" class="form-select @error('language') is-invalid @enderror" required>
                                    <option value="en" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="fr" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="es" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="de" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'de' ? 'selected' : '' }}>German</option>
                                    <option value="zh" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'zh' ? 'selected' : '' }}>Chinese</option>
                                    <option value="ar" {{ old('language', $user->display_preferences['language'] ?? 'en') == 'ar' ? 'selected' : '' }}>Arabic</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Currency -->
                            <div class="col-md-6">
                                <label class="form-label">Currency *</label>
                                <select name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="USD" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                    <option value="EUR" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    <option value="GBP" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                                    <option value="JPY" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'JPY' ? 'selected' : '' }}>Japanese Yen (JPY)</option>
                                    <option value="AUD" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'AUD' ? 'selected' : '' }}>Australian Dollar (AUD)</option>
                                    <option value="CAD" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'CAD' ? 'selected' : '' }}>Canadian Dollar (CAD)</option>
                                    <option value="CHF" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'CHF' ? 'selected' : '' }}>Swiss Franc (CHF)</option>
                                    <option value="CNY" {{ old('currency', $user->display_preferences['currency'] ?? 'USD') == 'CNY' ? 'selected' : '' }}>Chinese Yuan (CNY)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Date Format -->
                            <div class="col-md-6">
                                <label class="form-label">Date Format *</label>
                                <select name="date_format" class="form-select @error('date_format') is-invalid @enderror" required>
                                    <option value="m/d/Y" {{ old('date_format', $user->display_preferences['date_format'] ?? 'm/d/Y') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d/m/Y" {{ old('date_format', $user->display_preferences['date_format'] ?? 'm/d/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="Y-m-d" {{ old('date_format', $user->display_preferences['date_format'] ?? 'm/d/Y') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                </select>
                                @error('date_format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Time Format -->
                            <div class="col-md-6">
                                <label class="form-label">Time Format *</label>
                                <select name="time_format" class="form-select @error('time_format') is-invalid @enderror" required>
                                    <option value="12h" {{ old('time_format', $user->display_preferences['time_format'] ?? '12h') == '12h' ? 'selected' : '' }}>12-hour (AM/PM)</option>
                                    <option value="24h" {{ old('time_format', $user->display_preferences['time_format'] ?? '12h') == '24h' ? 'selected' : '' }}>24-hour</option>
                                </select>
                                @error('time_format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Notification Settings -->
                        <div class="mt-5">
                            <h6 class="mb-3 border-bottom pb-2">
                                <i class="ri-notification-3-line me-2"></i>Notification Settings
                            </h6>
                            <div class="row g-3">
                                @php
                                    $notifications = $user->notification_settings;
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications" value="1" 
                                            {{ old('email_notifications', $notifications['email_notifications']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                        <div class="form-text">Receive important updates via email</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="sms_notifications" id="sms_notifications" value="1"
                                            {{ old('sms_notifications', $notifications['sms_notifications']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms_notifications">
                                            SMS Notifications
                                        </label>
                                        <div class="form-text">Receive shipment updates via SMS</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="shipment_updates" id="shipment_updates" value="1"
                                            {{ old('shipment_updates', $notifications['shipment_updates']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipment_updates">
                                            Shipment Updates
                                        </label>
                                        <div class="form-text">Get notified about shipment status changes</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="promotional_emails" id="promotional_emails" value="1"
                                            {{ old('promotional_emails', $notifications['promotional_emails']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="promotional_emails">
                                            Promotional Emails
                                        </label>
                                        <div class="form-text">Receive offers and promotions</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="billing_notifications" id="billing_notifications" value="1"
                                            {{ old('billing_notifications', $notifications['billing_notifications']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billing_notifications">
                                            Billing Notifications
                                        </label>
                                        <div class="form-text">Get notified about invoices and payments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="ri-refresh-line me-2"></i>Reset Changes
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-2"></i>Save Account Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Default Addresses -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-map-pin-line me-2"></i>Default Addresses
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Default Shipping Address -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="mb-3">Default Shipping Address</h6>
                            @if($defaultShipping)
                            <div class="address-card p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong>{{ $defaultShipping->contact_name }}</strong>
                                    <span class="badge bg-primary">Shipping</span>
                                </div>
                                <p class="mb-2">
                                    {{ $defaultShipping->address_line1 }}<br>
                                    @if($defaultShipping->address_line2)
                                    {{ $defaultShipping->address_line2 }}<br>
                                    @endif
                                    {{ $defaultShipping->city }}, {{ $defaultShipping->state }} {{ $defaultShipping->postal_code }}<br>
                                    {{ $defaultShipping->country_code }}
                                </p>
                                <div class="text-muted small">
                                    <i class="ri-phone-line me-1"></i>{{ $defaultShipping->contact_phone }}
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('addresses.edit', $defaultShipping) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-edit-line me-1"></i>Edit
                                    </a>
                                    <a href="{{ route('addresses.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                        <i class="ri-list-check me-1"></i>Manage All
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4 border rounded">
                                <i class="ri-map-pin-line text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-3">No default shipping address set</p>
                                <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-primary">
                                    <i class="ri-add-line me-1"></i>Add Shipping Address
                                </a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Default Billing Address -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Default Billing Address</h6>
                            @if($defaultBilling)
                            <div class="address-card p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong>{{ $defaultBilling->contact_name }}</strong>
                                    <span class="badge bg-success">Billing</span>
                                </div>
                                <p class="mb-2">
                                    {{ $defaultBilling->address_line1 }}<br>
                                    @if($defaultBilling->address_line2)
                                    {{ $defaultBilling->address_line2 }}<br>
                                    @endif
                                    {{ $defaultBilling->city }}, {{ $defaultBilling->state }} {{ $defaultBilling->postal_code }}<br>
                                    {{ $defaultBilling->country_code }}
                                </p>
                                <div class="text-muted small">
                                    <i class="ri-phone-line me-1"></i>{{ $defaultBilling->contact_phone }}
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('addresses.edit', $defaultBilling) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-edit-line me-1"></i>Edit
                                    </a>
                                    <a href="{{ route('addresses.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                        <i class="ri-list-check me-1"></i>Manage All
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4 border rounded">
                                <i class="ri-bill-line text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-3">No default billing address set</p>
                                <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-primary">
                                    <i class="ri-add-line me-1"></i>Add Billing Address
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-switch .form-check-input {
    height: 1.5em;
    width: 3em;
}

.form-switch .form-check-input:checked {
    background-color: var(--navy);
    border-color: var(--navy);
}

.address-card {
    transition: all 0.3s;
    border-left: 4px solid transparent;
}

.address-card:hover {
    border-left-color: var(--navy);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.list-group-item.active {
    background-color: var(--navy);
    border-color: var(--navy);
}

.list-group-item.active:hover {
    background-color: var(--navy-light);
    border-color: var(--navy-light);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reset form confirmation
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reset all changes? Any unsaved changes will be lost.')) {
                e.preventDefault();
            }
        });
    }
    
    // Email change warning
    const emailInput = document.querySelector('input[name="email"]');
    const originalEmail = emailInput?.value;
    
    if (emailInput && originalEmail) {
        emailInput.addEventListener('change', function() {
            if (this.value !== originalEmail) {
                alert('Note: Changing your email address will require re-verification.');
            }
        });
    }
});
</script>
@endpush
@endsection