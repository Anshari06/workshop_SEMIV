<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Agar route() & asset() pakai URL sesuai request saat ini (bukan APP_URL)
        $scheme = request()->getScheme();           // http atau https
        $host = request()->getHttpHost();           // domain yang diakses

        if ($scheme && $host) {
            URL::forceRootUrl($scheme . '://' . $host);
        }
    }
}