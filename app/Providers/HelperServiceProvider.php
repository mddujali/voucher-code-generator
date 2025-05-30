<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (glob(__DIR__ . '/../Support/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ///
    }
}
