<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SettingsController;

// Home Page
Route::get('/', [PageController::class, 'home'])->name('home');

// Other Pages
Route::get('/services', [PageController::class, 'services'])->name('services');

// Tracking routes
Route::get('/tracking', [ShipmentController::class, 'track'])->name('tracking');
Route::post('/tracking', [ShipmentController::class, 'track'])->name('tracking.submit');

// Quote routes
Route::get('/quote', [PageController::class, 'quote'])->name('quote');
Route::post('/quote/submit', [PageController::class, 'quoteSubmit'])->name('quote.submit');

// Add this route
Route::post('/contact/sales', [PageController::class, 'contactSales'])->name('contact.sales');

Route::get('/about', [PageController::class, 'about'])->name('about');

// Contact routes
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Social login routes (for future implementation)
Route::get('/auth/{provider}', [PageController::class, 'socialLogin'])->name('social.login');

Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Form submissions
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// ==================== AUTHENTICATION ROUTES ====================
// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Add after your auth routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// ==================== PROTECTED ROUTES (Require Authentication) ====================
Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible only to logged-in users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

       // Address Management
    Route::resource('addresses', AddressController::class);

      // Custom route for setting default address
    Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])
        ->name('addresses.set-default');


    // Shipment Management with middleware
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::resource('shipments', ShipmentController::class);
        
        // Add the cancel route
        Route::post('/shipments/{shipment}/cancel', [ShipmentController::class, 'cancel'])
            ->name('shipments.cancel')
            ->middleware('throttle:60,1');
    });

     Route::get('/dashboard/tracking', [ShipmentController::class, 'dashboardTracking'])->name('dashboard.tracking');


     // Document Management


// Document Management - REORDERED
Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');
Route::get('/shipments/{shipment}/generate-label', [DocumentController::class, 'generateShippingLabel'])->name('shipments.generate-label');
Route::get('/shipments/{shipment}/generate-invoice', [DocumentController::class, 'generateInvoice'])->name('shipments.generate-invoice');
Route::resource('documents', DocumentController::class);



// ==================== BILLING ROUTES ====================
Route::prefix('billing')->name('billing.')->group(function () {
    // Main billing page
    Route::get('/', [BillingController::class, 'index'])->name('index');
    
    // Invoice management
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');
    
    // Payment management
    Route::get('/pay/{invoice}', [BillingController::class, 'payInvoice'])->name('pay');
    Route::post('/pay/{invoice}/submit', [BillingController::class, 'submitPayment'])->name('pay.submit');
    Route::get('/payments', [BillingController::class, 'payments'])->name('payments');
    
    // Payment proof upload
    Route::post('/payment/{payment}/upload-proof', [BillingController::class, 'uploadProof'])->name('upload-proof');
});

// // ==================== ADMIN BILLING ROUTES ====================
// Route::prefix('admin/billing')->name('admin.billing.')->middleware(['auth', 'admin'])->group(function () {
//     Route::get('/addresses', [BillingController::class, 'manageAddresses'])->name('addresses');
//     Route::post('/addresses', [BillingController::class, 'storeAddress'])->name('addresses.store');
//     Route::put('/addresses/{address}', [BillingController::class, 'updateAddress'])->name('addresses.update');
//     Route::delete('/addresses/{address}', [BillingController::class, 'deleteAddress'])->name('addresses.delete');
    
//     Route::get('/payments/pending', [BillingController::class, 'pendingPayments'])->name('payments.pending');
//     Route::post('/payments/{payment}/verify', [BillingController::class, 'verifyPayment'])->name('payments.verify');
//     Route::post('/payments/{payment}/reject', [BillingController::class, 'rejectPayment'])->name('payments.reject');
    
//     // Optional: Add a dashboard for admin billing
//     Route::get('/dashboard', [BillingController::class, 'adminDashboard'])->name('dashboard');
// });


// ==================== BILLING ROUTES ====================
Route::prefix('billing')->name('billing.')->middleware(['auth'])->group(function () {
    // Main billing dashboard
    Route::get('/', [BillingController::class, 'index'])->name('index');
    
    // Invoice management
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');
    
    // Payment management
    Route::get('/pay/{invoice}', [BillingController::class, 'payInvoice'])->name('pay');
    Route::post('/pay/{invoice}/submit', [BillingController::class, 'submitPayment'])->name('pay.submit');
    Route::get('/payments', [BillingController::class, 'payments'])->name('payments');
    
    // Payment proof upload
    Route::post('/payment/{payment}/upload-proof', [BillingController::class, 'uploadProof'])->name('upload-proof');
    

 Route::get('/rates', [BillingController::class, 'getRates'])->name('get-rates');

    // Billing settings
    Route::get('/settings', [BillingController::class, 'settings'])->name('settings');
    
    // Export payments
    Route::get('/payments/export', [BillingController::class, 'exportPayments'])->name('payments.export');
    
    // Payment details API
    Route::get('/payment/{payment}/details', [BillingController::class, 'paymentDetails'])->name('payment.details');
});

// Exchange rate API
Route::get('/rates', [BillingController::class, 'getRates'])->name('get-rates');


   // User Profile Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Profile
    Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/picture/delete', [UserProfileController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
    
    // Account Settings
    Route::get('/account', [UserProfileController::class, 'showAccount'])->name('account');
    Route::post('/account/update', [UserProfileController::class, 'updateAccount'])->name('account.update');
    
    // Privacy & Security
    Route::get('/security', [UserProfileController::class, 'showSecurity'])->name('security');
    Route::post('/password/update', [UserProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/two-factor/toggle', [UserProfileController::class, 'toggleTwoFactor'])->name('two-factor.toggle');
    Route::post('/sessions/{session}/terminate', [UserProfileController::class, 'terminateSession'])->name('sessions.terminate');
    Route::post('/sessions/terminate-all', [UserProfileController::class, 'terminateAllOtherSessions'])->name('sessions.terminate-all');
    
    // Data Management
    Route::get('/data/export', [UserProfileController::class, 'exportData'])->name('data.export');
    Route::post('/account/deactivate', [UserProfileController::class, 'deactivateAccount'])->name('account.deactivate');
});


// ==================== NOTIFICATION ROUTES ====================
Route::prefix('notifications')->name('notifications.')->group(function () {
    // Get notifications (with pagination)
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    
    // Get unread count (for polling)
    Route::get('/count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('count');
    
    // Mark as read
    Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
    
    // Mark all as read
    Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('read.all');
    
    // Clear all notifications
    Route::delete('/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('clear.all');
});

    // You can add more protected routes here later:
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    // Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    // etc.
});


// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Shipments
    Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments');
    Route::get('/shipments/{id}', [AdminController::class, 'showShipment'])->name('shipments.show');
    Route::post('/shipments/{id}/status', [AdminController::class, 'updateShipmentStatus'])->name('shipments.update-status');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');

        // User delete route
     Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    
    // Documents
    Route::get('/documents', [AdminController::class, 'documents'])->name('documents');
    Route::get('/documents/{id}/view', [AdminController::class, 'viewDocument'])->name('documents.view');
    Route::get('/documents/{id}', [AdminController::class, 'documentDetails'])->name('documents.show');
    
    // Wallet Management - FIXED NAMES
    Route::get('/wallets', [AdminController::class, 'wallets'])->name('wallets');
    Route::post('/wallets', [AdminController::class, 'storeWallet'])->name('wallets.store');
    Route::get('/wallets/{id}/edit', [AdminController::class, 'editWallet'])->name('wallets.edit');
    Route::put('/wallets/{id}', [AdminController::class, 'updateWallet'])->name('wallets.update');
    Route::delete('/wallets/{id}', [AdminController::class, 'destroyWallet'])->name('wallets.destroy');
    
  // In your admin routes group
Route::get('/payments/crypto', [AdminController::class, 'cryptoPayments'])->name('payments.crypto');
Route::get('/payments/crypto/{id}', [AdminController::class, 'showCryptoPayment'])->name('payments.crypto.show');
Route::post('/payments/crypto/{id}/update', [AdminController::class, 'updateCryptoPayment'])->name('payments.update');
Route::get('/payments/crypto/export', [AdminController::class, 'exportCryptoPayments'])->name('payments.crypto.export');


   
    
    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/analytics/export', [AdminController::class, 'exportAnalytics'])->name('analytics.export');
    
    
    // Settings Routes
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
    Route::put('/', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
    Route::get('/email', [\App\Http\Controllers\Admin\SettingsController::class, 'emailTemplates'])->name('email');
    Route::post('/email/{template}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateEmailTemplate'])->name('email.update');
    Route::get('/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache');
    Route::get('/backup', [\App\Http\Controllers\Admin\SettingsController::class, 'backupDatabase'])->name('backup');
});
});