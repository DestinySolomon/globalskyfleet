<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share notification data with admin layout
        View::composer('layouts.admin', function ($view) {
            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin'])) {
                $unreadNotificationsCount = Auth::user()->unreadNotifications()->count();
                $recentNotifications = Auth::user()->notifications()
                    ->latest()
                    ->limit(5)
                    ->get();
                
                $view->with([
                    'unreadNotificationsCount' => $unreadNotificationsCount,
                    'recentNotifications' => $recentNotifications
                ]);
            }
        });
    }
}