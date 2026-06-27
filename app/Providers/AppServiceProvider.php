<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS and asset URLs when accessed via ngrok or any HTTPS domain
        if (request()->getScheme() === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Routing\UrlGenerator::forceRootUrl(request()->getScheme() . '://' . request()->getHttpHost());
        }
    }
}
