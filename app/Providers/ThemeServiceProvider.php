<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class ThemeServiceProvider extends ServiceProvider
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
        // Add prefersDarkMode method to Request class
        Request::macro('prefersDarkMode', function () {
            // For now, default to false (light mode)
            // This will be overridden by JavaScript client-side detection
            return false;
        });
    }
}
