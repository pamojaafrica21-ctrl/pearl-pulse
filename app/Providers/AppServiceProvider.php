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
        config([
            'livewire.temporary_file_upload.rules' => ['required', 'file', 'max:102400'],
        ]);

        View::composer('layouts.public', function ($view) {
            try {
                $settings = app(SettingService::class);
                $view->with([
                    'siteContact' => $settings->contact(),
                    'siteSocial' => $settings->social(),
                    'reviewLinks' => $settings->reviewLinks(),
                    'footerBlurb' => $settings->get('footer_blurb', 'Private journeys through East Africa.'),
                    'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
                    'navCountries' => \App\Models\Country::query()->published()->orderBy('sort_order')->get(),
                    'navExperiences' => \App\Models\Experience::query()->published()->orderBy('sort_order')->get(),
                ]);
            } catch (\Throwable) {
                $view->with([
                    'siteContact' => ['address' => '', 'phone' => '', 'email' => '', 'admin_email' => '', 'whatsapp' => ''],
                    'siteSocial' => ['instagram' => '', 'facebook' => '', 'twitter' => ''],
                    'reviewLinks' => ['google' => '', 'tripadvisor' => ''],
                    'footerBlurb' => 'Private journeys through East Africa.',
                    'whatsappUrl' => null,
                    'navCountries' => collect(),
                    'navExperiences' => collect(),
                ]);
            }
        });
    }
}
