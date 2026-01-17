<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Policies\AddressPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Address Policy
        Gate::policy(Address::class, AddressPolicy::class);
        
        // Share notifications data with admin layout
        View::composer('layouts.admin', function ($view) {
            $unreadNotificationsCount = 0;
            $recentNotifications = collect();
            
            if (Auth::check()) {
                $user = Auth::user();
                // Count unread notifications for the user
                $unreadNotificationsCount = \DB::table('notifications')
                    ->where('notifiable_id', $user->id)
                    ->where('notifiable_type', get_class($user))
                    ->whereNull('read_at')
                    ->count();
                
                // Get recent notifications (last 5) - get actual Notification model if available
                try {
                    $recentNotifications = $user->notifications()
                        ->latest()
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    // Fallback if notifications relationship doesn't exist
                    $recentNotifications = collect();
                }
            }
            
            $view->with('unreadNotificationsCount', $unreadNotificationsCount)
                 ->with('recentNotifications', $recentNotifications);
        });
    }
}