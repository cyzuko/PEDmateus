<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Channels\SmsChannel;
use App\Services\SmsService;

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
        // Registar o canal SMS personalizado
        Notification::extend('sms', function ($app) {
            return new SmsChannel($app->make(SmsService::class));
        });
    }
}