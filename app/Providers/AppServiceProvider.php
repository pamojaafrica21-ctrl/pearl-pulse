<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class);
    }

    public function boot(): void
    {
        View::composer('layouts.public', function ($view) {
            try {
                $settings = app(SettingService::class);
                $view->with([
                    'siteContact' => $settings->contact(),
                    'siteSocial' => $settings->social(),
                    'footerBlurb' => $settings->get('footer_blurb', 'Curated journeys through East Africa’s wildest landscapes.'),
                ]);
            } catch (\Throwable) {
                $view->with([
                    'siteContact' => ['address' => '', 'phone' => '', 'email' => '', 'admin_email' => ''],
                    'siteSocial' => ['instagram' => '', 'facebook' => '', 'twitter' => ''],
                    'footerBlurb' => 'Curated journeys through East Africa’s wildest landscapes.',
                ]);
            }
        });
    }
}
