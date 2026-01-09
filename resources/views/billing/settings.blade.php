@extends('layouts.dashboard')

@section('title', 'Billing Settings - GlobalSkyFleet')

@section('page-title', 'Billing Settings')

@section('content')
<div class="billing-settings-page">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Billing Settings</h1>
            <p class="text-muted mb-0">Manage your billing preferences and notifications</p>
        </div>
        <div>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Back to Billing
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Settings -->
        <div class="col-lg-8">
            <!-- Notification Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form id="notificationForm">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="invoiceNotifications" checked>
                                <label class="form-check-label" for="invoiceNotifications">
                                    New Invoice Notifications
                                </label>
                                <p class="text-muted small mb-0">Receive email when a new invoice is issued</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="paymentNotifications" checked>
                                <label class="form-check-label" for="paymentNotifications">
                                    Payment Status Updates
                                </label>
                                <p class="text-muted small mb-0">Receive notifications when payment status changes</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overdueNotifications" checked>
                                <label class="form-check-label" for="overdueNotifications">
                                    Overdue Invoice Reminders
                                </label>
                                <p class="text-muted small mb-0">Receive reminders for overdue invoices</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="weeklySummary" checked>
                                <label class="form-check-label" for="weeklySummary">
                                    Weekly Billing Summary
                                </label>
                                <p class="text-muted small mb-0">Receive weekly summary of all billing activities</p>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="saveNotifications">
                                <i class="ri-save-line"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Preferences -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Payment Preferences</h5>
                </div>
                <div class="card-body">
                    <form id="paymentPreferencesForm">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Default Payment Method</label>
                            <select class="form-select" id="defaultPaymentMethod">
                                <option value="BTC">Bitcoin (BTC)</option>
                                <option value="USDT_ERC20" selected>USDT (ERC-20)</option>
                                <option value="USDT_TRC20">USDT (TRC-20)</option>
                            </select>
                            <p class="text-muted small mt-1">This will be selected automatically when making payments</p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Auto-Payment (Coming Soon)</label>
                            <div class="alert alert-info">
                                <i class="ri-information-line"></i> Auto-payment feature is currently under development. You'll be notified when it becomes available.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="savePaymentPreferences">
                                <i class="ri-save-line"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Information -->
        <div class="col-lg-4">
            <!-- Billing Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Billing Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Account Type</h6>
                        <div class="d-flex align-items-center">
                            <i class="ri-user-line text-primary me-2"></i>
                            <span class="fw-semibold">Standard Account</span>
                        </div>
                        <p class="small text-muted mt-1">All features included</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Billing Cycle</h6>
                        <div class="d-flex align-items-center">
                            <i class="ri-calendar-line text-primary me-2"></i>
                            <span class="fw-semibold">Pay Per Shipment</span>
                        </div>
                        <p class="small text-muted mt-1">Invoices generated after each shipment</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Currency</h6>
                        <div class="d-flex align-items-center">
                            <i class="ri-money-dollar-circle-line text-primary me-2"></i>
                            <span class="fw-semibold">USD (US Dollar)</span>
                        </div>
                        <p class="small text-muted mt-1">All invoices are in US Dollars</p>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary w-100">
                            <i class="ri-customer-service-2-line"></i> Contact Billing Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('billing.invoices') }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-bill-line text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">View All Invoices</h6>
                                    <p class="text-muted small mb-0">Browse your complete invoice history</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="{{ route('billing.payments') }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-history-line text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">Payment History</h6>
                                    <p class="text-muted small mb-0">Track all your cryptocurrency payments</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="{{ route('documents.index') }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-text-line text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">Tax Documents</h6>
                                    <p class="text-muted small mb-0">Download tax-related documents</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="{{ route('terms') }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-file-paper-line text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">Terms & Conditions</h6>
                                    <p class="text-muted small mb-0">Review billing terms and policies</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.billing-settings-page .form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.billing-settings-page .form-switch .form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.billing-settings-page .list-group-item {
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.billing-settings-page .list-group-item:last-child {
    border-bottom: none;
}

.billing-settings-page .list-group-item:hover {
    background-color: #f8fafc;
}

.billing-settings-page .list-group-item h6 {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.billing-settings-page .list-group-item p {
    font-size: 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save notification preferences
    const saveNotificationsBtn = document.getElementById('saveNotifications');
    if (saveNotificationsBtn) {
        saveNotificationsBtn.addEventListener('click', function() {
            // Get current values
            const invoiceNotifications = document.getElementById('invoiceNotifications').checked;
            const paymentNotifications = document.getElementById('paymentNotifications').checked;
            const overdueNotifications = document.getElementById('overdueNotifications').checked;
            const weeklySummary = document.getElementById('weeklySummary').checked;
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ri-loader-4-line spinner"></i> Saving...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                showToast('Notification preferences saved successfully!', 'success');
                
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    }
    
    // Save payment preferences
    const savePaymentPreferencesBtn = document.getElementById('savePaymentPreferences');
    if (savePaymentPreferencesBtn) {
        savePaymentPreferencesBtn.addEventListener('click', function() {
            const defaultPaymentMethod = document.getElementById('defaultPaymentMethod').value;
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ri-loader-4-line spinner"></i> Saving...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                showToast('Payment preferences saved successfully!', 'success');
                
                // Restore button
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ri-check-line me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        // Show toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function () {
            this.remove();
        });
    }
});
</script>
@endsection