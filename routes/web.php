<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Auth\ForgotPasswordController;

// Home Page
Route::get('/', [PageController::class, 'home'])->name('home');

// Other Pages
Route::get('/services', [PageController::class, 'services'])->name('services');

// Tracking routes
Route::get('/tracking', [PageController::class, 'tracking'])->name('tracking');
Route::get('/tracking/submit', [PageController::class, 'trackingSubmit'])->name('tracking.submit');

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
    
    // You can add more protected routes here later:
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    // Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    // etc.
});