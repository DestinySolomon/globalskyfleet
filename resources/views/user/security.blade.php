@extends('layouts.dashboard')

@section('title', 'Privacy & Security | GlobalSkyFleet')
@section('page-title', 'Privacy & Security')

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
                        <a href="{{ route('user.account') }}" class="list-group-item list-group-item-action">
                            <i class="ri-settings-3-line me-2"></i>Account Settings
                        </a>
                        <a href="{{ route('user.security') }}" class="list-group-item list-group-item-action active">
                            <i class="ri-shield-keyhole-line me-2"></i>Privacy & Security
                        </a>
                        <div class="list-group-item">
                            <small class="text-muted">SECURITY</small>
                        </div>
                        <a href="#passwordSection" class="list-group-item list-group-item-action">
                            <i class="ri-lock-line me-2"></i>Change Password
                        </a>
                        <a href="#twoFactorSection" class="list-group-item list-group-item-action">
                            <i class="ri-shield-user-line me-2"></i>Two-Factor Auth
                        </a>
                        <a href="#sessionsSection" class="list-group-item list-group-item-action">
                            <i class="ri-computer-line me-2"></i>Active Sessions
                        </a>
                        <a href="#dataSection" class="list-group-item list-group-item-action">
                            <i class="ri-database-2-line me-2"></i>Data Management
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Security Status -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Security Status</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        <small class="text-muted">Password:</small>
                        @if($user->passwordRecentlyChanged())
                        <span class="ms-auto badge bg-success-subtle text-success">Strong</span>
                        @else
                        <span class="ms-auto badge bg-warning-subtle text-warning">Update Recommended</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="{{ $user->two_factor_enabled ? 'ri-checkbox-circle-line text-success' : 'ri-close-circle-line text-danger' }} me-2"></i>
                        <small class="text-muted">2FA:</small>
                        <span class="ms-auto badge {{ $user->two_factor_enabled ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="{{ $user->email_verified_at ? 'ri-checkbox-circle-line text-success' : 'ri-alert-line text-warning' }} me-2"></i>
                        <small class="text-muted">Email:</small>
                        <span class="ms-auto">{{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ri-computer-line text-muted me-2"></i>
                        <small class="text-muted">Active Sessions:</small>
                        <span class="ms-auto">{{ $activeSessions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Security Settings -->
        <div class="col-lg-9 col-md-8">
            <!-- Change Password -->
            <div class="card border-0 shadow-sm mb-4" id="passwordSection">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-lock-line me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(!$user->passwordRecentlyChanged())
                    <div class="alert alert-warning mb-4">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Security Recommendation:</strong> It's been more than 90 days since your last password change. Consider updating your password for better security.
                    </div>
                    @endif
                    
                    <form action="{{ route('user.password.update') }}" method="POST" id="passwordForm">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Current Password -->
                            <div class="col-md-6">
                                <label class="form-label">Current Password *</label>
                                <input type="password" 
                                       name="current_password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- New Password -->
                            <div class="col-md-6">
                                <label class="form-label">New Password *</label>
                                <input type="password" 
                                       name="new_password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Must be at least 8 characters long
                                </div>
                            </div>
                            
                            <!-- Confirm New Password -->
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password *</label>
                                <input type="password" 
                                       name="new_password_confirmation" 
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       required>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Strength -->
                            <div class="col-md-6">
                                <label class="form-label">Password Strength</label>
                                <div class="password-strength-meter">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="passwordStrengthFill"></div>
                                    </div>
                                    <div class="strength-text small mt-1" id="passwordStrengthText"></div>
                                </div>
                            </div>
                            
                            <!-- Password Requirements -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Password Requirements:</h6>
                                    <ul class="mb-0">
                                        <li>At least 8 characters long</li>
                                        <li>Should contain uppercase and lowercase letters</li>
                                        <li>Should contain at least one number</li>
                                        <li>Should contain at least one special character (!@#$%^&*)</li>
                                        <li>Should be different from your current password</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="col-12 pt-3 border-top">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-key-line me-2"></i>Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Two-Factor Authentication -->
            <div class="card border-0 shadow-sm mb-4" id="twoFactorSection">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-shield-user-line me-2"></i>Two-Factor Authentication
                        </h5>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="twoFactorToggle" 
                                   {{ $user->two_factor_enabled ? 'checked' : '' }}
                                   style="height: 1.5em; width: 3em;">
                            <label class="form-check-label" for="twoFactorToggle">
                                {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6>Enhance your account security</h6>
                            <p class="text-muted mb-0">
                                Two-factor authentication adds an extra layer of security to your account. 
                                When enabled, you'll need to enter a verification code from your authenticator app in addition to your password.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" 
                                    class="btn {{ $user->two_factor_enabled ? 'btn-outline-danger' : 'btn-outline-primary' }}" 
                                    id="toggle2FABtn"
                                    data-enabled="{{ $user->two_factor_enabled ? '1' : '0' }}">
                                <i class="ri-{{ $user->two_factor_enabled ? 'close' : 'shield-check' }}-line me-2"></i>
                                {{ $user->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Recovery Codes (only shown when enabling) -->
                    <div id="recoveryCodesSection" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            <strong>Two-factor authentication has been enabled!</strong>
                        </div>
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="ri-alert-line me-2"></i>Save Your Recovery Codes
                            </h6>
                            <p class="mb-2">Save these recovery codes in a secure place. You can use them to regain access to your account if you lose your authenticator device.</p>
                            <div id="recoveryCodesList" class="p-3 bg-light rounded mb-3"></div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="copyRecoveryCodes">
                                    <i class="ri-file-copy-line me-1"></i>Copy Codes
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" id="downloadRecoveryCodes">
                                    <i class="ri-download-line me-1"></i>Download as Text
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Sessions -->
            <div class="card border-0 shadow-sm mb-4" id="sessionsSection">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-computer-line me-2"></i>Active Sessions
                        </h5>
                        @if($activeSessions->count() > 1)
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#terminateAllModal">
                            <i class="ri-logout-circle-r-line me-1"></i>Terminate All Other Sessions
                        </button>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($activeSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Device & Browser</th>
                                    <th>IP Address</th>
                                    <th>Last Activity</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeSessions as $session)
                                <tr class="{{ $session->id === session()->getId() ? 'table-active' : '' }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if(str_contains(strtolower($session->user_agent), 'windows'))
                                                <i class="ri-windows-fill text-primary"></i>
                                                @elseif(str_contains(strtolower($session->user_agent), 'mac'))
                                                <i class="ri-apple-fill text-secondary"></i>
                                                @elseif(str_contains(strtolower($session->user_agent), 'linux'))
                                                <i class="ri-ubuntu-fill text-warning"></i>
                                                @elseif(str_contains(strtolower($session->user_agent), 'android'))
                                                <i class="ri-android-fill text-success"></i>
                                                @elseif(str_contains(strtolower($session->user_agent), 'iphone') || str_contains(strtolower($session->user_agent), 'ipad'))
                                                <i class="ri-apple-fill text-dark"></i>
                                                @else
                                                <i class="ri-computer-line text-muted"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-medium">
                                                    @php
                                                        $device = 'Unknown Device';
                                                        if (str_contains(strtolower($session->user_agent), 'mobile')) {
                                                            $device = 'Mobile Device';
                                                        } elseif (str_contains(strtolower($session->user_agent), 'tablet')) {
                                                            $device = 'Tablet';
                                                        } elseif (str_contains(strtolower($session->user_agent), 'windows') || str_contains(strtolower($session->user_agent), 'mac') || str_contains(strtolower($session->user_agent), 'linux')) {
                                                            $device = 'Desktop';
                                                        }
                                                    @endphp
                                                    {{ $device }}
                                                </div>
                                                <small class="text-muted">
                                                    @php
                                                        $browser = 'Unknown Browser';
                                                        if (str_contains(strtolower($session->user_agent), 'chrome')) {
                                                            $browser = 'Chrome';
                                                        } elseif (str_contains(strtolower($session->user_agent), 'firefox')) {
                                                            $browser = 'Firefox';
                                                        } elseif (str_contains(strtolower($session->user_agent), 'safari') && !str_contains(strtolower($session->user_agent), 'chrome')) {
                                                            $browser = 'Safari';
                                                        } elseif (str_contains(strtolower($session->user_agent), 'edge')) {
                                                            $browser = 'Edge';
                                                        }
                                                    @endphp
                                                    {{ $browser }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $session->ip_address }}</code>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                    </td>
                                    <td class="pe-4">
                                        @if($session->id === session()->getId())
                                        <span class="badge bg-success">Current Session</span>
                                        @else
                                        <form action="{{ route('user.sessions.terminate', $session->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ri-logout-circle-r-line me-1"></i>Terminate
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ri-computer-line text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No active sessions</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Data Management -->
            <div class="card border-0 shadow-sm" id="dataSection">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-database-2-line me-2"></i>Data Management
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Export Data -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="mb-3">Export Your Data</h6>
                            <p class="text-muted mb-3">
                                Download a copy of your personal data including profile information, addresses, shipments, and invoices.
                            </p>
                            <a href="{{ route('user.data.export') }}" class="btn btn-outline-primary">
                                <i class="ri-download-line me-2"></i>Export Data (JSON)
                            </a>
                        </div>
                        
                        <!-- Account Deactivation -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-danger">Account Deactivation</h6>
                            <p class="text-muted mb-3">
                                Deactivate your account. This will remove your access to the platform. Some data may be retained for legal reasons.
                            </p>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                <i class="ri-user-unfollow-line me-2"></i>Deactivate Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terminate All Sessions Modal -->
<div class="modal fade" id="terminateAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminate All Other Sessions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This will log you out from all other devices except this one.</p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-2"></i>
                    <strong>Warning:</strong> You will need to log in again on all other devices.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.sessions.terminate-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-logout-circle-r-line me-2"></i>Terminate All Other Sessions
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Deactivate Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>By deactivating your account:</p>
                <ul>
                    <li>You will lose access to all your shipments and documents</li>
                    <li>Your account will be marked as inactive</li>
                    <li>Some data may be retained for legal and compliance reasons</li>
                    <li>You will need to contact support to reactivate your account</li>
                </ul>
                
                <div class="mt-4">
                    <label class="form-label">Enter your password to confirm:</label>
                    <input type="password" class="form-control" id="deactivatePassword" placeholder="Current password">
                </div>
                
                <div class="mt-3">
                    <label class="form-label">Type "DEACTIVATE MY ACCOUNT" to confirm:</label>
                    <input type="text" class="form-control" id="deactivateConfirmation" placeholder="DEACTIVATE MY ACCOUNT">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.account.deactivate') }}" method="POST" id="deactivateForm">
                    @csrf
                    <input type="hidden" name="password" id="hiddenPassword">
                    <input type="hidden" name="confirmation" id="hiddenConfirmation">
                    <button type="submit" class="btn btn-danger" id="deactivateSubmit" disabled>
                        <i class="ri-user-unfollow-line me-2"></i>Deactivate Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.password-strength-meter {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 3px;
}

.strength-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    transition: width 0.3s, background-color 0.3s;
}

.table-active {
    background-color: rgba(10, 36, 99, 0.05) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength meter
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const strengthFill = document.getElementById('passwordStrengthFill');
    const strengthText = document.getElementById('passwordStrengthText');
    
    if (newPasswordInput && strengthFill && strengthText) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = 'Very Weak';
            let color = '#dc3545';
            
            // Check length
            if (password.length >= 8) strength += 25;
            
            // Check for mixed case
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            
            // Check for numbers
            if (/[0-9]/.test(password)) strength += 25;
            
            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            // Update display
            strengthFill.style.width = strength + '%';
            
            if (strength >= 75) {
                text = 'Strong';
                color = '#198754';
            } else if (strength >= 50) {
                text = 'Good';
                color = '#ffc107';
            } else if (strength >= 25) {
                text = 'Weak';
                color = '#fd7e14';
            }
            
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });
    }
    
    // Two-Factor Authentication toggle
    const toggle2FABtn = document.getElementById('toggle2FABtn');
    const twoFactorToggle = document.getElementById('twoFactorToggle');
    
    if (toggle2FABtn) {
        toggle2FABtn.addEventListener('click', function() {
            const isEnabled = this.dataset.enabled === '1';
            
            if (isEnabled) {
                // Disable 2FA
                if (confirm('Are you sure you want to disable two-factor authentication? This reduces your account security.')) {
                    const password = prompt('Enter your password to disable two-factor authentication:');
                    if (password) {
                        toggle2FA(false, password);
                    }
                }
            } else {
                // Enable 2FA
                const password = prompt('Enter your password to enable two-factor authentication:');
                if (password) {
                    toggle2FA(true, password);
                }
            }
        });
    }
    
    function toggle2FA(enable, password = null) {
        const formData = new FormData();
        formData.append('enable', enable ? '1' : '0');
        if (password) formData.append('password', password);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("user.two-factor.toggle") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                toggle2FABtn.dataset.enabled = enable ? '1' : '0';
                toggle2FABtn.innerHTML = `<i class="ri-${enable ? 'close' : 'shield-check'}-line me-2"></i>${enable ? 'Disable 2FA' : 'Enable 2FA'}`;
                toggle2FABtn.className = `btn ${enable ? 'btn-outline-danger' : 'btn-outline-primary'}`;
                twoFactorToggle.checked = enable;
                
                // Show recovery codes if enabled
                if (enable && data.recovery_codes) {
                    const codesList = document.getElementById('recoveryCodesList');
                    const recoveryCodesSection = document.getElementById('recoveryCodesSection');
                    
                    codesList.innerHTML = data.recovery_codes.map(code => 
                        `<div class="mb-1"><code>${code}</code></div>`
                    ).join('');
                    
                    recoveryCodesSection.style.display = 'block';
                    
                    // Scroll to recovery codes
                    recoveryCodesSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    document.getElementById('recoveryCodesSection').style.display = 'none';
                }
                
                // Show success message
                alert(data.message);
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    // Copy recovery codes
    const copyRecoveryCodesBtn = document.getElementById('copyRecoveryCodes');
    if (copyRecoveryCodesBtn) {
        copyRecoveryCodesBtn.addEventListener('click', function() {
            const codes = document.querySelectorAll('#recoveryCodesList code');
            const codesText = Array.from(codes).map(code => code.textContent).join('\n');
            
            navigator.clipboard.writeText(codesText).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="ri-check-line me-1"></i>Copied!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    }
    
    // Download recovery codes
    const downloadRecoveryCodesBtn = document.getElementById('downloadRecoveryCodes');
    if (downloadRecoveryCodesBtn) {
        downloadRecoveryCodesBtn.addEventListener('click', function() {
            const codes = document.querySelectorAll('#recoveryCodesList code');
            const codesText = Array.from(codes).map(code => code.textContent).join('\n');
            const blob = new Blob([codesText], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'recovery-codes.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    }
    
    // Account deactivation validation
    const deactivatePassword = document.getElementById('deactivatePassword');
    const deactivateConfirmation = document.getElementById('deactivateConfirmation');
    const deactivateSubmit = document.getElementById('deactivateSubmit');
    const hiddenPassword = document.getElementById('hiddenPassword');
    const hiddenConfirmation = document.getElementById('hiddenConfirmation');
    
    if (deactivatePassword && deactivateConfirmation && deactivateSubmit) {
        function validateDeactivation() {
            const passwordValid = deactivatePassword.value.length > 0;
            const confirmationValid = deactivateConfirmation.value === 'DEACTIVATE MY ACCOUNT';
            
            deactivateSubmit.disabled = !(passwordValid && confirmationValid);
            
            if (hiddenPassword) hiddenPassword.value = deactivatePassword.value;
            if (hiddenConfirmation) hiddenConfirmation.value = deactivateConfirmation.value;
        }
        
        deactivatePassword.addEventListener('input', validateDeactivation);
        deactivateConfirmation.addEventListener('input', validateDeactivation);
    }
    
    // Session termination confirmation
    document.querySelectorAll('form[action*="terminate"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to terminate this session?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
@endsection