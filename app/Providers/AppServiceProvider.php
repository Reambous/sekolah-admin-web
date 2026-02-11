<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // <--- Tambahkan ini
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // SET BAHASA INDONESIA
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        // Fix untuk database string length (opsional tapi bagus)
        Schema::defaultStringLength(191);

        // if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
        //     URL::forceScheme('https');
        // }

        if (str_contains(request()->getHost(), 'ngrok-free.app') || str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }
    }
}
