<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bk');
        Carbon::setLocale('id');

        // Paksa https di production, atau kapan pun APP_URL memakai https
        // (mis. Render dengan APP_ENV belum diset) agar CSS tidak diblokir mixed content.
        if (
            $this->app->environment('production')
            || str_starts_with((string) config('app.url'), 'https://')
        ) {
            URL::forceScheme('https');
        }
    }
}