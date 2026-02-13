<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('formatUserTime')) {
    /**
     * Format a timestamp in the user's timezone
     *
     * @param \Illuminate\Support\Carbon|null $datetime
     * @param string $format
     * @param \App\Models\User|null $user
     * @return string|null
     */
    function formatUserTime($datetime, $format = 'M d, Y H:i', $user = null)
    {
        if (!$datetime) {
            return null;
        }

        // Get the user - use auth if not provided
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }

        // Get user's timezone or default to UTC
        $timezone = $user?->timezone ?? config('app.timezone');

        // Convert the datetime to user's timezone and format it
        return $datetime->setTimezone($timezone)->format($format);
    }
}

if (!function_exists('userTimeZone')) {
    /**
     * Get user's timezone
     *
     * @param \App\Models\User|null $user
     * @return string
     */
    function userTimeZone($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }

        return $user?->timezone ?? config('app.timezone');
    }
}
